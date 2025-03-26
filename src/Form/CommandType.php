<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $quantityPanier = $options['data']['quantityInPanier'];
        $quantityInStock = $options['data']['quantityInStock'];

        $interval = range(-$quantityPanier, $quantityInStock, 1);
        $choices = array_combine($interval, $interval); // [0 => -2, 1 => -1, 2 => 0, 3 => 1, 4 => 2]
        $builder
            ->add('choix', ChoiceType::class, [
                'choices' => $choices,
                'mapped' => false,
                'expanded' => false,
                'data' => 0,
            ])
           ->add('action', SubmitType::class,
            [
                'label' => 'Commander',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
