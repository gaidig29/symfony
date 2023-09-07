<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('overview', TextareaType::class, ['required' => false])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    "Canceled" => "canceled",
                    "Ended" => "ended",
                    "Returning" => "returning"
                ],
                "expanded" => false,
                "multiple" => false

            ])
            ->add('vote')
            ->add('popularity')
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    "SF" => "SF",
                    "Comedy" => "Comedy",
                    "Thriller" => "Thriller",
                    "Western" => "Western"
                ],
                "expanded" => true
            ])
            ->add('firstAirDate', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('lastAirDate')
            ->add('backdrop')
            ->add('poster',FileType::class,[
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new Image([
                        'maxSize'=>'2m',
                        'mimeTypesMessage'=>'A picture please !'
                    ])
                ]
            ])
            ->add('tmdbId')
            ->add('dateCreated')
            ->add('dateModified');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
