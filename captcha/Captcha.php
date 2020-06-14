<?php

declare(strict_types=1);

namespace captcha;

use cfg\CfgApp;

class Captcha
{
    /**
     * Url fournit pas GoogleCaptcha
     */
    private const URL = "https://www.google.com/recaptcha/api/siteverify";

    private function __construct()
    {
    }

    /**
     * Renvoie true si le token est correct, false sinon.
     *
     * @param string $response Token renvoyé pas l'api de GoogleCaptcha.
     * @return void
     */
    public static function validateCaptcha(?string $response): string
    {
        if (function_exists('curl_version')) {
            // Onrécupère la clé de validation côté server.
            $key = CfgApp::get('captchaKey');
            // On envoie à l'api la clé et le token reçu après l'envoi du formulaire
            $url = self::URL . "?secret={$key}&response={$response}";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
        } else {
            // On utilise file_get_contents
            $response = file_get_contents($url);
        }
        return $response;
    }
}
