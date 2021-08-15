<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('type')
            ->add('amount')
            ->add('state')
            ->add('dateBought')
            ->add('value')
            ->add('depreciationYears')
            ->add('manufacturer')
            ->add('location')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('tags')
            ->add('createdBy')
            ->add('updatedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}
