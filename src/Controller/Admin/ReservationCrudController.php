<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;
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
            ->setEntityLabelInSingular('Reservering')
            ->setEntityLabelInPlural('Reservering')
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
            TextField::new('name', 'Naam')->setCssClass('js-row-action'),
            AssociationField::new('ageGroup', 'Speltak'),
            $field->setSortable(false)->setHelp('De extra gebruikers van buiten de geselecteerde speltak.'),
            DateField::new('dateStart', 'Begindatum'),
            DateField::new('dateEnd', 'Einddatum'),
            AssociationField::new('createdBy', 'Toegevoegd door')->hideOnForm(),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            AssociationField::new('loans', 'Aantal uitleningen')->hideOnForm(),
            CollectionField::new('nonReturnedLoans', 'Waarvan ingeleverd')->hideOnForm(),
            DateTimeField::new('deletedAt', 'Verwijderd')->hideWhenCreating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name')->setLabel('Name'))
            ->add(EntityFilter::new('ageGroup')->setLabel('Speltak'))
            ->add(DateTimeFilter::new('dateStart')->setLabel('Begindatum'))
            ->add(DateTimeFilter::new('dateEnd')->setLabel('Einddatum'))
            ->add(EntityFilter::new('createdBy')->setLabel('Toegevoegd door'))
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }
}
