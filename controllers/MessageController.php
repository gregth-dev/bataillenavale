<?php

declare(strict_types=1);

namespace controllers;

use entities\Battle;
use entities\Message;
use entities\User;
use peps\core\Router;
use stdClass;

/**
 * Contrôle l'affichage et l'envoie des messages.
 * @see Message
 * @see Router
 */
final class MessageController
{
    /**
     * Envoie les messages en BDD.
     * POST message/postMessage
     * @return void
     */
    public static function postMessage(): void
    {
        //Vérifier si l'utilisateur est connecté, sinon envoyer un message d'erreur.
        $message = new Message();
        if (!$message->idUser = User::getUserSession()->idUser) {
            $obj = new stdClass();
            $obj->value = false;
            Router::json(json_encode($obj));
        }
        //Récupérer les données POST.
        $message->content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) ?: null;
        $message->private = filter_input(INPUT_POST, 'option', FILTER_SANITIZE_STRING) === 'private' ? 1 : 0;
        date_default_timezone_set("Europe/Paris");
        $message->date = date('Y-m-d H:i:s', time());
        $message->persist();
    }

    /**
     * Récupère les messages en BDD.
     * GET message/getMessage
     * @return void
     */
    public static function getMessage(): void
    {
        $option = filter_input(INPUT_POST, 'option', FILTER_SANITIZE_STRING) ?: null;
        //Vérifier si l'utilisateur est connecté, sinon envoyer un message d'erreur.
        if (!$user = User::getUserSession()) {
            $obj = new stdClass();
            $obj->value = false;
            Router::json(json_encode($obj));
        }
        // Récupérer les données POST.
        $messages = Message::getAll();
        if (!$option) {
            if (count($messages) > Message::NB_MSG_LIMIT)
                Message::deleteMsg();
        } else if ($option === 'private') {
            $battle = Battle::findOneBy(['idUser1' => $user->idUser]);
            $messages = Message::getAllPrivate($user->idUser, $battle->idUser2);
        }
        // On rempli le tableau de messages en fonction des données utilisateurs.
        // Permet en cas de mis à jour du profil quelle soit répercutée sur le chat.
        $tempTab = [];
        foreach ($messages as $message) {
            $obj = new stdClass();
            if ($message->user->name) {
                $obj->content = $message->content;
                $obj->name = $message->user->name;
                $obj->avatar = $message->user->avatar;
                $obj->date = $message->date;
                $tempTab[] = $obj;
            }
        }
        $messages = array_reverse($tempTab);
        Router::json(json_encode($messages));
    }

    /**
     * Supprime les messages en BDD.
     * GET message/deletePrivateMessage
     * @return void
     */
    public static function deletePrivateMessage(): void
    {
        //Vérifier si l'utilisateur est connecté, sinon envoyer un message d'erreur.
        if (!$user = User::getUserSession()) {
            $obj = new stdClass();
            $obj->value = false;
            Router::json(json_encode($obj));
        }
        $message = new Message();
        $message->idUser = $user->idUser;
        $message->deletePrivateMsg();
    }
}
