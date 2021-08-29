<?php

namespace App\Controller\Admin;

use App\Entity\AgeGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;

class AgeGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AgeGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Speltak')
            ->setEntityLabelInPlural('Speltakken')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam'),
            ColorField::new('color', 'Kleur'),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            AssociationField::new('users', 'Aantal gebruikers')->hideOnForm(),
            AssociationField::new('reservations', 'Aantal reserveringen')->hideOnForm(),
            DateTimeField::new('deletedAt', 'Verwijderd')->hideWhenCreating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }
}
