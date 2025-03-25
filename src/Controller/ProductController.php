<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

        return $this->redirectToRoute('produit_list', $arg);
    }

    #[Route('/list{page}', name: '_list',
    requirements: ['page' => '[1-9)]\d*'],
        defaults: ['page' => 0],
    )]
    public function listAction(int $page, EntityManagerInterface $em): Response
    {
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findAll();

        $arg = array(
            'page ' => $page,
            'products' => $products,
        );

        return $this->render('Product/list.html.twig', $arg);
    }


}
