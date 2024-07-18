<?php

namespace App\Back\Form;

use App\Back\Entity\Categorie;
use App\Back\Entity\Image;
use App\Back\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'required' => $options['imageRequired'],
                'empty_data' => $options['dataImage']
            ])
            ->add('principal')
            ->add('produit', EntityType::class, [
                'mapped' => true,
                'class' => Produit::class,
                'required' => false,
                'attr' => array ('onchange' => 'imageProduit()'),
            ])
            ->add('categorie', EntityType::class, [
                'mapped' => true,
                'class' => Categorie::class,
                'required' => false,
                'attr' => array ('onchange' => 'imageCategorie()'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'imageRequired' => true,
            'dataImage' => null
        ]);
    }
}
