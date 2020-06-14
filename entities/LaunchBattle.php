<?php

declare(strict_types=1);

namespace entities;

use peps\core\ORMDB;

/**
 * Entité LaunchBattle. Demande de partie multijoueur.
 */
class LaunchBattle extends ORMDB
{
	/**
	 * Id.
	 */
	public ?int $idLaunchBattle = null;

	/**
	 * Identifiant du joueur 1.
	 */
	public ?int $idUser1 = null;

	/**
	 * Identifiant du joueur 2.
	 */
	public ?int $idUser2 = null;

	/**
	 * Statut de la demande.
	 */
	public int $statut = 0;

	/**
	 * Date max de validité de la demande.
	 */
	public ?string $maxTime = null;

	/**
	 * ReadLaunch.
	 * 1 : la demande a été vue.
	 * 1 : la demande a été vue.
	 */
	public ?int $readLaunch = 0;

	/**
	 * Constante de limite de temps en secondes.
	 */
	public const TIME_LIMIT = 30;

	/**
	 * User1.
	 */
	private ?User $user1 = null;
	
	/**
	 * User2.
	 */
	private ?User $user2 = null;

	/**
	 * Constante d'erreur si le joueur a déjà envoyé une demande.
	 */
	public const ERR_INVALID_DEMAND = "Vous avez déjà envoyé une invitation";

	/**
	 * Constante d'erreur si le joueur a déjà envoyé une demande.
	 */
	public const ERR_ALREADY_IN_GAME = "Le joueur est déjà dans une partie";

	/**
	 * Constructeur public.
	 * Initialise $time et $maxTime.
	 */
	public function __construct()
	{
		date_default_timezone_set('Europe/Paris');
		$this->maxTime = date('Y-m-d H:i:s', time() + SELF::TIME_LIMIT);
	}

	/**
	 * Retourne la date et l'heure à l'instant T.
	 *
	 * @return string
	 */
	public static function getTime(): string
	{
		date_default_timezone_set('Europe/Paris');
		return date('Y-m-d H:i:s', time());
	}

	/**
	 * Retourne l'utilisateur1 de ce LaunchBattle.
	 *
	 * @return User L'utilisateur1
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
	 * Retourne l'utilisateur2 de ce LaunchBattle.
	 *
	 * @return User L'utilisateur2
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
	 * Renvoie true si l'enregistre dans la BDD est possible, false sinon.
	 *
	 * @param object|null $obj Informations renvoyé en JSON.
	 * @return boolean
	 */
	public function validate(?object &$obj = null): bool
	{
		// On vérifie si les joueurs ne sont pas déjà dans une partie.
		$battle = new Battle();
		$battle->idUser1 = $this->idUser1;
		$battle->idUser2 = $this->idUser2;
		if($battle->battleExists()) {
			$obj->message = Battle::ERR_ALREADY_IN_BATTLE;
			return false;
        }
		// On vérifie si l'utilisateur2 exist.
		$this->getUser2();
		if(!$this->user2->name) {
            $obj->message = User::ERR_INVALID_NAME;
			return false;
		}
		// On vérifie si une demande a déjà été envoyé par l'utilisateur1.
        if (self::findOneBy(['idUser1' => $this->idUser1])) {
			$obj->message = LaunchBattle::ERR_INVALID_DEMAND;
            return false;
		}
		return true;
	}
}
