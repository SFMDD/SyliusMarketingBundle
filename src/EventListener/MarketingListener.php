<?php

namespace FMDD\SyliusMarketingPlugin\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class MarketingListener extends AbstractMarketingListener
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

    public function buildLayoutHeader(BlockEvent $blockEvent)
    {
        if($this->isEnabled())
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/layout_header.html.twig"));
    }

    public function buildMetadata(BlockEvent $blockEvent)
    {
        if($this->isEnabled() and !empty($this->author))
            $blockEvent->addBlock($this->blockInit($blockEvent, "@FMDDSyliusMarketingPlugin/Marketing/metadata.html.twig"));
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
