<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'=> false,
                'attr' => ['class' => 'form-control email', 'placeholder' => 'Email']
            ])
            ->add('username', TextType::class, [
                'label'=> false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Pseudo']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=> 'Conditions à accepter',
                'attr' => ['class' => 'form-check-input'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                'class' => 'form-control password',
                'placeholder' => 'Mot de passe'
                ],
                'label'=> false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label'=> false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ville']
            ])
            ->add('is_breeder', CheckboxType::class, [
                'label'    => 'Êtes-vous un éleveur ?',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'data-toggle' => 'breeder-toggle'
                ] 
            ])
            ->add('siret', TextType::class, [
                'label'=> false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Siret',
                    'data-breeder' => 'true',
                    'style' => 'display: none;'
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label'=> false,
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Numéro de téléphone',
                    'data-breeder' => 'true',
                    'style' => 'display: none;'
                ]
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
