<?php

declare(strict_types=1);

namespace Tests\FMDD\SyliusMarketingPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Order as BaseOrder;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrustpilotAwareInterface;
use Setono\SyliusTrustpilotPlugin\Model\OrderTrait as TrustpilotOrderTrait;

/**
 * @ORM\MappedSuperclass
 * @ORM\Table(name="sylius_order")
 */
class Order extends BaseOrder implements OrderTrustpilotAwareInterface
{
    use TrustpilotOrderTrait;
}
