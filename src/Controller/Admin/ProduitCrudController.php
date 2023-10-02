<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $photoField = ImageField::new('photo')
        ->setBasePath('uploads/images')
        ->setUploadDir('public/uploads/images')
        ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]');
        // ->setFormTypeOptions([
        //     'required' => false, // Ne pas rendre le champ requis
        //     'constraints' => [
        //         new File([
        //             'maxSize' => '1024k',
        //             'mimeTypes' => ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/webp'],
        //             'mimeTypesMessage' => 'Veuillez télécharger une image au format jpg, jpeg, png ou webp',
        //         ]),
        //     ],
        // ]);
                
            

        if ($pageName === Crud::PAGE_NEW) {
            $photoField = $photoField->setFormTypeOptions(['required' => true]);
        } else {
            $photoField = $photoField->setFormTypeOptions(['required' => false]);
        }

        $prixField= MoneyField::new('prix')
        ->setCurrency('EUR')
        ->setNumDecimals(2)
        ->setFormTypeOptions([
        'constraints' => [
            new GreaterThanOrEqual(['value' => 0, 'message' => 'Le prix ne peut pas être négatif ou zéro.'])
          ],
        ]);
    // ->setTemplatePath('admin/field/prix.html.twig')
    // ->setCustomOption('multiply_by_100', true);

    $stockField = IntegerField::new('stock')
    ->setFormTypeOptions([
        'constraints' => [
            new GreaterThanOrEqual(['value' => 1, 'message' => 'Le stock doit être supérieur ou égal à 1.'])
         ],
        ]);

        $fields = [
            IdField::new('id')->hideOnForm(),
            // AssociationField::new('membre')->renderAsNativeWidget(),
            TextField::new('titre'),
            TextField::new('auteur'),
            TextField::new('editeur'),
            TextEditorField::new('description'),
            DateTimeField::new('date_enregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
            ChoiceField::new('categorie')->setChoices(['Kodomo'=>'kodomo', "Shonen"=>"shonen", 'Shojo'=>'shojo',"Seinen" => "seinen", 'Josei'=>'josei']),
            $photoField,
            $prixField,
            $stockField,
        ];
        return $fields;
    }

    public function createEntity(string $entityFqcn)
    {
        $produit = new $entityFqcn;
        $produit->setDateEnregistrement(new \DateTime());
        return $produit;
    }
}
