<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Auteur',
                'required' => true,
            ])
            ->add('editeur', TextType::class, [
                'label' => 'Editeur',
                'required' => true,
            ])
            
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'required' => true,
                'choices' => [
                    'Kodomo' => 'kodomo',
                    'Shonen' => 'shonen',
                    'Shojo' => 'shojo',
                    'Seinen' => 'seinen',
                    'Josei' => 'josei',
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez mettre une image au format JPG, JPEG, PNG ou WebP.',
                    ]),
                ],
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix',
                'required' => true,
                'scale' => 2, // Définissez le nombre de décimales à 2
                'attr' => [
                    'step' => '0.01', // Étape de 1 centime
                ],
                'constraints' => [
                    new GreaterThan(['value' => 0, 'message' => 'Le prix doit être supérieur à 0.'])
                ],
            ])
            
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
                'required' => true,
                'constraints' => [
                    new GreaterThan(['value' => 0, 'message' => 'Le stock doit être supérieur à 0.'])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }


    
}
        
    

