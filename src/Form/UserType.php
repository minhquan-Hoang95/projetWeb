<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

            $builder
                ->add('login',
                    TextType::class,
                    [
                        'label' => 'Login ',])
               /* ->add('roles',
                ChoiceType::class,
                    [
                        'label' => 'Roles',
                        'choices' => [
                            'Client' => 'ROLE_CLIENT',
                            'Admin' => 'ROLE_ADMIN',
                            'Super Admin' => 'ROLE_SUPER_ADMIN',

                        ],
                        'multiple' => true, // choix multiple possible
                        'expanded' => true,
                    ])*/
                ->add('password',
                    PasswordType::class,
                    [
                        'label' => 'Password ',
                    ])
                ->add('firstname',
                    TextType::class,
                    [
                        'label' => 'Firstname',
                    ])
                ->add('lastname',
                    TextType::class,
                    [
                        'label' => 'Lastname',
                    ])
                ->add('birthdate',
                    DateType::class,
                    [
                        'label' => 'Birthday',
                    ])
             ->add('pays',
                    EntityType::class,
                    [
                        'class' => Pays::class,
                        'label' => 'Pays',
                        'choice_label' => function (Pays $pays) {
                            return
                                $pays->getName()
                                . '('
                                . (is_null($pays->getCode()) ? '???' : $pays->getCode())
                                .')';
                        },
                        'placeholder' => 'Choisissez un pays',
                        'expanded' => false, // une liste déroulante

                    ])
                /*->add('pays',
                CountryType::class,
                [
                    'label' => 'Pays',
                    //'preferred_choices' => ['FR', 'BE', 'CH', 'DE', 'IT', 'ES', 'GB', 'US'],
                ])*/
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
