<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Sodium\add;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['adresse'] == null){
            $builder
                ->add('adresse', DeliveryFormType::class, []);
        }
        $builder
            ->add('cb_numero', NumberType::class, [
                'required' => true,
                'mapped' => false,
                'html5' => true,
            ])
            ->add('cb_date', DateType::class, [
                'required' => true,
                'mapped' => false,
            ])
            ->add('cb_code', NumberType::class, [
                'required' => true,
                'mapped' => false,
                'html5' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null,
            'adresse' => null,
        ]);
    }
}