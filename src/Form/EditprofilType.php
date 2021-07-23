<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditprofilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', null, [
            'label' => 'Prenom'
        ])
        ->add('lastName', null, [
            'label' => 'Nom'
        ])
        ->add('address', null, [
            'label' => 'Adresse'
        ])
        ->add('tel', TelType::class, [
            'label' => 'Telephone'
        ])
        ->add('birthday', DateType::class, [
            'label' => 'Date de Naissance',
        ])
        ->add('sexe', ChoiceType::class, [
            'label' => 'Sexe',
            'choices' => [
                'masculin' => 'masculin',
                'feminin' => 'feminin'
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Bio'
        ])
        ->add('profilPhoto', FileType::class, [
            'label' => 'Uploadez un photo',
            'required' => false
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
