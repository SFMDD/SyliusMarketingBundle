<?php

declare(strict_types=1);

namespace Tests\FMDD\SyliusMarketingPlugin\Application\Entity;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\Table;
use FMDD\SyliusMarketingPlugin\Entity\ChannelInterface as FMDDChannelInterface;
use FMDD\SyliusMarketingPlugin\Entity\ChannelTrait as FMDDChannelTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

/**
 * @MappedSuperclass
 * @Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements FMDDChannelInterface
{
    use FMDDChannelTrait;

}
