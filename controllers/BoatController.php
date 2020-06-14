<?php

declare(strict_types=1);

namespace controllers;

use entities\Battle;
use entities\Boat;
use entities\User;
use peps\core\Router;
use stdClass;

final class BoatController
{
    /**
     * Enregistre la position des bateaux en BDD.(mode multijoueur uniquement)
     * POST battle/postBoat
     * @return void
     */
    public static function postBoat(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        $boat = new Boat();
        if (!$boat->idUser = User::getUserSession()->idUser)
            Router::json(JSON_encode($obj));
        $boat->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?: null;
        //Si le bateau existe déjà on le supprime et on en crée un nouveau avec ses positions.
        $boat->deleteBoat();
        $boat->positions = filter_input(INPUT_POST, 'positions', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) ?: null;
        $boat->direction = filter_input(INPUT_POST, 'direction', FILTER_VALIDATE_INT) ?: null;
        $boat->count = filter_input(INPUT_POST, 'count', FILTER_VALIDATE_INT) ?: null;
        $boat->persist();
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }

    /**
     * Récupère la position des bateaux en BDD.(mode multijoueur uniquement)
     * POST battle/getBoat
     * @return void
     */
    public static function getBoats(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$battle = Battle::findOneBy(['idUser1' => User::getUserSession()->idUser]))
            Router::json(json_encode($obj));
        $boats = Boat::findAllBy(['idUser' => $battle->idUser2]);
        Router::json(JSON_encode($boats));
    }

    /**
     * Supprime tous les bateaux associés au joueur.
     * POST battle/deleteBoats
     * @return void
     */
    public static function deleteBoats(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$boat = Battle::findOneBy(['idUser' => User::getUserSession()->idUser]))
            Router::json(json_encode($obj));
        $boat->deleteBoats();
    }
}
