<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Reservatie')
            ->setEntityLabelInPlural('Reservaties')
            ->setDefaultSort(['dateStart' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $field = ArrayField::new('users', 'Extra Gebruikers');
        } else {
            $field = AssociationField::new('users', 'Extra Gebruikers')->autocomplete();
        }

        return [
            TextField::new('name', 'Naam'),
            ChoiceField::new('ageGroup', 'Speltak')
                ->setChoices(array_combine(User::AGE_GROUPS, User::AGE_GROUPS)),
            $field->setSortable(false)->setHelp('De extra gebruikers van buiten de geselecteerde speltak.'),
            DateField::new('dateStart', 'Begin datum'),
            DateField::new('dateEnd', 'Eind datum'),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            AssociationField::new('loans', 'Aantal uitleningen')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name')->setLabel('Name'))
            ->add(ChoiceFilter::new('ageGroup')
                ->setChoices(array_combine(User::AGE_GROUPS, User::AGE_GROUPS))->setLabel('Speltak'))
            ->add(DateTimeFilter::new('dateStart')->setLabel('Begin datum'))
            ->add(DateTimeFilter::new('dateEnd')->setLabel('Eind datum'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'));
    }
}
