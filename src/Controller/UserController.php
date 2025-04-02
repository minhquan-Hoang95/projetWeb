<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\CountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\Translation\t;

#[Route('/user', name: 'user')]

final class UserController extends AbstractController
{
    #[Route('/', name: '')]
    public function displayUserAction(EntityManagerInterface $em): Response
    {
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findAll();

        $args = array(
            'users' => $users,
        );

        return $this->render('User/display.html.twig', $args);
    }

    #[Route('/view', name: '_view_user')]
    public function viewUserAction(): Response
    {
       $user = $this->getUser();

        return $this->render('User/view.html.twig');
    }

    #[Route('/adduser', name: '_adduser')]
    public function addUserAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->add('submit', SubmitType::class, ['label' => 'Add user']);
        $form->handleRequest($request); // for processing the form submission and handling the form data

        if($form->isSubmitted() && $form->isValid())
        {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_CLIENT']);


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

        return $this->render('User/add.html.twig', $args);
    }

    #[Route('/edituser', name: '_edit')]
    public function editUserAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = $this->getUser();
        if(is_null($user))
        {
            throw $this->createNotFoundException('Utilisateur avec login : '  . ' inexistant');
        }
        // Create a form to edit the user
        $form = $this->createForm(UserType::class, $user); // createForm() creates a form
        $form->add('submit', SubmitType::class, ['label' => 'Edit user']); // add a submit button to the form
        $form->handleRequest($request); // handleRequest() processes the form submission

        // if the form is submitted and valid, update the user in the database
        if($form->isSubmitted() && $form->isValid())
        {

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $em->flush(); // flush() tells Doctrine to save the entity to the database
            // si c'est superadmin => redirection vers la page d'accueil // TODO
            // sinon redirection vers la page de profil ou lister les produits // TODO
            $this->addFlash('info', 'Edition user réussie'); // addFlash() adds a flash message to the session
            return $this->redirectToRoute('home_index'); // redirectToRoute() generates a URL and redirects to it
        }
        // if the form is submitted but not valid, add a flash message
        if($form->isSubmitted())
        {
            $this->addFlash('info', 'formulaire edition user incorrect');
        }

        $args = array(
            'myform' => $form->createView(),
        );

        return $this->render('User/edit.html.twig', $args);
    }

}
