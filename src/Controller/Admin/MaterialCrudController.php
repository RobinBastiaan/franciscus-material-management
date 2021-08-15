<?php

namespace App\Controller\Admin;

use App\Entity\Material;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            ->setDefaultSort(['name' => 'ASC']);
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
