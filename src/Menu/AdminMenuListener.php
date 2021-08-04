<?php

declare(strict_types=1);

namespace FMDD\SyliusMarketingPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $configuration = $menu->getChild('marketing');

        if (null !== $configuration) {
            $this->addChild($configuration);
        } else {
            $this->addChild($menu->getFirstChild());
        }
    }

    private function addChild(ItemInterface $item): void
    {
        $item
            ->addChild('cartAbandoned', [
                'route' => 'fmdd_sylius_marketing_admin_cart_abandoned_index',
            ])
            ->setAttribute('type', 'link')
            ->setLabel('sylius.menu.admin.main.cart_abandoned')
            ->setLabelAttribute('icon', 'cart');

        $item
            ->addChild('notification', [
                'route' => 'fmdd_sylius_marketing_admin_notification_index',
            ])
            ->setAttribute('type', 'link')
            ->setLabel('sylius.menu.admin.main.notification')
            ->setLabelAttribute('icon', 'paper plane');

        $item
            ->addChild('instagramPosts', [
                'route' => 'fmdd_sylius_marketing_admin_instagram_post_index',
            ])
            ->setAttribute('type', 'link')
            ->setLabel('sylius.menu.admin.main.instagram')
            ->setLabelAttribute('icon', 'instagram');


    }
}
