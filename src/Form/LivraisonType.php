<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etatLivraison', ChoiceType::class, [
                'choices'  => [
                    '' => null,
                    'Livrer' => true,
                    'Non livrer' => false,
                ],
            ])                       
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'email',
                'multiple' => false,
                'expanded' =>false
                ],
            )            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
