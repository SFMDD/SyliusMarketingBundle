<?php

namespace FMDD\SyliusMarketingPlugin\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;

class JsonldListener extends AbstractMarketingListener
{
    public function __construct(ChannelContextInterface $channelContext)
    {
        parent::__construct($channelContext);
    }

    public function buildBreadcrumbProduct(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/breadcrumb_product.html.twig"));
    }

    public function buildBreadcrumbSearch(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/breadcrumb_search.html.twig"));
    }

    public function buildBreadcrumbTaxon(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/breadcrumb_taxon.html.twig"));
    }

    public function buildContactBusiness(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/contact_business.html.twig"));
    }

    public function buildLocalBusiness(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/local_business.html.twig"));
    }

    public function buildProduct(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/product.html.twig"));
    }

    public function buildWebsite(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/website.html.twig"));
    }

    public function buildFaq(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/faq.html.twig"));
    }
}
