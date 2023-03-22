<?php

namespace App\Form;

use App\Entity\Personnel;
use App\Entity\Salle;
use App\Entity\Sanction;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

class PersonnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id',NumberType::class)
            ->add('nomP',TextType::class)
            ->add('prenomP',TextType::class)
            ->add('telP',TelType::class)
            ->add('emailP',EmailType::class)
            ->add('mdp',PasswordType::class)
            ->add('salaireP',MoneyType::class)
            ->add('poste',ChoiceType::class, [
                'choices'  => [
                    'Choix' => null,
                    'Coach' => 'Coach',
                    'Ouvrier' => 'Ouvrier',
                    'Nutritionniste ' => 'Nutritionniste ',
                    'Gerant ' => 'Gerant ',

                ],
            ])
            ->add('hTravail',NumberType::class,)
            ->add('hAbsence',NumberType::class)

            ->add('dateEmbauche',DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('salle',EntityType::class,
                ['class'=>Salle::class,
                    'choice_label'=>'nomS',
                    'multiple'=>false,
                ]
            )

            ->add('save',SubmitType::class)
            ->add('reset',ResetType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnel::class,
        ]);
    }
}
