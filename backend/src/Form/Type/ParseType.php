<?php

namespace App\Form\Type;

use App\Model\ParseRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ParseType extends AbstractType
{
    public function getBlockPrefix()
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, ['required' => true])
            ->add('script', TextType::class, ['required' => false])
            ->add('clicks', CollectionType::class, [
                'entry_type'   => TextType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => false,
            ])
            ->add('items', CollectionType::class, [
                'entry_type'   => ParseRequestItemType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => false,
            ])
            ->add('debug', CheckboxType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', ParseRequest::class);
        $resolver->setDefault('csrf_protection', false);
    }


}