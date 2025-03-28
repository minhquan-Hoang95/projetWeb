<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin')]
final class AdminController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function indexAction(): Response
    {
        return $this->render('Admin/index.html.twig');
    }

    #[Route('/editUser', name: '_editUser')]
    public function editClientAction(EntityManagerInterface $em): Response
    {
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findAll();

        $args = array(
            'users' => $users,
        );

        return $this->render('Admin/editUser.html.twig', $args);

    }

    #[Route('/delete/{id}', name: '_delete', requirements: ['id' => '[1-9]\d*'])]
    //#[IsGranted('ROLE_CLIENT')]
    public function deleteAction(EntityManagerInterface $em, int $id): Response
    {
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($id);

        if (is_null($user))
            throw $this->createNotFoundException('user ' . $id . ' inexistant');
        $roles = $user->getRoles();
        if(in_array('ROLE_ADMIN', $roles) || in_array('ROLE_SUPER_ADMIN', $roles))
        {
            $this->addFlash('info', 'Vous ne pouvez pas supprimer un admin');
            return $this->redirectToRoute('admin_editUser');
        }


        $em->remove($user);

        $em->flush();
        $this->addFlash('info', 'Utilisateur supprimé : Client');
        return $this->redirectToRoute('admin_editUser');
    }

    #[Route('/addProduct', name: '_addProduct')]
    public function addProductAction(EntityManagerInterface $em, Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->add('submit', SubmitType::class, ['label' => 'Add product']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($product);
            $em->flush();
            $this->addFlash('info', 'Produit ajouté');
            return  $this->redirectToRoute('home_index');
        }
        if($form->isSubmitted())
        {
            $this->addFlash('info', 'Formulaire incorrect');
        }

        $args = array(
            'myform' => $form
           // 'myform' => $form->createView()
        );

        return $this->render('Admin/addProduct.html.twig', $args);
    }




}
