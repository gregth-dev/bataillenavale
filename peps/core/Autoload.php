<?php

declare(strict_types=1);

namespace peps\core;

/**
 * Classe 100% statique d'autoload.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
final class Autoload
{
	/**
	 * Constructeur privé.
	 */
	private function __construct()
	{
	}

	/**
	 * Initialise l'autoload. DOIT être appelée depuis le contrôleur frontal en tout premier.
	 *
	 * @return void
	 */
	public static function init(): void
	{
		// Inscrire la fonction d'autoload dans la pile d'autoload.
		spl_autoload_register(fn ($className) => require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php');
	}
}
