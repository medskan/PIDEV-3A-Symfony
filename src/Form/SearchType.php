<?php

namespace App\Form;

use App\Entity\Categorie;
use app\entity\SearchData;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q',TextType::class,
            ['label'=>false,
                'required'=>false,
                'attr'=>['placeholder'=>'Rechercher']

            ])
            ->add('categories',EntityType::class,
                ['label'=>false,
                    'required'=>false,
                    'class'=>Categorie::class,
                     'expanded'=>true,
                    'multiple'=>true,

                ])
           ->add('max',IntegerType::class,
               ['label'=>false,
                   'required'=>false,
                   'attr'=>['placeholder'=>'Prix Maximal']

               ])
            ->add('min',IntegerType::class,
                ['label'=>false,
                    'required'=>false,
                    'attr'=>['placeholder'=>'Prix Minimal']

                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method'=>'GET',
            'csrf_protection' =>false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';  }
}
