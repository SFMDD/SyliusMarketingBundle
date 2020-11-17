<?php


namespace FMDD\SyliusMarketingPlugin\EventListener;

use App\Entity\Notification;
use App\Entity\NotificationType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Sylius\Component\Core\Model\OrderInterface;

class NotificationOrderPayerListener
{
    /**
     * @var Registry
     */
    private Registry $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
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
                'product_image' => $item->getProduct()->getImages()->first(),
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
