<?php


namespace FMDD\SyliusMarketingPlugin\Trustpilot;


use Setono\SyliusTrustpilotPlugin\Trustpilot\Order\EmailManager\EmailManagerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

class EmailManager implements EmailManagerInterface
{
    /** @var SenderInterface */
    private $emailSender;

    /** @var string */
    private $trustpilotEmail;

    /** @var string */
    private $locale;

    public function __construct(
        SenderInterface $emailSender,
        string $trustpilotEmail,
        string $locale
    ) {
        $this->emailSender = $emailSender;
        $this->trustpilotEmail = $trustpilotEmail;
        $this->locale = $locale;
    }

    public function sendTrustpilotEmail(OrderInterface $order): void
    {
        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        $this->emailSender->send('trustpilot_email', [
            $this->trustpilotEmail,
            'quickly.web.marketing@gmail.com'
        ], [
            'order' => $order,
            'customer' => $customer,
            'locale' =>  str_replace('_', '-', $this->country_code_to_locale($this->locale)),
        ]);
    }

    private function country_code_to_locale($country_code, $language_code = '')
    {
        $locales = array("da-DK","de-DE","en-GB","es-ES","fi-FI","fr-FR","it-IT","nb-NO","nl-NL","pl-PL","pt-PT","ru-RU","sv-SE","en-AU","en-IE","de-AT","de-CH","fr-BE","nl-BE","ja-JP","pt-BR","en-US","en-CA","en-NZ");

        foreach ($locales as $locale)
        {
            $locale_region = locale_get_region($locale);
            $locale_language = locale_get_primary_language($locale);
            $locale_array = array('language' => $locale_language,
                'region' => $locale_region);

            if (strtoupper($country_code) == $locale_region &&
                $language_code == '')
            {
                return locale_compose($locale_array);
            }
            elseif (strtoupper($country_code) == $locale_region &&
                strtolower($language_code) == $locale_language)
            {
                return locale_compose($locale_array);
            }
        }
        return null;
    }
}
























