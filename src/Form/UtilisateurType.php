<?php

namespace App\Form;

use App\Entity\Utilisateur;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, [
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 20
                ],
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 20,
                        'minMessage' => 'Il faut au moins 4 caractères!',
                        'maxMessage' => 'Il faut au plus 20 caractères!'
                    ]),
                    new NotBlank(['message' => 'Le login ne peut pas être vide']),
                    new NotNull(['message' => 'Le login ne peut pas être null'])
                ]])

            ->add('adresseEmail', EmailType::class, [
                'attr' => [
                    'maxlength' => 255
                ],
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse email ne peut pas être vide']),
                    new NotNull(['message' => 'L\'adresse email ne peut pas être null']),
                    new Email(['message' => 'L\'adresse mail n\'est pas valide'])
                ]
            ])

            ->add('code', TextType::class, [
                'attr' => [
                    'minlength' => 6,
                    'maxlength' => 6,
                    'pattern' => '^[a-zA-Z0-9]+$'
                ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 6,
                        'exactMessage' => 'Le code doit contenir exactement 6 caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => 'Le code ne doit contenir que des caractères alphanumériques.'
                    ]),
                    new NotBlank(['message' => 'Le code ne peut pas être vide']),
                    new NotNull(['message' => 'Le code ne peut pas être null'])
                ]])

            ->add('estVisible', CheckboxType::class, [
                'attr' => [
                    'checked' => 'checked'],
                'required' => false])

            ->add('plainPassword', PasswordType::class, [
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

            ->add('inscription', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
