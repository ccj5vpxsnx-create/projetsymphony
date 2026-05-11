<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'votre@email.com']
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Minimum 6 caractères'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire']),
                    new Length(['min' => 6, 'minMessage' => 'Minimum 6 caractères']),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Je suis',
                'choices' => [
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Etudiant'   => 'ROLE_ETUDIANT',
                ],
            ])
            ->add('nom', TextType::class, [
                'mapped' => false,
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'mapped' => false,
                'label' => 'Prénom',
            ])
            ->add('identifiant', TextType::class, [
                'mapped' => false,
                'label' => 'Matricule (Enseignant) / NCE (Etudiant)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => \App\Entity\User::class,
        ]);
    }
}