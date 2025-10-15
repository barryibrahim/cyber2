<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom', 'attr' => ['class' => 'form-control']])
            ->add('prenom', TextType::class, ['label' => 'Prénom', 'attr' => ['class' => 'form-control']])
            ->add('email', EmailType::class, ['label' => 'Email', 'attr' => ['class' => 'form-control']])

            // Champ mot de passe facultatif
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => '',
                    'class' => 'form-control'
                ],
            ])

            // Boutons radio pour les rôles
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Modérateur' => 'ROLE_MODERATEUR',
                ],
                'expanded' => true, // Affiche sous forme de boutons radio
                'multiple' => true, // Permet plusieurs rôles (Admin + User par ex)
            ])

            ->add('isActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
            ->add('ville', TextType::class, ['label' => 'Ville', 'attr' => ['class' => 'form-control']])
            ->add('adresse', TextareaType::class, ['label' => 'Adresse', 'attr' => ['class' => 'form-control']])
            ->add('codepostal', TextType::class, ['label' => 'Code postal', 'attr' => ['class' => 'form-control']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
