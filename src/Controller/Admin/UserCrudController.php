<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
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
        if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
            $ageGroupField = ArrayField::new('ageGroups', 'Speltak');
        } else {
            $ageGroupField = AssociationField::new('ageGroups', 'Speltak');
        }

        return [
            AvatarField::new('name')->setIsGravatarEmail(),
            TextField::new('name', 'Naam')->setCssClass('js-row-action'),
            EmailField::new('email', 'E-mail'),
            ChoiceField::new('roles', 'Rechten')
                ->setChoices(['Gebruiker' => User::ROLE_USER, 'Materiaalmeester' => User::ROLE_MATERIAL_MASTER, 'Admin' => User::ROLE_ADMIN])
                ->allowMultipleChoices()
                ->setPermission(User::ROLE_ADMIN),
            $ageGroupField
                ->setHelp('Dit heeft invloed op welke reserveringen deze gebruiker kan zien en bewerken.'),
            DateTimeField::new('createdAt', 'Aangemaakt')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Aangepast')->hideOnForm(),
            DateTimeField::new('deletedAt', 'Verwijderd')->hideWhenCreating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('ageGroups')->setLabel('Speltakken'))
            ->add(NullFilter::new('deletedAt')->setLabel('Verwijderd')->setChoiceLabels('Nee', 'Ja'));
    }
}
