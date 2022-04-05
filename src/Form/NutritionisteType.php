<?php

namespace App\Form;

use App\Entity\Nutritioniste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NutritionisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom' ,TextType::class, array(
                'attr' => array(
                    'placeholder' => 'nom')))
            ->add('prenom',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'prenom')))
            ->add('email',EmailType::class ,array(
                'attr' => array(
                    'placeholder' => 'email')))
            ->add('passwd', PasswordType::class,array(
                'attr' => array(
                    'placeholder' => 'passwd')))
            ->add('adresse', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'adresse')))
            ->add('bio', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'bio')))
            ->add('certification', TextareaType::class , array(
                'attr' => array(
                    'placeholder' => 'certification')))
            ->add('img',FileType::class,['mapped'=>false,'required' => false])
            ->add('Registre', SubmitType::class)
            ->add('Cancel', ResetType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nutritioniste::class,
        ]);
    }
}
