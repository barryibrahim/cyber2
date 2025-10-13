<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, ['attr' => ['class'=> 'form-control'], 'label_attr' => ['class'=>
            'fw-bold']])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'fw-bold'],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'fw-bold'],
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est requis.']),
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'fw-bold'],
                'constraints' => [
                    new NotBlank(['message' => "L'adresse est requise."]),
                ],
            ])
            ->add('ville', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'fw-bold'],
                'constraints' => [
                    new NotBlank(['message' => 'La ville est requise.']),
                ],
            ])
            ->add('codepostal', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'fw-bold'],
                'constraints' => [
                    new NotBlank(['message' => 'Le code postal est requis.']),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                        'exactMessage' => 'Le code postal doit contenir exactement {{ limit }} chiffres.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'data' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class'=> 'form-control'],
                'label_attr' => ['class'=> 'fw-bold'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe.',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Le mot de passe doit contenir au moins 12 caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule.',
                    ]),
                    new Regex([
                        'pattern' => '/[a-z]/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre minuscule.',
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Le mot de passe doit contenir au moins un chiffre.',
                    ]),
                    new Regex([
                        'pattern' => '/[\W_]/',
                        'message' => 'Le mot de passe doit contenir au moins un caractère spécial.',
                    ]),
                    new NotCompromisedPassword(['message' => 'Ce mot de passe a déjà été compromis sur Internet, veuillez en choisir un autre.']),
                ],
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
