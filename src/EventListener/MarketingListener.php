<?php

namespace FMDD\SyliusMarketingPlugin\EventListener;

use phpDocumentor\Reflection\Types\Boolean;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class MarketingListener
{
    public function __construct()
    {
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

    public function buildLayoutHeader(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/layout_header.html.twig"));
    }

    public function buildMetadata(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/metadata.html.twig"));
    }

    public function buildProductIndex(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/product_index.html.twig"));
    }

    public function buildProductShow(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/product_show.html.twig"));
    }

    public function buildPromotion(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/promotion.html.twig"));
    }

    public function buildPurchase(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/purchase.html.twig"));
    }

    public function buildRegistration(BlockEvent $blockEvent)
    {

        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/registration.html.twig"));
    }

    public function buildSearch(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/search.html.twig"));
    }

    public function buildSelectPayment(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/select_payment.html.twig"));
    }

    public function buildViewItemList(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/view_item_list.html.twig"));
    }
    public function buildAddPaymentInfo(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/add_payment_info.html.twig"));
    }

    public function buildBoxProduct(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/box_product.html.twig"));
    }

    public function buildCheckoutBegin(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/checkout_begin.html.twig"));
    }

    public function buildCheckoutProgress(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/checkout_progress.html.twig"));
    }

    public function buildException(BlockEvent $blockEvent)
    {
        $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/exception.html.twig"));
    }
}
