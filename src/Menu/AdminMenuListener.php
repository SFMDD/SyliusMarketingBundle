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
            ->setLabel('Cart abandoned')
            ->setLabelAttribute('icon', 'cart');
    }
}
