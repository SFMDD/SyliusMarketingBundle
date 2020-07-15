<?php


namespace FMDD\SyliusMarketingPlugin\EventListener;


use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;
use Sylius\Component\Channel\Context\ChannelContextInterface;

abstract class AbstractMarketingListener
{

    /**
     * @var ChannelContextInterface
     */
    protected ChannelContextInterface $channelContext;

    public function __construct(
        ChannelContextInterface $channelContext
    ){
        $this->channelContext = $channelContext;
    }

    public function blockInit(BlockEvent $blockEvent, string $template){
        $block = new Block();
        $block->setId(uniqid('', true));
        $block->setSettings(array_replace($blockEvent->getSettings(), [
            'template' => $template
        ]));
        $block->setType('sonata.block.service.template');
        return $block;
    }

    private function checkGoogleAdwords(){
        $adwords = $this->channelContext->getChannel()->getGoogleAdwords();
        if(empty(str_replace("AW-", "", $adwords)))
            return false;
        return true;
    }

    private function checkGoogleAnalytics(){
        $analytics = $this->channelContext->getChannel()->getGoogleAnalytics();
        if(empty(str_replace("UA-", "", $analytics)))
            return false;
        return true;
    }

    public function isEnabledFacebook(){
        $pixel = $this->channelContext->getChannel()->getFacebookPixel();
        if(!empty($pixel))
            return true;
        return false;
    }

    public function isEnabledGoogle(){
        if($this->checkGoogleAnalytics())
            return true;
        return false;
    }

    public function isEnabled(){
        if($this->isEnabledGoogle() and $this->isEnabledFacebook()){
            return true;
        }
        return false;
    }
}
