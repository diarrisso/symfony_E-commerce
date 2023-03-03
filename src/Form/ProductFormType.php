<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Nom'
            ])
            ->add('description', options: [
                'label'=>'desription'
    ])
            ->add('price', options: [
                'label' => 'prix'
            ])
            ->add('stock', options: [
                'label'=> 'quantite en stock'
            ])
           ->add('categories', EntityType::class, [
               'class' => Categories::class,
               'choice_label'=>'name',
               'label'=> 'categories',
               'group_by'=> 'parent.name',
               'query_builder'=> function ( CategoriesRepository $category)
               {
                 return $category->createQueryBuilder('c')
                     ->where('c.parent IS NOT NULL')
                     ->orderBy('c.name', 'ASC');
               }
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
