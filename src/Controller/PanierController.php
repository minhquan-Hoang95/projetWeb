<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
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
    #[IsGranted(new Expression('is_granted(\'ROLE_ADMIN\') or is_granted("ROLE_CLIENT")'))]
    public function deleteAction(int $id, EntityManagerInterface $em): Response
    {
        $panierRepository = $em->getRepository(Panier::class);
        $panier = $panierRepository->find($this->getUser());

        if (is_null($panier)) {
            throw new NotFoundHttpException('Panier ' . $id . ' inexistant');
        }

        $em->remove($panier);
        $em->flush();

        return $this->redirectToRoute('panier_view');
    }

    #[Route('/clear', name: '_clear')]
    public function clearAction(EntityManagerInterface $em): Response
    {
        $user = $this->getUser(); // get current user from security context
        $panierRepository = $em->getRepository(Panier::class);
        $paniers = $panierRepository->findBy(['user' => $user]);


        foreach ($paniers as $panier) {
            $em->remove($panier);
        }

        $em->flush();

        return $this->redirectToRoute('panier_view');
    }

    #[Route('order', name: '_order')]
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
