<?php

namespace App\Form;

use App\Entity\Reclamations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReclamationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder            
            ->add('titre',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Titre de réclamation')))
            ->add('file')
            ->add('message',TextareaType ::class, array(
                'attr' => array(
                    'placeholder' => 'Ecrire votre réclamation')))
            ->add('type',ChoiceType::class, [
                'choices'  => [
                    'Systeme' => "Systeme",
                    'Coach' => "Coach",
                    'Nutritionniste' => "Nutritionniste",
                    'Autre' => "Autre"
                ],
            ])
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamations::class,
        ]);
    }
}
