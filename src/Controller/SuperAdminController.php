<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/superadmin', name: 'superadmin')]
final class SuperAdminController extends AbstractController
{
    #[Route('/addAdmin', name: '_addAdmin')]
    public function addAdminAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher) : Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class, ['label' => 'Add user']);
        $form->handleRequest($request); // for processing the form submission and handling the form data

        if($form->isSubmitted() && $form->isValid())
        {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_ADMIN']);


            $em->persist($user); // persist() tells Doctrine to "manage" the entity
            $em->flush(); // flush() tells Doctrine to save the entity to the database
            $this->addFlash('info', 'Ajouter user réussi'); // addFlash() adds a flash message to the session
            return $this->redirectToRoute('home_index'); // redirectToRoute() generates a URL and redirects to it
        }
        // if the form is submitted but not valid, add a flash message
        if($form->isSubmitted())
        {
            $this->addFlash('info', 'formulaire ajout user incorrect');
        }

        $args = array(
            'myform' => $form->createView(),
        );

        return $this->render('SuperAdmin/add.html.twig', $args);


    }

}
