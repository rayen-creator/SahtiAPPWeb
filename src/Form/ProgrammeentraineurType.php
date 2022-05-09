<?php

namespace App\Form;

use App\Entity\Programmeentraineur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeentraineurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idexercice',null,[
                'label' => 'description'

            ])
            ->add('nompack')
            ->add('type', ChoiceType::class,
            [
                'choices'  => [

                    'Fitness' => 'Fitness',
                    'Cardio' => 'Cardio',
                    'Strength ' => 'Strength',

                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programmeentraineur::class,
        ]);
    }
}
