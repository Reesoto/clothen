<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Choose a name for this address",
                "attr"  =>  [
                    "placeholder"   => "Ex : office, house ..."
                ]
            ])
            ->add('firstname', TextType::class, [
                "label" => "Firstname",
                "attr"  =>  [
                    "placeholder"   => "John"
                ]
            ])
            ->add('lastname', TextType::class, [
                "label" => "Lastname",
                "attr"  =>  [
                    "placeholder"   => "Doe"
                ]
            ])
            ->add('company', TextType::class, [
                "label" => "Company name (optionnal)",
                "required"  => false,
                "attr"  =>  [
                    "placeholder"   => "Ex : Emblem Inc."
                ]
            ])
            ->add('address', TextType::class, [
                "label" => "Street",
                "attr"  =>  [
                    "placeholder"   => "1 rue de la Libération"
                ]
            ])
            ->add('zipcode', TextType::class, [
                "label" => "Zipcode",
                "attr"  =>  [
                    "placeholder"   => "75008"
                ]
            ])
            ->add('city', TextType::class, [
                "label" => "City",
                "attr"  =>  [
                    "placeholder"   => "Paris"
                ]
            ])
            ->add('country', CountryType::class, [
                "label" => "Country",
                "attr"  =>  [
                    "placeholder"   => "France"
                ]
            ])
            ->add('phone', TelType::class, [
                "label" => "Phone",
                "attr"  =>  [
                    "placeholder"   => "+33612345678"
                ]
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Save my address",
                "attr"  => [
                    "class" => "btn-block btn-info"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
