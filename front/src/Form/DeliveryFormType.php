<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['dataAdresse'] == null){
            $builder
                ->add('intitule')
                ->add('ville')
                ->add('region')
                ->add('code_postal')
                ->add('pays')
            ;
        }else{
            $builder
                ->add('intitule', TextType::class, [
                    'data' => $options['dataAdresse']->getIntitule()
                ])
                ->add('ville', TextType::class, [
                    'data' => $options['dataAdresse']->getIntitule()
                ])
                ->add('region', TextType::class, [
                    'data' => $options['dataAdresse']->getIntitule()
                ])
                ->add('code_postal', TextType::class, [
                    'data' => $options['dataAdresse']->getIntitule()
                ])
                ->add('pays', TextType::class, [
                    'data' => $options['dataAdresse']->getIntitule()
                ])
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
            'dataAdresse' => null,
            'user' => null,
        ]);
    }
}
