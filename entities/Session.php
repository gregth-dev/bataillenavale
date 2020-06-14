<?php

declare(strict_types=1);

namespace entities;

use peps\core\DBAL;
use peps\core\ORMDB;

/**
 * Entité Session.
 * 
 * @see DBAL
 * @see ORMDB
 */
class Session extends ORMDB {
    /**
	 * Id.
	 */
	public ?string $sid = null;

	/**
	 * Identifiant de session.
	 */
	public ?string $data = null;

	/**
	 * Data de session. ID de l'utilisateur connecté.
	 */
	public ?string $dateSession = null;

	/**
	 * Constante d'erreur si la session est déjà active.
	 */
	public const ERR_SESSION_ALREADY_ACTIVATE = "Vous êtes déjà connecté";

	/**
	 * Constructeur public nécessaire.
	 */
	public function __construct()
	{
    }
	
	/**
	 * Renvoie un tableau contenant les id des utilisateurs connectés.
	 *
	 * @return array
	 */
    public static function getOnline(): array {
        $q = "SELECT * FROM session WHERE data != ''";
        $online = DBAL::getDB()->xeq($q)->findAll();
        $idList = [];
        foreach ($online as $idUser) {
            $idUser = $idUser->data;
            preg_match_all('!\d+\.*\d*!', $idUser, $matches);;
            $idUser = (int)$matches[0][0];
            $idList[] = $idUser;
        }
        return array_unique($idList, SORT_NUMERIC);
	}

	/**
	 * Renvoie true si l'id passé en paramètre est présent dans la table session, false sinon.
	 *
	 * @param int $id IdUser de l'utilisateur.
	 * @return boolean
	 */
	public static function sessionUserExist(int $id): bool {
		if(in_array($id,self::getOnline()))
			return true;
		return false;
	}
}