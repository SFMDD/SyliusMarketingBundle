<?php

namespace FMDD\SyliusMarketingPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ChannelTrait
{

    /**
     * @var string
     *
     * @ORM\Column(name="url_privacy", type="string", nullable=true)
     */
    private $urlPrivacy;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_phone", type="string", nullable=true)
     */
    private $contactPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_opening_time", type="string", nullable=true)
     */
    private $contactOpeningTime;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_geo_latitude_longitude", type="string", nullable=true)
     */
    private $contactGeoLatitudeLongitude;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_geo_opening_hours", type="string", nullable=true)
     */
    private $contactGeoOpeningHours;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_pixel", type="string", nullable=true)
     */
    private $facebookPixel;

    /**
     * @var string
     *
     * @ORM\Column(name="google_analytics", type="string", nullable=true)
     */
    private $googleAnalytics;

    /**
     * @var string
     *
     * @ORM\Column(name="google_adwords", type="string", nullable=true)
     */
    private $googleAdwords;

    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    private $googleId;

    /**
     * @var string
     *
     * @ORM\Column(name="google_type_merchant", type="string", nullable=true)
     */
    private $googleTypeMerchant;

    /**
     * @var string
     *
     * @ORM\Column(name="google_event_purchase", type="string", nullable=true)
     */
    private $googleEventPurchase;

    /**
     * @var string
     *
     * @ORM\Column(name="google_event_search", type="string", nullable=true)
     */
    private $googleEventSearch;

    /**
     * @var string
     *
     * @ORM\Column(name="google_event_product_show", type="string", nullable=true)
     */
    private $googleEventProductShow;

    /**
     * @var string
     *
     * @ORM\Column(name="google_event_registration", type="string", nullable=true)
     */
    private $googleEventRegistration;

    /**
     * @var string
     *
     * @ORM\Column(name="google_event_checkout_progress", type="string", nullable=true)
     */
    private $googleEventCheckoutProgress;

    /**
     * @var string
     *
     * @ORM\Column(name="google_event_select_payment", type="string", nullable=true)
     */
    private $googleEventSelectPayment;

    /**
     * @return string
     */
    public function getContactPhone():? string
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     */
    public function setContactPhone(string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return string
     */
    public function getContactOpeningTime():? string
    {
        return $this->contactOpeningTime;
    }

    /**
     * @param string $contactOpeningTime
     */
    public function setContactOpeningTime(string $contactOpeningTime): void
    {
        $this->contactOpeningTime = $contactOpeningTime;
    }

    /**
     * @return string
     */
    public function getFacebookPixel():? string
    {
        return $this->facebookPixel;
    }

    /**
     * @param string $facebookPixel
     */
    public function setFacebookPixel(string $facebookPixel): void
    {
        $this->facebookPixel = $facebookPixel;
    }

    /**
     * @return string
     */
    public function getGoogleAnalytics():? string
    {
        return $this->googleAnalytics;
    }

    /**
     * @param string $googleAnalytics
     */
    public function setGoogleAnalytics(string $googleAnalytics): void
    {
        $this->googleAnalytics = $googleAnalytics;
    }

    /**
     * @return string
     */
    public function getGoogleAdwords():? string
    {
        return $this->googleAdwords;
    }

    /**
     * @param string $googleAdwords
     */
    public function setGoogleAdwords(string $googleAdwords): void
    {
        $this->googleAdwords = $googleAdwords;
    }

    /**
     * @return string
     */
    public function getGoogleId():? string
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string
     */
    public function getGoogleTypeMerchant():? string
    {
        return $this->googleTypeMerchant;
    }

    /**
     * @param string $googleTypeMerchant
     */
    public function setGoogleTypeMerchant(string $googleTypeMerchant): void
    {
        $this->googleTypeMerchant = $googleTypeMerchant;
    }

    /**
     * @return string
     */
    public function getGoogleEventPurchase():? string
    {
        return $this->googleEventPurchase;
    }

    /**
     * @param string $googleEventPurchase
     */
    public function setGoogleEventPurchase(string $googleEventPurchase): void
    {
        $this->googleEventPurchase = $googleEventPurchase;
    }

    /**
     * @return string
     */
    public function getGoogleEventSearch():? string
    {
        return $this->googleEventSearch;
    }

    /**
     * @param string $googleEventSearch
     */
    public function setGoogleEventSearch(string $googleEventSearch): void
    {
        $this->googleEventSearch = $googleEventSearch;
    }

    /**
     * @return string
     */
    public function getGoogleEventProductShow():? string
    {
        return $this->googleEventProductShow;
    }

    /**
     * @param string $googleEventProductShow
     */
    public function setGoogleEventProductShow(string $googleEventProductShow): void
    {
        $this->googleEventProductShow = $googleEventProductShow;
    }

    /**
     * @return string
     */
    public function getGoogleEventRegistration():? string
    {
        return $this->googleEventRegistration;
    }

    /**
     * @param string $googleEventRegistration
     */
    public function setGoogleEventRegistration(string $googleEventRegistration): void
    {
        $this->googleEventRegistration = $googleEventRegistration;
    }

    /**
     * @return string
     */
    public function getGoogleEventCheckoutProgress():? string
    {
        return $this->googleEventCheckoutProgress;
    }

    /**
     * @param string $googleEventCheckoutProgress
     */
    public function setGoogleEventCheckoutProgress(string $googleEventCheckoutProgress): void
    {
        $this->googleEventCheckoutProgress = $googleEventCheckoutProgress;
    }

    /**
     * @return string
     */
    public function getGoogleEventSelectPayment():? string
    {
        return $this->googleEventSelectPayment;
    }

    /**
     * @param string $googleEventSelectPayment
     */
    public function setGoogleEventSelectPayment(string $googleEventSelectPayment): void
    {
        $this->googleEventSelectPayment = $googleEventSelectPayment;
    }

    /**
     * @return string
     */
    public function getUrlPrivacy():? string
    {
        return $this->urlPrivacy;
    }

    /**
     * @param string $urlPrivacy
     */
    public function setUrlPrivacy(string $urlPrivacy): void
    {
        $this->urlPrivacy = $urlPrivacy;
    }

    /**
     * @return string
     */
    public function getContactGeoLatitudeLongitude():? string
    {
        return $this->contactGeoLatitudeLongitude;
    }

    public function getContactGeoLatitudeLongitudeArray():? array {
        $pattern = "/'(.*?)'/";
        preg_match_all($pattern, $this->contactGeoLatitudeLongitude, $matches);
        return $matches[1];
    }

    /**
     * @param string $contactGeoLatitudeLongitude
     */
    public function setContactGeoLatitudeLongitude(string $contactGeoLatitudeLongitude): void
    {
        $this->contactGeoLatitudeLongitude = $contactGeoLatitudeLongitude;
    }

    /**
     * @return string
     */
    public function getContactGeoOpeningHours():? string
    {
        return $this->contactGeoOpeningHours;
    }

    public function getContactGeoOpeningHoursArray():? array
    {
        $return = array();
        $pattern = "/'(.*?)'/";
        preg_match_all($pattern, $this->contactGeoOpeningHours, $matches);
        $i = 0;
        $days = array();
        $hours = array();
        foreach ($matches[1] as $match){
            if($i > 4)
                array_push($hours, $match);
            else
                array_push($days, $match);
            $i++;
        }
        array_push($return, $days);
        array_push($return, $hours);
        return $return;
    }

    /**
     * @param string $contactGeoOpeningHours
     */
    public function setContactGeoOpeningHours(string $contactGeoOpeningHours): void
    {
        $this->contactGeoOpeningHours = $contactGeoOpeningHours;
    }
}