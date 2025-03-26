<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
use App\Form\CommandType;
use App\Form\PanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

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
    requirements: ['page' => '[1-9)]\d*'],
        defaults: ['page' => 0],
    )]
    public function listAction(EntityManagerInterface $em, Request $request): Response
    {
        //$panier = new Panier();
        $user = $this->getUser();

        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findAll();

        $panierRepository = $em->getRepository(Panier::class);



        $forms = [];

        foreach ($products as $product) {
            $panier = $panierRepository->findOneBy(['user' => $user, 'product' => $product]);
            // if panier is empty, create a new one
            if(is_null($panier)) {
                $panier = new Panier();
            }
            $form = $this->createForm(CommandType::class, null,
                ['data' =>
                    ['quantityInStock' => $product->getQuantityInStock(),
                        'quantityInPanier' => $panier->getDesireQuantity(),
                    ]
                ]); // create form for each product with quantity in stock as choices
           $forms[$product->getId()] = $form->createView(); // add form to array with product id as key
        }

        if($form->isSubmitted() && $form->isValid())
        {
           // $data = $form->getData();

            //$em->persist($data);*/
            $em->flush();
            $this->addFlash('info', 'Commande effectuée');
            return $this->redirectToRoute('product_list');
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


  /*  #[Route('/commander', name: '_commander')]
    public function updatePanier(Request $request, EntityManagerInterface $em, Panier $panier): Response
    {
        // When I click on "commander" button, I want to update the quantity of the product in the panier
        // And I want to update the quantity in stock of the product
        // And I want to update the total price of the panier
    }*/
  #[Route('/displayPanier', name: '_displayPanier')]
    public function displayPanierAction(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $panierRepository = $em->getRepository(Panier::class);

        $paniers = $panierRepository->findBy(['user' => $user]);

        //$productRepository = $em->getRepository(Product::class);
       // $products = $productRepository->findAll();

        /*foreach ($products as $product) {
            $panier = $panierRepository->findOneBy(['user' => $user, 'product' => $product]);

        }*/

        $args = array(
            'paniers' => $paniers,
        );

        return $this->render('Product/display_panier.html.twig', $args);
    }









}
