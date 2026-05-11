<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Soutenance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoutenanceType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('dateSoutenance')
        ->add('enseignant', EntityType::class, [
            'class' => Enseignant::class,
            'choice_label' => function(Enseignant $e) {
                return $e->getNom() . ' ' . $e->getPrenom();
            },
            'placeholder' => 'Choisir un enseignant',
        ])
        ->add('etudiant', EntityType::class, [
            'class' => Etudiant::class,
            'choice_label' => function(Etudiant $e) {
                return $e->getNom() . ' ' . $e->getPrenom();
            },
            'placeholder' => 'Choisir un étudiant',
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Soutenance::class,
        ]);
    }
}