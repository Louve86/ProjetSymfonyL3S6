<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Product;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',
            TextType::class,
            [
                'label' => 'Nom du produit :',
            ])
            ->add('price',
            NumberType::class,
            [
                'label' => 'Prix:',
            ])
            ->add('quantity',
            IntegerType::class,
            [
                'label'=>'Nombre en stock :'
            ])
            ->add('countries',
                EntityType::class, [
                    'label' => 'Pays :',
                    'class' => Country::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
