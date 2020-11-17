<?php


namespace FMDD\SyliusMarketingPlugin\EventListener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FMDD\SyliusMarketingPlugin\Entity\Notification;
use FMDD\SyliusMarketingPlugin\Entity\NotificationType;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\OrderInterface;

class NotificationOrderPayerListener
{
    /**
     * @var Registry
     */
    private Registry $doctrine;
    /**
     * @var CacheManager
     */
    private CacheManager $cacheManager;

    public function __construct(Registry $doctrine, CacheManager $cacheManager)
    {
        $this->doctrine = $doctrine;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param OrderInterface $order
     */
    public function process(OrderInterface $order)
    {
        $em = $this->doctrine->getManager();
        /** @var NotificationType $notificationType */
        $notificationType = $this->doctrine->getRepository(NotificationType::class)->findOneBy(['code' => 'purchase']);

        foreach($order->getItems() as $item) {
            $options = [
                'firstname' => $order->getCustomer()->getFirstName(),
                'product_name' => empty($item->getVariantName()) ? $item->getProductName() : $item->getVariantName(),
                'product_image' => empty($item->getProduct()->getImages()) ? '' :
                    $this->cacheManager->getBrowserPath(
                        $item->getProduct()->getImages()->first()->getPath(),
                        'sylius_shop_product_thumbnail'
                    ),
                'country' => $order->getShippingAddress()->getCountryCode(),
                'city' => $order->getShippingAddress()->getCity(),
            ];
            $notification = new Notification();
            $notification->setType($notificationType);
            $notification->setCreatedAt(new \DateTime());
            $notification->setOptions(json_encode($options));
            $em->persist($notification);
        }
        $em->flush();
    }
}
