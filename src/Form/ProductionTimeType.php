<?php

namespace App\Form;

use App\Entity\ProductionTime;
use App\Entity\Project;  
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [ 'class' => Project::class, 'choice_label' => 'name'
            ])
            ->add('time', IntegerType::class, [
                'label' => 'Temps'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductionTime::class,
        ]);
    }
}

