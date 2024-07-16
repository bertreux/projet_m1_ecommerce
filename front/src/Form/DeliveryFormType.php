<?php

namespace App\Front\Form;

use App\Front\Entity\Adresse;
use App\Front\Entity\Utilisateur;
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
                    'data' => $options['dataAdresse']->getVille()
                ])
                ->add('region', TextType::class, [
                    'data' => $options['dataAdresse']->getRegion()
                ])
                ->add('code_postal', TextType::class, [
                    'data' => $options['dataAdresse']->getCodePostal()
                ])
                ->add('pays', TextType::class, [
                    'data' => $options['dataAdresse']->getPays()
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
