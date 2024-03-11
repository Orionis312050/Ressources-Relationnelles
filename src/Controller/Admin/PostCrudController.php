<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }


    public function configureFields(string $pageName): iterable
    {
        /*
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
        */
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Title'),
            //TextEditorField::new('description'), //je ne suis pas sur
            TextField::new('Content'),
            TextField::new('Description'),
            TextField::new('Type'),
            //TextField::new('Couleur'),
            //ColorField::new('Couleur'),
            //MoneyField::new('Prix')
                //->setCurrency('EUR'),
            ImageField::new('Img')
                ->setBasePath('images/')->setUploadDir('/public/images'),
            //BooleanField::new('Disponible'),

        ];
    }

}
