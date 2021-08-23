<?php

namespace App\Controller\Admin;

use App\Entity\Material;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class MaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Material::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Materiaal')
            ->setEntityLabelInPlural('Materialen')
            ->setDefaultSort(['name' => 'ASC'])
            ->overrideTemplate('crud/detail', 'bundles/EasyAdminBundle/crud/detail-material.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $field = ArrayField::new('tags');
        } else {
            $field = AssociationField::new('tags')->autocomplete();
        }

        return [
            NumberField::new('amount', 'Aantal'),
            TextField::new('name', 'Naam'),
            TextField::new('type'),
            TextareaField::new('description', 'Korte omschrijving'),
            TextEditorField::new('information', 'Uitgebreide informatie'),
            ChoiceField::new('state', 'Staat')->setChoices(array_combine(Material::STATES, Material::STATES)),
            DateField::new('dateBought', 'Koopdatum'),
            MoneyField::new('value', 'Aankoopwaarde')->setCurrency('EUR')->setStoredAsCents(false),
            MoneyField::new('currentValue', 'Huidige waarde')->setCurrency('EUR')->setStoredAsCents(false),
            TextField::new('manufacturer', 'Fabrikant'),
            NumberField::new('depreciationYears', 'Afschrijvingsjaren')->setHelp('Laat dit veld leeg wanneer dit materiaal niet vervangen hoeft te worden.'),
            TextField::new('location', 'Locatie'),
            $field->setSortable(false),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            ArrayField::new('notes', 'Notities')->onlyOnDetail(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name')->setLabel('Naam'))
            ->add(TextFilter::new('type'))
            ->add(ChoiceFilter::new('state')
                ->setChoices(array_combine(Material::STATES, Material::STATES))->setLabel('Staat'))
            ->add(TextFilter::new('location')->setLabel('Locatie'))
            ->add(NumericFilter::new('value')->setLabel('Aankoopwaarde'))
            ->add(EntityFilter::new('tags'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'));
    }
}
