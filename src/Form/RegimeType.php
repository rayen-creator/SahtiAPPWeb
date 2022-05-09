<?php

namespace App\Form;

use App\Entity\Regime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objectif', ChoiceType::class, [
                    'choices' => [
                        'Gagner du poids' => 'Gagner du poids',
                        'Perdre du poids' => 'Perdre du poids',
                        'Maintenir le poids' =>'Maintenir le poids'],
                    'expanded'=>true
                ]
            )
            ->add('dateDebut' ,DateType::class, [
                'widget' => "single_text",


            ])
            ->add('duree')
            ->add('maxCalories')
            ->add('nbRepas')
            ->add('idSpecialiste')
            ->add('image',FileType::class,[
                'required'=>false,'mapped'=>false,
            ])
            ->add('idRepas')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regime::class,
        ]);
    }
}
