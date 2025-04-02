<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
use App\Form\CommandType;
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

        // get/fetch tous les produits dans la base de données
        $productRepository = $em->getRepository(Product::class);
        // find all products and return them as an array
        $products = $productRepository->findAll();

        // get/fetch panier repository
        $panierRepository = $em->getRepository(Panier::class);
        // Vu qu'on a besoin d'un tableau de formulaires, on va créer un tableau de formulaires vides à la base
        $forms = [];

        // Je vais regarder pour chaque produit s'il y a un panier associé à l'utilisateur
        foreach ($products as $product) {
            // Je cherche le panier associé à l'utilisateur et au produit
            $panier = $panierRepository->findOneBy(['user' => $user, 'product' => $product]); // find panier for user and product

            // Je vais créer un formulaire et donner manger à array $option pour construire le formulaire "choix" et "action"
            $form = $this->createForm(CommandType::class, null,
                ['data' =>
                    ['quantityInStock' => $product->getQuantityInStock(), // je donne la quantité en stock de produit correspondant
                        'quantityInPanier' => $panier ? $panier->getDesireQuantity() : 0 , // je donne la quantité en stock de produit correspondant $panier ? $panier->getDesireQuantity() : 0
                        'product' => $product, // je donne le produit correspondant pour récupérer l'id du produit
                    ]
                ]); // create form for each product with quantity in stock as choices

            $form->handleRequest($request); // Request will handle the form submission
            // En fonction de id de produit, je vais créer un tableau de formulaires
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
                        $this->addFlash('info', 'Produit déjà dans le panier, quantité mise à jour');
                        if($panier->getDesireQuantity() == 0)
                        {
                            $em->remove($panier);
                            $this->addFlash('info', 'Produit retiré du panier');

                        }

                    }
                    else
                    {
                        $panier = new Panier();
                        $panier
                           //->setUser($this->getUser())
                            //->setProduct($product)
                            ->setDesireQuantity($nbChoix);

                        $this->getUser()->addPanier($panier);
                        $product->addPanier($panier);
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

        $paniers = $panierRepository->findBy(['user' => $user]);



        $args = array(
            'products' => $products,
            'myforms' => $forms,
            'paniers' => $paniers,
        );
        return $this->render('Product/list.html.twig', $args);
    }













}
