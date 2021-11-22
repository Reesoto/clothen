<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label'     => 'Firstname'
            ])
            ->add('lastname', EmailType::class, [
                'disabled' => true,
                'label'     => 'Lastname'
            ])
            ->add('old_password', PasswordType::class, [
                'mapped'    => false,
                'label'     => 'Current password',
                'attr'      => [
                    'placeholder'   => 'Please fill with your current password'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type'              =>  PasswordType::class,
                'mapped'            =>  false,
                'invalid_message'   =>  'Passwords must match together',
                'label'             =>  'My new password' ,
                'required'          =>  true,
                'first_options'     =>  ['label'  =>  'My new password'],
                'second_options'    =>  ['label'  =>  'Confirm your password']
            ])
            ->add('submit', SubmitType::class, [
                'label' =>  'Update'
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
