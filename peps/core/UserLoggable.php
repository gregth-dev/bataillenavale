<?php

declare(strict_types=1);

namespace peps\core;

/**
 * Méthodes pour la connexion des utilisateurs.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
interface UserLoggable extends ORM
{

	/**
	 * Tente de loguer le UserLoggable.
	 *
	 * @return boolean True ou false selon que le UserLoggable a été logué ou pas.
	 */
	public function login(): bool;

	/**
	 * Retourne le UserLoggable en session ou null si aucun user en session.
	 *
	 * @return UserLoggable|null UserLoggable en session ou null.
	 */
	public static function getUserSession(): ?self;
}
