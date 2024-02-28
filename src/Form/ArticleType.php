<?php

namespace App\Form;

use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['attr'=>['class'=>'form-control form-control-sm']
            ,'label'=>'Titre :','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1 text-danger-emphasis'],
            'constraints'=>[new Assert\Length(['min'=>5,'max'=>100]), new Assert\NotBlank(['message' => ''])]])
            ->add('content',CKEditorType::class,['attr'=>['class'=>'form-control form-control-sm'],
            'label'=>'Edito :','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-3 text-danger-emphasis']])
            ->add('photo',FileType::class,['attr'=>['class'=>'form-control form-control-sm mt-1'],
            'label'=>'Télécharger 3 photos','label_attr'=>['class'=>'col-form-label col-form-label-sm mt-4 text-danger-emphasis']
            ,'multiple'=>true,'mapped'=>false,'required'=>true])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-primary rounded-pill mt-4 float-end'],'label'=>'Valider'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
