<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $min=$options['data']['min'];
        $max=$options['data']['max'];
        $choices=[];
        for($nbArticles = $min;$nbArticles<$max+1;$nbArticles++){
            $choices[$nbArticles]=$nbArticles;
        }
        $builder
            ->add('nbArticles',
            ChoiceType::class,
                [
                    'label'=>'Modifiez votre panier',
                    'choices'=> $choices,
                    'mapped'=>false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
