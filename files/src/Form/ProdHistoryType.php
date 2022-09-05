<?php

namespace App\Form;

use App\Entity\ProdHistory;
use App\Entity\Product;
use App\Entity\ProdSteps;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProdHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('timestamp', DateTimeType::class, array(
                'disabled' => 'true'
            ))
            ->add('prodId',EntityType::class,[
                'class'=>Product::class,
                'choice_label'=>'prodid',
            ])
            ->add('prodSerial')
            ->add('stepid',EntityType::class,[
                'class'=>ProdSteps::class,
                'choice_label'=>'descp',
            ])
            ->add('operatorid')
            ->add('stepcomments')
            ->add('stepstatus');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProdHistory::class,
        ]);
    }
}
