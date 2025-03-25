<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
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
}
