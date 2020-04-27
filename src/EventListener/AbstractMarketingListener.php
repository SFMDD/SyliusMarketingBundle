<?php


namespace FMDD\SyliusMarketingPlugin\EventListener;


abstract class AbstractMarketingListener
{
    /**
     * @var string
     */
    public $googleAnalytics;
    /**
     * @var string
     */
    public $googleAdwords;
    /**
     * @var string
     */
    public $googleId;
    /**
     * @var string
     */
    public $googleTypeMerchant;
    /**
     * @var string
     */
    public $facebookPixel;
    /**
     * @var string
     */
    public $urlPrivacy;
    /**
     * @var string
     */
    public $websiteName;
    /**
     * @var string
     */
    public $author;
    /**
     * @var string
     */
    public $googleEventPurchase;
    /**
     * @var string
     */
    public $googleEventSearch;
    /**
     * @var string
     */
    public $googleProductShow;
    /**
     * @var string
     */
    public $googleEventRegistration;
    /**
     * @var string
     */
    public $googleEventCheckoutProgress;
    /**
     * @var string
     */
    public $googleEventSelectPayment;

    public function __construct(
        string $googleAnalytics,
        string $googleAdwords,
        string $googleId,
        string $googleTypeMerchant,
        string $facebookPixel,
        string $urlPrivacy,
        string $websiteName,
        string $author,
        string $googleEventPurchase,
        string $googleEventSearch,
        string $googleProductShow,
        string $googleEventRegistration,
        string $googleEventCheckoutProgress,
        string $googleEventSelectPayment
    ){
        $this->googleAnalytics = $googleAnalytics;
        $this->googleAdwords = $googleAdwords;
        $this->googleId = $googleId;
        $this->googleTypeMerchant = $googleTypeMerchant;
        $this->facebookPixel = $facebookPixel;
        $this->urlPrivacy = $urlPrivacy;
        $this->websiteName = $websiteName;
        $this->author = $author;
        $this->googleEventPurchase = $googleEventPurchase;
        $this->googleEventSearch = $googleEventSearch;
        $this->googleProductShow = $googleProductShow;
        $this->googleEventRegistration = $googleEventRegistration;
        $this->googleEventCheckoutProgress = $googleEventCheckoutProgress;
        $this->googleEventSelectPayment = $googleEventSelectPayment;
    }

    private function checkGoogleAdwords(){
        if(empty(str_replace("AW-", "", $this->googleAdwords)))
            return false;
        return true;
    }

    private function checkGoogleAnalytics(){
        if(empty(str_replace("UA-", "", $this->googleAnalytics)))
            return false;
        return true;
    }

    public function isEnabledFacebook(){
        if(!empty($this->facebookPixel))
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
