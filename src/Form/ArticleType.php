<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'2','maxlength'=>'50']
            ,'label'=>'Titre :','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\Length(['min'=>5,'max'=>100]), new Assert\NotBlank(['message' => ''])]])
            ->add('content',TextareaType::class,['attr'=>['form-control form-control-sm', 'minlength'=>'2','maxlength'=>'50']
            ,'label'=>'Edito :','label_attr' => ['class'=>'col-form-label col-form-label-sm mt-1'],
            'constraints'=>[new Assert\NotBlank(message:'')]])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'btn btn-sm btn-primary mt-4 float-end'],'label'=>'Valider'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
