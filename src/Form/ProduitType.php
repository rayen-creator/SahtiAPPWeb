<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('image', FileType::class,array('data_class' => null))
            ->add('quantite')
            ->add('descprod', CKEditorType::class)
            ->add('id_cat',EntityType::class, [
                'empty_data' => ' ',
                'label' => 'categorie',
                'class' => Categorie::class,
                'choice_label' => 'titre',])
            ->add('save', SubmitType::class)

            ;


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
