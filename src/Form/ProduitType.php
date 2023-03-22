<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;



use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit',TextType::class,[
                'label'=>'Nom Produit',
                'attr'=>['placeholder'=>'Nom ']
            ])
            ->add('description',TextType::class,[
                'label'=>'Description',
                'attr'=>['placeholder'=>'Description ']
            ])
        ->add('categorie', EntityType::Class,array(
            'class' => Categorie::class,
            'choice_label'=>'nomC',
            'label' =>'Selection des categories',
           'expanded'=>'true'
        ))

            ->add('quantite',TextType::class,[
                'label'=>'Quantité stock',
                'attr'=>['placeholder'=>'nb ']
            ])
            ->add('prixProduit',
        IntegerType::class,[
                'label'=>'Prix',
                'attr'=>['placeholder'=>'prix ']
            ])
            ->add('promotion',TextType::class,[
                'label'=>'Promotion',
                'attr'=>['placeholder'=>'promo ']
            ])
            ->add('image',FileType::class,array('data_class' => null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
