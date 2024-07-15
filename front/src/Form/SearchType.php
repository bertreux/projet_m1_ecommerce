<?php

namespace App\Front\Form;

use App\Front\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['options'] == null){
            $builder
                ->add('findTextBy', ChoiceType::class,[
                    'choices' => [
                        'correspondance exacte : ' => 1,
                        'un caractère de différent : ' => 2,
                        'débute par : ' => 3,
                        'contient la recherche : ' => 4,
                    ]
                ])
                ->add('description', TextType::class,[
                    'required' => false,
                ])
                ->add('findTextBy2', ChoiceType::class,[
                    'choices' => [
                        'correspondance exacte : ' => 1,
                        'un caractère de différent : ' => 2,
                        'débute par : ' => 3,
                        'contient la recherche : ' => 4,
                    ]
                ])
                ->add('titre', TextType::class,[
                    'required' => false,
                ]);
            if($options['isCategory'] == true){
                $builder
                    ->add('category', EntityType::class,[
                        'class' => Categorie::class,
                        'required' => false,
                    ]);
            }
            $builder
                ->add('prix_min', NumberType::class,[
                    'required' => false,
                ])
                ->add('prix_max', NumberType::class,[
                    'required' => false,
                ])
                ->add('stock', CheckboxType::class,[
                    'required' => false,
                ])
                ->add('trie', ChoiceType::class, [
                    'required' => false,
                    'choices' => [
                        'Prix' => 'produit.prix',
                        'Nouveauté' => 'produit.arriver',
                        'Stock' => 'produit.stock',
                    ]
                ])
                ->add('trieSens', ChoiceType::class, [
                    'choices' => [
                        'Ascendant' => 'ASC',
                        'Descendant' => 'DESC',
                    ]
                ])
            ;
        }else{
            $builder
                ->add('findTextBy', ChoiceType::class,[
                    'choices' => [
                        'correspondance exacte : ' => 1,
                        'un caractère de différent : ' => 2,
                        'débute par : ' => 3,
                        'contient la recherche : ' => 4,
                    ],
                    'data' => $options['options']['findTextBy'],
                ])
                ->add('description', TextType::class,[
                    'required' => false,
                    'data' => $options['options']['description'],
                ])
                ->add('findTextBy2', ChoiceType::class,[
                    'choices' => [
                        'correspondance exacte : ' => 1,
                        'un caractère de différent : ' => 2,
                        'débute par : ' => 3,
                        'contient la recherche : ' => 4,
                    ],
                    'data' => $options['options']['findTextBy2'],
                ])
                ->add('titre', TextType::class,[
                    'required' => false,
                    'data' => $options['options']['titre'],
                ]);
            if($options['isCategory'] == true){
                    $builder
                        ->add('category', EntityType::class,[
                            'class' => Categorie::class,
                            'required' => false,
                            'data' => $options['categori'],
                        ]);
            }
            $builder
                ->add('prix_min', NumberType::class,[
                    'required' => false,
                    'data' => $options['options']['prix_min'],
                ])
                ->add('prix_max', NumberType::class,[
                    'required' => false,
                    'data' => $options['options']['prix_max'],
                ])
                ->add('stock', CheckboxType::class,[
                    'required' => false,
                    'data' => $options['options']['stock'],
                ])
                ->add('trie', ChoiceType::class, [
                    'required' => false,
                    'choices' => [
                        'Prix' => 'produit.prix',
                        'Nouveauté' => 'produit.arriver',
                        'Stock' => 'produit.stock',
                    ],
                    'data' => $options['options']['trie'],
                ])
                ->add('trieSens', ChoiceType::class, [
                    'choices' => [
                        'Ascendant' => 'ASC',
                        'Descendant' => 'DESC',
                    ],
                    'data' => $options['options']['trieSens'],
                ])
            ;
        }
        $builder
            ->add('submitSearch', SubmitType::class, [
                'label' => 'Chercher',
            ])
            ->add('ReniSearch', SubmitType::class, [
                'label' => 'Rénitialiser',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'isCategory' => false,
            'options' => null,
            'categori' => null
        ]);
    }
}
