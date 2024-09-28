<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class EditUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'mapped' => true,
                'attr' => [
                    'minlength' => 6,
                    'maxlength' => 6,
                    'pattern' => '^[a-zA-Z0-9]+$'
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => 'Le code ne doit contenir que des caractères alphanumériques.'
                    ])
                ]
            ])
            ->add('estVisible', CheckboxType::class, [
                'mapped' => true,
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'mapped' => true,
                'constraints' => [
                    new Length(['max' => 255, 'maxMessage' => 'Il faut au plus 255 caractères!']),
                ],
                'attr' => [
                    'maxlength' => 255
                ],
                'required' => false,
                'label' => 'Prenom'
            ])
            ->add('nom', TextType::class, [
                'mapped' => true,
                'constraints' => [
                    new Length(['max' => 255, 'maxMessage' => 'Il faut au plus 255 caractères!']),
                ],
                'attr' => [
                    'maxlength' => 255
                ],
                'required' => false,
                'label' => 'Nom'
            ])
            ->add('telephone', TextType::class, [
                'mapped' => true,
                'required' => false,
                'empty_data' => null,
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'exactMessage' => 'Il faut exactement 10 caractères pour un numéro de téléphone!',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
                    ])
                ],
                'attr' => [
                    'minlength' => 10,
                    'maxlength' => 10,
                ],
                'label' => 'Telephone'
            ])
            ->add('facebook', TextType::class, [
                'mapped' => true,
                'constraints' => [
                    new Length(['max' => 255, 'maxMessage' => 'Il faut au plus 255 caractères!'])
                ],
                'attr' => [
                    'maxlength' => 255
                ],
                'required' => false,
                'label' => 'Profil Facebook'
            ])
            ->add('adresseEmail', EmailType::class, [
                'attr' => [
                    'maxlength' => 255
                ]
            ])
            ->add('modifier', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}