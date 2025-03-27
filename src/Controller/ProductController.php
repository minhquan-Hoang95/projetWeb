<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
use App\Form\CommandType;
use App\Form\PanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/product', name: 'product')]
final class ProductController extends AbstractController
{
    #[Route('/', name: '')]
    public function indexAction(): Response
    {
        $arg = array(
            'page' => 1,
        );

        return $this->redirectToRoute('product_list', $arg);
    }

    #[Route('/list', name: '_list',
    )]
    #[IsGranted('ROLE_CLIENT')]
    public function listAction(EntityManagerInterface $em, Request $request): Response
    {
        //$panier = new Panier();
        $user = $this->getUser();

        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findAll();

        $panierRepository = $em->getRepository(Panier::class);

        $forms = [];

        foreach ($products as $product) {
            $panier = $panierRepository->findOneBy(['user' => $user, 'product' => $product]); // find panier for user and product

            $form = $this->createForm(CommandType::class, null,
                ['data' =>
                    ['quantityInStock' => $product->getQuantityInStock(),
                        'quantityInPanier' => $panier ? $panier->getDesireQuantity() : 0,
                        'product' => $product,
                    ]
                ]); // create form for each product with quantity in stock as choices

            $form->handleRequest($request);
            $forms[$product->getId()] = $form->createView(); // add form to array with product id as key

            if($form->isSubmitted() && $form->isValid())
            {
                $nbChoix = $form->get('choix')->getData();
                $productId = $form->get('product')->getData();
                $product = $productRepository->find($productId);

                if($product)
                {
                    $panier = $panierRepository->findOneBy(['user' => $user, 'product' => $product]);
                    if($panier)
                    {
                        $panier->setDesireQuantity($panier->getDesireQuantity() + $nbChoix);
                    }
                    else
                    {
                        $panier = new Panier();

                        $panier
                           ->setUser($this->getUser())
                            ->setProduct($product)
                            ->setDesireQuantity($nbChoix);
                        //$user->addPanier($panier);
                        //$product->addPanier($panier);
                    }

                    $product->setQuantityInStock($product->getQuantityInStock() - $nbChoix);
                    $em->persist($panier);
                    $em->persist($product);
                    $em->flush();
                }
                $this->addFlash('info', 'Produit ajouté au panier');
                return $this->redirectToRoute('product_list');
            }

        }

        if($form->isSubmitted())
        {
            $this->addFlash('info', 'Formulaire incorrect');
        }

        $args = array(
            'products' => $products,
            'myforms' => $forms
        );
        return $this->render('Product/list.html.twig', $args);
    }













}
