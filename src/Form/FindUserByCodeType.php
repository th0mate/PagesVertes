<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class FindUserByCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Fz9oE7',
                    'minlength' => 6,
                    'maxlength' => 6,
                    'pattern' => '^[a-zA-Z0-9]+$',
                    'class' => 'recherche'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => 'Le code ne doit contenir que des caractères alphanumériques.'
                    ])
                ]])
            ->add('rechercher', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'submit boutonRecherche',
                    'style' => 'boutonRecherche'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => Utilisateur::class,
            'data_class' => null,
        ]);
    }
}
