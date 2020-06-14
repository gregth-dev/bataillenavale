<?php

declare(strict_types=1);

namespace controllers;

use captcha\Captcha;
use peps\core\Cfg;
use peps\core\Router;
use stdClass;

final class DisplayController
{

    /**
     * Affiche la vue de la page d'accueil.
     * @return void
     */
    public static function game(): void
    {
        Router::render(Cfg::get('accueil'));
    }

    /**
     * Affiche la vue des règles du jeu.
     * GET help/reglesdujeu
     * @return void
     */
    public static function reglesdujeu(): void
    {
        Router::render(Cfg::get('reglesdujeu'));
    }

    /**
     * Affiche la vue installation des bateaux.
     * GET help/installation
     * @return void
     */
    public static function installation(): void
    {
        Router::render(Cfg::get('installation'));
    }

    /**
     * Affiche la vue sur les attaques spéciales.
     * GET help/attaques
     * @return void
     */
    public static function attaques(): void
    {
        Router::render(Cfg::get('attaques'));
    }

    /**
     * Affiche la vue sur les parties multijoueur.
     * GET help/multijoueur
     * @return void
     */
    public static function multijoueur(): void
    {
        Router::render(Cfg::get('multijoueur'));
    }

    /**
     * Affiche la vue de la charte du site.
     * GET /charte
     * @return void
     */
    public static function charte(): void
    {
        Router::render(Cfg::get('charte'));
    }

    /**
     * Affiche la vue contact du site.
     * GET /contact
     * @return void
     */
    public static function contact(): void
    {
        Router::render(Cfg::get('contact'));
    }

    /**
     * Récupère les informations du formulaire puis les envoie par mail.
     * Renvoie le résultat côté client.
     *
     * @return void
     */
    public static function postContact(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING) ?: null;
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING) ?: null;
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null;
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) ?: null;
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING) ?: null;
        $postData = [$prenom, $nom, $email, $message];
        // On renvoie le champ qui vaut null, s'il y en a.
        foreach ($postData as $data => $value) {
            if (!$value) {
                $obj->field = $data;
                Router::json(json_encode($obj));
            }
        }
        // On test le captcha, on renvoie une erreur s'il est incorrect.
        if (!Captcha::validateCaptcha($token)) {
            $obj->errorCaptcha = false;
            Router::json(json_encode($obj));
        }
        // On post le mail si tout va bien.
        $emailTo = 'gregory.thorel@live.fr';
        $headers = "From: " . $prenom . " {$nom} <{$email}>\r\nReply-To: {$email}";
        mail($emailTo, "Message depuis Bataille Navale", $message, $headers);
        $obj->value = true;
        Router::json(json_encode($obj));
    }
}
