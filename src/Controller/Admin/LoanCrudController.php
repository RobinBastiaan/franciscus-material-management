<?php

namespace App\Controller\Admin;

use App\Entity\Loan;
use App\Entity\Material;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class LoanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Loan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Uitlening')
            ->setEntityLabelInPlural('Uitleningen')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('reservation', 'Reservatie'),
            AssociationField::new('loanedMaterial', 'Materiaal'),
            ChoiceField::new('returnedState', 'Staat bij inleveren')
                ->setChoices(array_combine(Material::STATES, Material::STATES))
                ->setHelp('Laat dit veld leeg wanneer deze nog niet is teruggelegd na uitlening.')
                ->hideWhenCreating(),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('reservation')->setLabel('Reservatie'))
            ->add(EntityFilter::new('loanedMaterial')->setLabel('Materiaal'))
            ->add(ChoiceFilter::new('returnedState')
                ->setChoices(array_merge(['Nog niet ingeleverd' => null], array_combine(Material::STATES, Material::STATES)))->setLabel('Staat'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'));
    }
}
