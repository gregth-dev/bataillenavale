<?php

declare(strict_types=1);

namespace entities;

use peps\core\DBAL;
use peps\core\ORMDB;

/**
 * Entité Battle.
 * 
 * @see DBAL
 * @see ORMDB
 */
class Battle extends ORMDB
{
    /**
     * Id.
     */
    public ?int $idBattle = null;

    /**
     * Id de la partie en cours.
     */
    public ?int $idLaunchBattle = null;

    /**
     * Nom du joueur 1.
     */
    public ?int $idUser1 = null;

    /**
     * Nom du joueur 2.
     */
    public ?int $idUser2 = null;

    /**
     * Indique si le joueur est prêt.
     * 0 : pas prêt. 
     * 1 : prêt.
     * 2 : le joueur a quitté la partie
     * 2 : le joueur a gagné la partie
     */
    public int $ready = 0;

    /**
     * Case loupé de la grille.
     */
    public ?string $miss = null;

    /**
     * Case touchée de la grille.
     */
    public ?string $touch = null;
    
    /**
     * Cases bombardées par le joueur.
     */
    public ?string $bombing = null;

    /**
     * Cases megaBombées par le joueur.
     */
    public ?string $megaBomb = null;
    
    /**
     * Case réparé par le joueur.
     */
    public ?string $repair = null;

    /**
     * User.
     */
    private ?User $user1 = null;
    
    /**
     * User.
     */
    private ?User $user2 = null;

    /**
     * Constante d'erreur si le player2 n'est pas disponible.
     */
    public const ERR_ALREADY_IN_BATTLE = "Vous où le joueur êtes déjà dans une partie";

    /**
     * Constante d'erreur si le player2 n'est pas disponible.
     */
    public const ERR_INVALID_PLAYER = "Le joueur sélectionné est invalide";

    /**
     * Constante d'erreur si la partie n'est pas disponible.
     */
    public const ERR_INVALID_BATTLE = "La partie sélectionnée est invalide";

    /**
     * Constructeur public nécessaire.
     */
    public function __construct()
    {
    }

    /**
     * Retourne une instance de User.Lazy loading. Invocation dynamique par __get().
     *
     * @return User
     */
    protected function getUser1(): User
	{
		if (!$this->user1) {
			$this->user1 = new User();
			$this->user1->idUser = $this->idUser1;
			$this->user1->hydrate();
		}
		return $this->user1;
	}

    /**
     * Retourne une instance de User.Lazy loading. Invocation dynamique par __get().
     *
     * @return User
     */
	protected function getUser2(): User
	{
		if (!$this->user2) {
			$this->user2 = new User();
			$this->user2->idUser = $this->idUser2;
			$this->user2->hydrate();
		}
		return $this->user2;
	}

    /**
     * Supprime de la BDD les enregistrements qui ne correspondent pas à l'idLaunchBattle.
     *
     * @return void
     */
    public function deleteOldBattle(): void
    {
        $q = "DELETE FROM battle WHERE idLaunchBattle != :idLaunchBattle";
        $params = [':idLaunchBattle' => $this->idLaunchBattle];
        DBAL::getDB()->xeq($q, $params);
    }

    /**
     * Supprime de la BDD les enregistrements qui corresponde à l'idUser1.
     *
     * @return void
     */
    public function deleteBattle(): void
    {
        $q = "DELETE FROM battle WHERE idUser1 = :idUser1";
        $params = [':idUser1' => $this->idUser1];
        DBAL::getDB()->xeq($q, $params);
    }

    /**
     * Retourne true si une correspondance en BDD exist, false sinon.
     *
     * @return boolean
     */
    public function battleExists(): bool
    {
        $q = "SELECT * FROM battle WHERE (idUser1 = :idUser1 OR idUser2 = :idUser2) AND ready = :ready";
        $params = [':idUser1' => $this->idUser1, ':idUser2' => $this->idUser2, ':ready' => 1];
        return (bool) DBAL::getDB()->xeq($q, $params)->nb();
    }
}
