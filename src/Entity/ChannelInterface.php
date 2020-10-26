<?php


namespace FMDD\SyliusMarketingPlugin\Entity;


interface ChannelInterface
{
    public function getContactPhone():? string;
    public function setContactPhone(string $contactPhone): void;

    public function getContactOpeningTime():? string;
    public function setContactOpeningTime(string $contactOpeningTime): void;

    public function getFacebookPixel():? string;
    public function setFacebookPixel(string $facebookPixel): void;

    public function getGoogleAnalytics():? string;
    public function setGoogleAnalytics(string $googleAnalytics): void;

    public function getGoogleAdwords():? string;
    public function setGoogleAdwords(string $googleAdwords): void;

    public function getGoogleId():? string;
    public function setGoogleId(string $googleId): void;

    public function getGoogleTypeMerchant():? string;
    public function setGoogleTypeMerchant(string $googleTypeMerchant): void;

    public function getGoogleEventPurchase():? string;
    public function setGoogleEventPurchase(string $googleEventPurchase): void;

    public function getGoogleEventSearch():? string;
    public function setGoogleEventSearch(string $googleEventSearch): void;

    public function getGoogleEventProductShow():? string;
    public function setGoogleEventProductShow(string $googleEventProductShow): void;

    public function getGoogleEventRegistration():? string;
    public function setGoogleEventRegistration(string $googleEventRegistration): void;

    public function getGoogleEventCheckoutProgress():? string;
    public function setGoogleEventCheckoutProgress(string $googleEventCheckoutProgress): void;

    public function getGoogleEventSelectPayment():? string;
    public function setGoogleEventSelectPayment(string $googleEventSelectPayment): void;

    public function getUrlPrivacy():? string;
    public function setUrlPrivacy(string $urlPrivacy): void;

    public function getContactGeoLatitudeLongitude():? string;
    public function getContactGeoLatitudeLongitudeArray():? array;
    public function setContactGeoLatitudeLongitude(string $contactGeoLatitudeLongitude): void;

    public function getContactGeoOpeningHours():? string;
    public function getContactGeoOpeningHoursArray():? array;
    public function setContactGeoOpeningHours(string $contactGeoOpeningHours): void;
}