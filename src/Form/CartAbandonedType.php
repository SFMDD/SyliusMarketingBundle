<?php

namespace FMDD\SyliusMarketingPlugin\Form;

use FMDD\SyliusMarketingPlugin\Entity\CartAbandoned;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartAbandonedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('sendDelay')
            ->add('template')
            ->add('targetActive')
            ->add('targetInactive')
            ->add('targetWithoutOrder')
            ->add('discountType')
            ->add('discountAmount')
            ->add('discountValidity')
            ->add('cartNotCheckout')
            ->add('cartNotPayed')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CartAbandoned::class,
        ]);
    }
}
