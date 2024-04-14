<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $nbMax = $options['data']['max'];
        $nbMin = $options['data']['min'];
        $choices = [];
        for ($nbArticle=$nbMin; $nbArticle<=$nbMax; $nbArticle++)
            $choices[$nbArticle] = $nbArticle;
        $builder
            ->add('nbArticles',
                ChoiceType::class,
                [
                    'label' => 'Modifiez votre panier',
                    'data' => 0,
                    'choices' => $choices,
                    'mapped' => false,
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
