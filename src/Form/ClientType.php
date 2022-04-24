<?php

namespace App\Form;

use App\Entity\Client;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use function MongoDB\BSON\toJSON;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'LastName')))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'FirstName')))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Email')))
            ->add('passwd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Password')))
            ->add('confirmpwd', PasswordType::class,
                ['mapped'=>false ,
                'attr' => ['placeholder' => 'Confirm Password']])
            ->add('adresse', TextType::class,array(
                'attr' => array(
                    'placeholder' => 'Address')))
//            ->add('datenaiss', DateTimeType::class , [
//                'widget' => 'single_text',
//
//            ])
//            ->add('datenaiss', null, array(
//                'attr' => array(
//
//                'widget' => 'single_text',
//            )))
            ->add('captchaCode', CaptchaType::class, array(
                    'captchaConfig' => 'ExampleCaptcha'
                ))
            ->add('date', DateType::class, ['mapped'=>false, 'widget' => 'single_text',] )
            ->add('imgFile',FileType::class,['mapped'=>false,'required' => false])
            ->add('Registre', SubmitType::class)
            ->add('Cancel', ResetType::class)




//            ->add('isblocked')
//            ->add('idCoach')
//            ->add('idNutri')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
