<?php
/**
 * Created by PhpStorm.
 * User: M.D
 * Date: 21/03/2019
 * Time: 10:40
 */

namespace FMDD\SyliusMarketingPlugin\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ChannelTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contactPhone', TextType::class, array('empty_data' => ''))
            ->add('contactOpeningTime', TextType::class, array('empty_data' => ''))
            ->add('contactGeoLatitudeLongitude', TextType::class, array('empty_data' => ''))
            ->add('contactGeoOpeningHours', TextType::class, array('empty_data' => ''))
            ->add('urlPrivacy', TextType::class, array('empty_data' => ''))
            ->add('facebookPixel', TextType::class, array('empty_data' => ''))
            ->add('googleAnalytics', TextType::class, array('empty_data' => ''))
            ->add('googleAdwords', TextType::class, array('empty_data' => ''))
            ->add('googleId', TextType::class, array('empty_data' => ''))
            ->add('googleTypeMerchant', TextType::class, array('empty_data' => ''))
            ->add('googleEventPurchase', TextType::class, array('empty_data' => ''))
            ->add('googleEventSearch', TextType::class, array('empty_data' => ''))
            ->add('googleEventProductShow', TextType::class, array('empty_data' => ''))
            ->add('googleEventRegistration', TextType::class, array('empty_data' => ''))
            ->add('googleEventCheckoutProgress', TextType::class, array('empty_data' => ''))
            ->add('googleEventSelectPayment', TextType::class, array('empty_data' => ''))
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChannelType::class];
    }
}
