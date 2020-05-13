<?php

use PaypalAddons\classes\API\Onboarding\PaypalGetCredentials;
use PaypalAddons\classes\AbstractMethodPaypal;

include_once(_PS_MODULE_DIR_.'paypal/vendor/autoload.php');

class AdminPaypalGetCredentialsController extends ModuleAdminController
{
    public function init()
    {
        parent::init();

        //$this->setRedirectAfter($this->context->link->getAdminLink('AdminPaypalSetup', true, [], ['checkCredentials' => 1]));
        $method = AbstractMethodPaypal::load();
        $authToken = Configuration::get('PAYPAL_AUTH_TOKEN');
        $partnerId = $method->isSandbox() ? PayPal::PAYPAL_PARTNER_ID_SANDBOX : PayPal::PAYPAL_PARTNER_ID_LIVE;
        $paypalGetCredentials = new PaypalGetCredentials($authToken, $partnerId, $method->isSandbox());
        $result = $paypalGetCredentials->execute();

        if ($result->isSuccess()) {
            $params = [
                'clientId' => $result->getClientId(),
                'secret' => $result->getSecret()
            ];
            $method->setConfig($params);
        }

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminPayPalSetup', true, [], ['checkCredentials' => 1]));
    }
}
