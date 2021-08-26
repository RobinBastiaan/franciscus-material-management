<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NullFilter;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Gebruiker')
            ->setEntityLabelInPlural('Gebruikers')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam'),
            EmailField::new('email', 'E-mail'),
            ChoiceField::new('roles', 'Rechten')
                ->setChoices(['Gebruiker' => User::ROLE_USER, 'Materiaalmeester' => User::ROLE_MATERIAL_MASTER, 'Admin' => User::ROLE_ADMIN])
                ->allowMultipleChoices()
                ->setPermission(User::ROLE_ADMIN),
            ChoiceField::new('ageGroup', 'Speltak')
                ->setChoices(array_combine(User::AGE_GROUPS, User::AGE_GROUPS))
                ->allowMultipleChoices()
                ->setHelp('Dit heeft invloed op welke reserveringen deze gebruiker kan zien en bewerken.'),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            DateTimeField::new('deletedAt', 'Verwijderd'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('ageGroup')
                ->setChoices(array_combine(User::AGE_GROUPS, User::AGE_GROUPS))->setLabel('Speltak'))
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }
}
