<?php

namespace App\Controller\Admin;

use App\Entity\ItemCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ItemCollectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ItemCollection::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            TextEditorField::new('description')->setTemplatePath('esy/text_editor.html.twig'),
            AssociationField::new('topic'),
            AssociationField::new('user'),
            ImageField::new('img')->setBasePath('images/collection')->setUploadDir('public/images/collection'),
        ];
    }

}
