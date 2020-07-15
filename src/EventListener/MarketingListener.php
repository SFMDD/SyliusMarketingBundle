<?php

namespace FMDD\SyliusMarketingPlugin\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;

class MarketingListener extends AbstractMarketingListener
{
    public function __construct(ChannelContextInterface $channelContext)
    {
        parent::__construct($channelContext);
    }

    public function buildLayoutHeader(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/layout_header.html.twig"));
    }

    public function buildMetadata(BlockEvent $blockEvent)
    {
        if($this->isEnabled() and !empty($this->channelContext->getChannel()->getShopBillingData()->getCompany()))
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/metadata.html.twig"));
    }

    public function buildProductShowMetadata(BlockEvent $blockEvent){
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/productShowMetadata.html.twig"));
    }

    public function buildProductIndex(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/product_index.html.twig"));
    }

    public function buildProductShow(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/product_show.html.twig"));
    }

    public function buildPromotion(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/promotion.html.twig"));
    }

    public function buildPurchase(BlockEvent $blockEvent)
    {
        if($this->isEnabled()){
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/purchase.html.twig"));
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Facebook/purchase.html.twig"));
        }
    }

    public function buildRegistration(BlockEvent $blockEvent)
    {
        if($this->isEnabled()){
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/registration.html.twig"));
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Facebook/registration.html.twig"));
        }
    }

    public function buildSearch(BlockEvent $blockEvent)
    {
        if($this->isEnabled()){
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/search.html.twig"));
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Facebook/search.html.twig"));
        }
    }

    public function buildSelectPayment(BlockEvent $blockEvent)
    {
        if($this->isEnabled()){
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/select_payment.html.twig"));
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Facebook/select_payment.html.twig"));
        }
    }

    public function buildViewItemList(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/view_item_list.html.twig"));
    }

    public function buildAddPaymentInfo(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/add_payment_info.html.twig"));
    }

    public function buildBoxProduct(BlockEvent $blockEvent)
    {
        /** TODO: Create impression box product */
        // $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/box_product.html.twig"));
    }

    public function buildCheckoutBegin(BlockEvent $blockEvent)
    {
        if($this->isEnabled()){
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/checkout_begin.html.twig"));
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Facebook/checkout_begin.html.twig"));
        }
    }

    public function buildCheckoutProgress(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/checkout_progress.html.twig"));
    }

    public function buildException(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/Google/exception.html.twig"));
    }
}
