<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Coach;
use App\Entity\Salle;
use App\Entity\Seance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Image du cours',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/jpeg,image/png,image/webp',
                    // La classe est maintenant gérée dans le template Twig
                ],


                'constraints' => [
                    new Assert\Image(
                        maxSize: '2M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Formats acceptés : JPEG, PNG ou WebP',
                        maxSizeMessage: 'L\'image ne doit pas dépasser {{ limit }} {{ suffix }}',
                    ),
                ],
            ])
            ->add('name', TextType::class, [

                'label' => 'Nom du cours',
                'attr' => [
                    'placeholder' => 'Ex: Boxe Débutant',
                    'class' => 'w-full bg-white text-gray-900 placeholder-gray-500 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le nom du cours est obligatoire'),
                    new Assert\Length(
                        min: 3,
                        max: 255,
                        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
                        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères',
                    ),
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionner une catégorie',
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'La catégorie est obligatoire'),
                ]
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Niveau',
                'choices' => [
                    'Sélectionner un niveau' => '',
                    'Débutant' => 'Débutant',
                    'Intermédiaire' => 'Intermédiaire',
                    'Avancé' => 'Avancé',
                    'Expert' => 'Expert'
                ],
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Le niveau est obligatoire'),
                ]
            ])
            ->add('coach', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'name',
                'label' => 'Nom du coach',
                'placeholder' => 'Ex: Gautam B',
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'Le coach est obligatoire'),
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Jour',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'La date est obligatoire'),
                    new Assert\GreaterThanOrEqual(
                        value: 'today',
                        message: 'La date ne peut pas être dans le passé',
                    ),
                ]
            ])
            ->add('start_time', TimeType::class, [
                'label' => 'Heure de début',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'L\'heure de début est obligatoire'),
                ]
            ])
            ->add('end_time', TimeType::class, [
                'label' => 'Heure de fin',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'L\'heure de fin est obligatoire'),
                ]
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'name',
                'label' => 'Lieu',
                'placeholder' => 'Ex: Studio A Salle 69',
                'attr' => [
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'Le lieu est obligatoire'),
                ]
            ])
            ->add('capacity_max', IntegerType::class, [
                'label' => 'Places maximum',
                'attr' => [
                    'placeholder' => '0',
                    'min' => 1,
                    'max' => 999,
                    'class' => 'w-full bg-white text-gray-900 px-4 py-3 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-[#dfbe6b]'
                ],
                'constraints' => [
                    new Assert\NotNull(message: 'Le nombre de places est obligatoire'),
                    new Assert\Positive(message: 'Le nombre de places doit être positif'),
                    new Assert\LessThanOrEqual(
                        value: 999,
                        message: 'Le nombre de places ne peut pas dépasser {{ compared_value }}',
                    ),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
