<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
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
                    'label' => 'Login :',
                ])
            //->add('roles')

            ->add('password',
                PassWordType::class,

                [
                    'hash_property_path' => 'password',
                    'mapped' => false,
                    'label' => 'Mot de passe :',
                ])

            ->add('name',
                TextType::class,
                [
                    'label' => 'Nom :',
                ])

            ->add('firstname',
                TextType::class,
                [
                    'label' => 'Prénom :',
                ])
            ->add('birthdate',
                BirthdayType::class,
                [
                    'label' => 'Date de naissance :',
                ])

            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Pays :',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
