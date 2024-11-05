<?php

namespace App\Form;

use App\Entity\Announcement;
use App\Entity\CatGender;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddAnnouncementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('cat_name', TextType::class, [
            'attr' => ['class' => 'form-control',
            'placeholder' => 'Nom du chat'],
            'label'=> false
        ])
        ->add('cat_birth', DateType::class, [
            'attr' => ['class' => 'form-control'],
            'label'=> 'Date de naissance du chat'
        ])
        ->add('cat_loof', CheckboxType::class, [
            'label'    => 'Est-ce que le chat est loof ?',
            'required' => false,
            'attr' => ['class' => 'form-check-input']
        ])
        ->add('cat_gender', EntityType::class, [
            'class' => CatGender::class,
            'label' => 'Quel est le sexe du chat ?',
            'choice_label' => 'gender',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('description', TextareaType::class, [
            'attr' => ['class' => 'form-control form-description',
            'placeholder' => 'Description de l\'annonce'],
            'label'=> false
        ])
        ->add('photos', FileType::class, [
            'data_class' => null,
            'multiple' => true,
            'mapped' => false,
            'label'=> 'Photos du chat',
            'required' => false,
            'attr' => [
                'accept' => 'image/*',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'symf_filrouge_v2' => Announcement::class,
        ]);
    }
}
