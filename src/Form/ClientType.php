<?php

namespace App\Form;

use App\Entity\Client;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\AbstractType;
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
                    'placeholder' => 'Name')))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Prenom')))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Email')))
            ->add('passwd', PasswordType::class, array(
                'attr' => array(
                    'placeholder' => 'Password')))
            ->add('adresse', TextType::class,array(
                'attr' => array(
                    'placeholder' => 'Adresse')))
//            ->add('datenaiss', DateTime::class , [
//                'widget' => 'single_text',
//                'format' => 'MM-dd-yyyy',
//            ])
            ->add('datenaiss', TextType::class, array(
                'attr' => array(
                        'placeholder' => 'aaa-mm-jj',
            )))
            ->add('img',FileType::class,['mapped'=>false,'required' => false])
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
