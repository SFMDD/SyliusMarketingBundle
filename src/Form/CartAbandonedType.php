<?php

namespace FMDD\SyliusMarketingPlugin\Form;

use FMDD\SyliusMarketingPlugin\Entity\CartAbandoned;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartAbandonedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
                'required' => false
            ])
            ->add('subject', TextType::class, [
                'label' => 'sylius.ui.subject',
                'required' => true
            ])
            ->add('template', ChoiceType::class, [
                'label' => 'sylius.ui.email_templates',
                'required' => true,
                'choices' => [
                    'emails.no_checkout.name' => 'no_checkout',
                    'emails.no_payment.name' => 'no_payment',
                ]
            ])
            ->add('sendDelay', IntegerType::class, [
                'label' => 'fmdd_sylius_marketing.form.cart_abandoned.delay',
                'required' => true
            ])
            //FIXME: Unused data
            ->add('targetActive')
            ->add('targetInactive')
            ->add('targetWithoutOrder')
            ->add('discountType')
            ->add('discountAmount')
            ->add('discountValidity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CartAbandoned::class,
        ]);
    }
}
