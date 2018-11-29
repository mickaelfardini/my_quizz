<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('roles', ChoiceType::class, ['choices' => [
                "User" => "ROLE_USER",
                "Admin" => "ROLE_ADMIN",
            ],
                "expanded" => true,
                "multiple" => true
            ])
            ->add('active', ChoiceType::class, ['choices' => [
                "Inactive" => 0,
                "Active" => 1,
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
