<?php

namespace App\Form;

use App\Entity\Progclient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgclientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progclient::class,
        ]);
    }
}
