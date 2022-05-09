<?php

namespace App\Form;

use App\Entity\Commandes;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('numCmd')
            ->add('montantCmd')
            ->add('commentaire')
            ->add('etat')
            ->add('qtecmd')
            ->add('modePay')
            ->add('dateCommande')
            ->add('dateModif')
            //->add('produit')*/
            ->add('montantCmd', TextType::class, array(
                'label' => 'Total TTC : '))
           // ->add('montantCmd', TextType::class, array(
                //'label' => 'Total Tva : '))
            //->add('totttc', TextType::class, array(
               // 'label' => 'Total TTC :'))
            //->add('produit')
            ->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
