<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',
            TextType::class,
            [
                'label' => 'Libelle',
                'attr' => ['placeholder' => 'nom du produit']
            ])
            ->add('unitPrice',
                NumberType::class,
                [
                    'label' => 'Prix',
                    'attr' => ['placeholder' => 'prix du produit']
                ]
            )
            ->add('quantityInStock', IntegerType::class,
            [
                'label' => 'Quantite en stock',
                'attr' => ['placeholder' => 'quantite en stock']
            ])
           /* ->add('pays',
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

                ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
