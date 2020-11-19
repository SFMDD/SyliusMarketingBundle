<?php

namespace FMDD\SyliusMarketingPlugin\Form;

use App\Repository\ConnectorTypeRepository;
use Doctrine\ORM\EntityRepository;
use FMDD\SyliusMarketingPlugin\Entity\Notification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                "label" => "fmdd_sylius_marketing.ui.notification_types",
                "required" => true,
                'class' => 'FMDD\SyliusMarketingPlugin\Entity\NotificationType',
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'query_builder' => function (EntityRepository $pr) use ($options){
                    return $pr->createQueryBuilder('p')->orderBy('p.id', 'ASC');
                }
            ])
            ->add('options')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
