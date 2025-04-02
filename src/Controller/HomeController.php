<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    private CountService $countService;
    public function __construct(CountService $countService)
    {
        $this->countService = $countService;
    }

    #[Route('/', name: 'home_index')]
    public function indexAction(): Response
    {
        return $this->render('Home/index.html.twig');
    }

    public function menuAction(Security $security) : Response
    {
        $user = $security->getUser();


        $args = array(
            'user' => $user,
        );

        if($security->isGranted('ROLE_ADMIN'))
        {
            return $this->render('Menu/admin.html.twig', $args);
            //return $this->redirectToRoute('security_admin');
        }
        elseif($security->isGranted('ROLE_SUPER_ADMIN'))
        {
            return $this->render('Menu/superadmin.html.twig', $args);
            // $this->redirectToRoute('security_superadmin');
        }
        elseif($security->isGranted('ROLE_CLIENT'))
        {
            return $this->render('Menu/client.html.twig', $args);
            // return $this->redirectToRoute('security_client');
        }
        else
        {
            return $this->render('Menu/visitor.html.twig');
            //   return $this->redirectToRoute('security_visitor');
        }

    }

    #[Route('/article', name: 'home_article')]
    public function articleAction(Session $session): Response
    {
        $user = $this->getUser();
        if($user)
        {
            $countArticles = $this->countService->countProduct($user->getId());
        }
        else
        {
            $countArticles = 0;
        }
        dump($countArticles);
        $args = array(
            'countArticles' => $countArticles,
        );
        return $this->render('Home/article.html.twig', $args);
    }
}
