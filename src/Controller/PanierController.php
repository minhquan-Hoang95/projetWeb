<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
use App\Service\CountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panier', name: 'panier')]
final class PanierController extends AbstractController
{
    #[Route('/', name: '_list')]
/*    #[IsGranted(new Expression('is_granted(\'ROLE_ADMIN\') or is_granted("ROLE_CLIENT")'))]*/
    public function listAction(EntityManagerInterface $em): Response
    {
        $panierRepository = $em->getRepository(Panier::class);
        $paniers = $panierRepository->findAll();


        $args = array(
            'paniers' => $paniers,
        );

        return $this->render('Panier/list.html.twig', $args);
    }

    #[Route('/view', name: '_view',)]  /*requirements: ['id' => '[1-9]\d*']*/
/*    #[IsGranted(new Expression('is_granted(\'ROLE_ADMIN\') or is_granted("ROLE_CLIENT")'))]*/

    public function viewAction(EntityManagerInterface $em): Response
    {
        $panierRepository = $em->getRepository(Panier::class);
        $panier = $panierRepository->findBy(['user' => $this->getUser()] );

        if (is_null($panier)) {
            throw new NotFoundHttpException('Panier de '  . ' inexistant');
        }

        $args = array(
            'paniers' => $panier,
        );

        return $this->render('Panier/view.html.twig', $args);
    }

    // Suppression d'un panier
    #[Route('/delete/{id}',
        name: '_delete',
        requirements: ['id' => '[1-9]\d*']
    )]
/*    #[IsGranted(new Expression('is_granted(\'ROLE_ADMIN\') or is_granted("ROLE_CLIENT")'))]*/
    public function deleteAction(int $id, EntityManagerInterface $em): Response
    {
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->find($id);

        if (is_null($product)) {
            throw $this->createNotFoundException('Produit ' . $id . ' inexistant');
        }

        $panierRepository = $em->getRepository(Panier::class);
        $panier = $panierRepository->findOneBy(['user' => $this->getUser(), 'product' => $product]);

        if (is_null($panier)) {
            throw $this->createNotFoundException('Panier inexistant');
        }

        $product->removePanier($panier); // remove panier from product to avoid circular reference
        $product->setQuantityInStock($product->getQuantityInStock() + $panier->getDesireQuantity());

        $em->remove($panier);
        $em->flush();

        return $this->redirectToRoute('panier_view');
    }
    #[Route('/clear', name: '_clear')]
/*    #[IsGranted(new Expression('is_granted(\'ROLE_ADMIN\') or is_granted("ROLE_CLIENT")'))]*/

    public function clearAction(EntityManagerInterface $em): Response
    {
        $user = $this->getUser(); // get current user from security context
        $panierRepository = $em->getRepository(Panier::class);
        $paniers = $panierRepository->findBy(['user' => $user]);

       // $productRepository = $em->getRepository(Product::class);

        foreach ($paniers as $panier) {
/*            $product = $productRepository->find($panier->getProduct());*/
            $productId = $panier->getProduct()->getId();
            $this->deleteAction($productId, $em);

        }


        return $this->redirectToRoute('panier_view');
    }

    #[Route('order', name: '_order')]
/*    #[IsGranted(new Expression('is_granted(\'ROLE_ADMIN\') or is_granted("ROLE_CLIENT")'))]*/

    public function orderAction(EntityManagerInterface $em): Response
    {
        $panierRepository = $em->getRepository(Panier::class);
        $paniers = $panierRepository->findBy(['user' => $this->getUser()]);

        foreach ($paniers as $panier) {
            $em->remove($panier);
        }

        $em->flush();

        return $this->redirectToRoute('panier_view');
    }
}
