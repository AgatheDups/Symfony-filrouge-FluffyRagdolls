<?php

namespace App\Controller\Admin;

use App\Entity\Announcement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnnouncementCrudController extends AbstractCrudController
{
    use Trait\ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Announcement::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('creation_date'),
            TextEditorField::new('description'),
            TextField::new('cat_name'),
            DateField::new('cat_birth'),
            BooleanField::new('cat_loof')->setDisabled(),
            AssociationField::new('cat_gender')
            ->setLabel('Cat gender')
                ->formatValue(function ($value, $entity) {
                if ($entity->getCatGender()) {
                    return $entity->getCatGender()->getId() === 1 ? 'Male' : 'Female';
                }
                return 'N/A';
            }),
            AssociationField::new('user')
                ->setLabel('User ID')
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser() ? $entity->getUser()->getId() : 'N/A';
                }),
        ];
    }
}
