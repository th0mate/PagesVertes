<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class EditMdpUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                "mapped" => false,
                "constraints" => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe.']),
                    new NotNull(['message' => 'Veuillez saisir un mot de passe.']),
                    new Length(['min' => 8, 'max' => 30, 'minMessage' => 'Le mot de passe doit contenir entre 8 et 30 caractères.',
                        'maxMessage' => 'Le mot de passe doit contenir entre 8 et 30 caractères.']),
                    new regex([
                        'pattern' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#',
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et un chiffre.'
                    ]),
                ],
                'attr' => [
                    'minlength' => 8,
                    'maxlength' => 30,
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$',

                ]])
            ->add('newPassword', PasswordType::class, [
                "mapped" => false,
                "constraints" => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe.']),
                    new NotNull(['message' => 'Veuillez saisir un mot de passe.']),
                    new Length(['min' => 8, 'max' => 30, 'minMessage' => 'Le mot de passe doit contenir entre 8 et 30 caractères.',
                        'maxMessage' => 'Le mot de passe doit contenir entre 8 et 30 caractères.']),
                    new regex([
                        'pattern' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#',
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule et un chiffre.'
                    ]),
                ],
                'attr' => [
                    'minlength' => 8,
                    'maxlength' => 30,
                    'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$',

                ]])
            ->add('enregistrer', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
