<?php

namespace App\Controller\Admin;

use App\Entity\Note;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Note::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Notitie')
            ->setEntityLabelInPlural('Notities');
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
