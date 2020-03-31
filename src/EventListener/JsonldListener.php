<?php

namespace FMDD\SyliusMarketingPlugin\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class JsonldListener extends AbstractMarketingListener
{
    public function __construct(string $googleAnalytics, string $googleAdwords, string $googleId, string $googleTypeMerchant, string $facebookPixel, string $urlPrivacy, string $websiteName, string $author, string $googleEventPurchase, string $googleEventSearch, string $googleProductShow, string $googleEventRegistration, string $googleEventCheckoutProgress, string $googleEventSelectPayment)
    {
        parent::__construct($googleAnalytics, $googleAdwords, $googleId, $googleTypeMerchant, $facebookPixel, $urlPrivacy, $websiteName, $author, $googleEventPurchase, $googleEventSearch, $googleProductShow, $googleEventRegistration, $googleEventCheckoutProgress, $googleEventSelectPayment);
    }

    private function blockInit(BlockEvent $blockEvent, string $template){
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings(array_replace($blockEvent->getSettings(), [
            'template' => $template
        ]));
        $block->setType('sonata.block.service.template');
        return $block;
    }

    public function buildBreadcrumbProduct(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Jsonld/breadcrumb_product.html.twig"));
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
}
