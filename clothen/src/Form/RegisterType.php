<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
              'label' => 'Votre prénom',
              'attr' => [
                          'placeholder' => 'John'
              ]
            ])
            ->add('lastname', TextType::class, [
              'label' => 'Votre nom de famille',
              'attr' => [
                          'placeholder' => 'Doe'
              ]
            ])
            ->add('email', EmailType::class, [
              'label' => 'Votre email',
              'attr' => [
                          'placeholder' => 'john@doe.com'
              ]
            ])
            ->add('password', RepeatedType::class, [
                'type'  =>  PasswordType::class,
                'invalid_message'   =>  'Passwords must match together',
                'required'  => true,
                'first_options' => ['label'  =>  'Votre mot de passe'],
                'second_options' => ['label'  =>  'Confirmer votre mot de passe']
            ])
            ->add('submit', SubmitType::class, [
              'label' =>  'Envoyer'
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
