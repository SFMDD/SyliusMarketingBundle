<?php

namespace FMDD\SyliusMarketingPlugin\Form;

use FMDD\SyliusMarketingPlugin\Entity\InstagramPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstagramPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author')
            ->add('content')
            ->add('comments')
            ->add('likes')
            ->add('link')
            ->add('imageSmall')
            ->add('imageMedium')
            ->add('imageBig')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InstagramPost::class,
        ]);
    }
}
