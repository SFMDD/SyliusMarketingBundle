<?php


namespace FMDD\SyliusMarketingPlugin\Listener;


use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

class OrderItemListener
{
    public function gTagAddToCart(GenericEvent $event) {
        /** @var OrderInterface $order */
        $order = $event->getSubject();

        Assert::isInstanceOf($order, OrderInterface::class);

        $subject = $event->getSubject();
        file_put_contents('C:/users/flore/Desktop/test.txt', 'class name : ' . get_class($subject));
    }
}