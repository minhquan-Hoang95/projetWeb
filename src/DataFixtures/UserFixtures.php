<?php

namespace App\DataFixtures;

use App\Entity\Pays;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private ?UserPasswordHasherInterface $passwordHasher = null;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $em): void
    {
        // Create a user
        $user1 = new User();
        $user1
            ->setLogin('sadmin')
            ->setFirstname('Admin')
            ->setLastname('super')
            //->setBirthDate(new \DateTime('1980-01-01'))
            ->setRoles(['ROLE_SUPER_ADMIN'])
        ;
        $hashedPassword = $this->passwordHasher->hashPassword($user1, 'nimdas');
        $user1->setPassword($hashedPassword);
        $em->persist($user1);

        $user2 = new User();
        $user2
            ->setLogin('gilles')
            ->setFirstname('Gilles')
            ->setLastname('Subrenat')
            //->setBirthDate(new \DateTime('1980-01-01'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_CLIENT'])
        ;
        $hashedPassword = $this->passwordHasher->hashPassword($user1, 'sellig');
        $user2->setPassword($hashedPassword);
        $em->persist($user2);

        $user3 = new User();
        $user3
            ->setLogin('rita')
            ->setFirstname('Rita')
            ->setLastname('Zrour')
            //->setBirthDate(new \DateTime('1980-01-01'))
            ->setRoles(['ROLE_CLIENT'])
        ;
        $hashedPassword = $this->passwordHasher->hashPassword($user3, 'atir');
        $user3->setPassword($hashedPassword);
        $em->persist($user3);

        $user4 = new User();
        $user4
            ->setLogin('boumediene')
            ->setFirstname('Boumediene')
            ->setLastname('MohaMed')
            //->setBirthDate(new \DateTime('1980-01-01'))
            ->setRoles(['ROLE_CLIENT'])
        ;
        $hashedPassword = $this->passwordHasher->hashPassword($user4, 'eneidemuob');
        $user4->setPassword($hashedPassword);
        $em->persist($user4);


        // Pays
        $pays1 = new Pays();
        $pays1
            ->setName('France')
            ->setCode('FR')
        ;
        $em->persist($pays1);

        $pays2 = new Pays();
        $pays2
            ->setName('Allemagne')
            ->setCode('DE')
        ;

        $em->persist($pays2);

        $pay3 = new Pays();
        $pay3
            ->setName('Italie')
            ->setCode('IT')
        ;

        $em->persist($pay3);

        $pay4 = new Pays();
        $pay4
            ->setName('Espagne')
            ->setCode('ES')
        ;

        $em->persist($pay4);

        $pay5 = new Pays();
        $pay5
            ->setName('Belgique')
            ->setCode('BE')
            ;
        $em->persist($pay5);

        $pay6 = new Pays();
        $pay6
            ->setName('Suisse')
            ->setCode('CH')
            ;
        $em->persist($pay6);

        $pay7 = new Pays();
        $pay7
            ->setName('Royaume-Uni')
            ->setCode('GB')
            ;

        $em->persist($pay7);

        // add users to pays
        $pays1->addUser($user1);
        $pays1->addUser($user2);
        $pays1->addUser($user3);
        $pays1->addUser($user4);

        $pays2->addUser($user1);
        $pays2->addUser($user2);

        $pay3->addUser($user3);
        $pay3->addUser($user4);

        $pay4->addUser($user1);
        $pay4->addUser($user2);

        /**
         * Produit
         */

        $product1 = new Product();
        $product1
            ->setLibelle('Box Thailande')
            ->setUnitPrice(27.00)
            ->setQuantityInStock(100)
            ;
        $em->persist($product1);

        $product2 = new Product();
        $product2
            ->setLibelle('Box Japon')
            ->setUnitPrice(30.00)
            ->setQuantityInStock(100)
            ;
        $em->persist($product2);

        $product3 = new Product();
        $product3
            ->setLibelle('Box Chine')
            ->setUnitPrice(25.00)
            ->setQuantityInStock(100)
            ;
        $em->persist($product3);

        $product4 = new Product();
        $product4
            ->setLibelle('Box Inde')
            ->setUnitPrice(20.00)
            ->setQuantityInStock(100)
            ;
        $em->persist($product4);

        $product5 = new Product();
        $product5
            ->setLibelle('Box Vietnam')
            ->setUnitPrice(22.00)
            ->setQuantityInStock(100)
            ;
        $em->persist($product5);

        // add pays to product

        $pays1->addProduct($product1);
        $pays1->addProduct($product2);
        $pays1->addProduct($product3);
        $pays1->addProduct($product4);
        $pays1->addProduct($product5);

        $pays2->addProduct($product1);
        $pays2->addProduct($product2);
        $pays2->addProduct($product3);
        $pays2->addProduct($product4);
        $pays2->addProduct($product5);

        $pay3->addProduct($product1);
        $pay3->addProduct($product2);
        $pay3->addProduct($product3);
        $pay3->addProduct($product4);
        $pay3->addProduct($product5);

        $pay4->addProduct($product1);
        $pay4->addProduct($product3);
        $pay4->addProduct($product5);

        $pay5->addProduct($product2);
        $pay5->addProduct($product4);

        $pay6->addProduct($product1);
        $pay6->addProduct($product4);

        $pay7->addProduct($product2);
        $pay7->addProduct($product5);




        $em->flush();
    }

}
