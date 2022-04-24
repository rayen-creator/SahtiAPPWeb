<?php

namespace App\Form;

use App\Entity\Entraineur;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
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

class EntraineurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom' ,TextType::class, array(
                'attr' => array(
                    'placeholder' => 'FirstName')))
            ->add('prenom',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'LastName')))
            ->add('email',EmailType::class ,array(
                'attr' => array(
                    'placeholder' => 'Email')))
            ->add('passwd', PasswordType::class,array(
                'attr' => array(
                    'placeholder' => 'Password')))
            ->add('confirmpwd', PasswordType::class,
                ['mapped'=>false ,
                    'attr' => ['placeholder' => 'Confirm Password']])
            ->add('adresse', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Address')))
            ->add('bio', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Bio')))
            ->add('certification', TextareaType::class , array(
                'attr' => array(
                    'placeholder' => 'Certification')))
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
            ))
            ->add('imgFile',FileType::class,['mapped'=>false,'required' => false])
            ->add('Registre', SubmitType::class)
            ->add('Cancel', ResetType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entraineur::class,
        ]);
    }
}
