<?php

declare(strict_types=1);

namespace entities;

use peps\core\DBAL;
use peps\core\ORMDB;

/**
 * Entité Message.
 * 
 * @see ORMDB
 */
class Message extends ORMDB
{
	/**
	 * Id.
	 */
	public ?int $idMessage = null;

	/**
	 * ID de l'expéditeur.
	 */
	public ?int $idUser = null;

	/**
	 * Contenu du message.
	 */
	public ?string $content = null;

	/**
	 * Date d'envoie du message.
	 */
	public ?string $date = null;

	/**
	 * Privé ou non.
	 */
	public ?int $private = 0;

	private ?User $user = null;

	/**
	 * Constante d'erreur si la session est déjà active.
	 */
	public const NB_MSG_LIMIT = 10;


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
	protected function getUser(): User
	{
		if (!$this->user) {
			$this->user = new User();
			$this->user->idUser = $this->idUser;
			$this->user->hydrate();
		}
		return $this->user;
	}

	/**
	 * Retourne un tableau d'instances de Message.
	 *
	 * @return array
	 */
	public static function getAll(): array
	{
		$q = "SELECT * FROM message WHERE private = 0 ORDER BY date DESC";
		return DBAL::getDB()->xeq($q)->findAll(self::class);
	}

	/**
	 * Retourne un tableau d'instances de Message.
	 *
	 * @return array
	 */
	public static function getAllPrivate($user1, $user2): array
	{
		$q = "SELECT * FROM message WHERE (idUser = :idUser1 OR idUser = :idUser2) AND private = 1 ORDER BY date DESC";
		$params = [':idUser1' => $user1, ':idUser2' => $user2];
		return DBAL::getDB()->xeq($q, $params)->findAll(self::class);
	}

	/**
	 * Supprime un enregistrement.
	 *
	 * @return void
	 */
	public static function deleteMsg()
	{
		$q = "DELETE FROM message ORDER BY date ASC LIMIT 1";
		DBAL::getDB()->xeq($q);
	}

	/**
	 * Supprime les messages privés.
	 *
	 * @return void
	 */
	public function deletePrivateMsg()
	{
		$q = "DELETE FROM message WHERE idUser = :idUser AND private = 1";
		$params = [':idUser' => $this->idUser];
		DBAL::getDB()->xeq($q, $params);
	}
}
