<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Abonnement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id',NumberType::class)
            ->add('nomA',TextType::class)
            ->add('prenomA',TextType::class)
            ->add('ageA',IntegerType::class)
            ->add('sexeA',ChoiceType::class,[
                'choices'  => [
                    'Genre' => null,
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],

            ])
            ->add('mdpA',PasswordType::class)
            ->add('emailA',EmailType::class)
            ->add('telA',TelType::class)
            ->add('adresseA',TextType::class)
            ->add('Abonnement',EntityType::class,
                ['class'=>Abonnement::class,
                    'choice_label'=>'typeA',
                    'multiple'=>false,
                ])
            ->add('message', TextareaType::class)
            ->add('save',SubmitType::class)
            ->add('reset',ResetType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
