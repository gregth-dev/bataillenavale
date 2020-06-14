<?php

declare(strict_types=1);

namespace peps\core;

use Locale;
use NumberFormatter;

/**
 * Classe 100% statique de configuration initiale de l'application. DOIT être étendue dans l'application par une classe de configuration générale elle même étendue par une classe finale par serveur.
 * Extension PHP 'intl' requise.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
class Cfg
{
	/**
	 * Tableau des constantes de configuration.
	 */
	private static array $constants = [];

	/**
	 * Constructeur privé.
	 */
	private function __construct()
	{
	}

	/**
	 * Inscrit des constantes de base. DOIT être redéfinie dans la classe enfant pour y enregistrer les constantes de l'application (invoquer alors parent::init en première instruction).
	 * Les clés (MAJUSCULES) inscrites ici sont les seules accessibles aux classes Peps.
	 *
	 * @return void
	 */
	protected static function init(): void
	{
		// Chemin du fichier JSON des routes depuis la racine de l'application.
		self::register('ROUTES_FILE', 'cfg' . DIRECTORY_SEPARATOR . 'routes.json');

		// Espace de nom des contrôleurs.
		self::register('CONTROLLERS_NAMESPACE', 'controllers');

		// Racine des vues depuis la racine de l'application.
		self::register('VIEWS_DIR', 'views');

		// Nom de la vue affichant l'erreur 404.
		self::register('ERROR_404_VIEW', 'view404.php');

		// Locale par défaut en cas de non détection (ex: 'fr' ou 'fr-FR').
		self::register('LOCALE_DEFAULT', 'fr');

		// Locale du client (ex: 'fr' ou 'fr-FR').
		self::register('LOCALE', (function () {
			// Récupérer les locales du navigateur.
			$strLocales = filter_var(getenv('HTTP_ACCEPT_LANGUAGE'), FILTER_SANITIZE_STRING);
			// Retourner la première des locales. Si absente, retourner la locale par défaut.
			return Locale::acceptFromHttp($strLocales) ?: self::get('LOCALE_DEFAULT');
		})());

		// Instance de NumberFormatter pour formater un nombre avec 2 décimales selon la locale.
		self::register('NF_LOCALE_2DEC', (fn () => NumberFormatter::create(self::$constants['LOCALE'], NumberFormatter::PATTERN_DECIMAL, '#,##0.00'))());

		// Instance de NumberFormatter pour formater un nombre avec 2 décimales selon la norme américaine (sans séparateur de milliers), typiquement pour les champs INPUT de type "number" de certains navigateurs.
		self::register('NF_INPUT_2DEC', (fn () => NumberFormatter::create('en-US', NumberFormatter::PATTERN_DECIMAL, '0.00'))());
	}

	/**
	 * Inscrit une constante dans le tableau des constantes.
	 *
	 * @param string $key Clé.
	 * @param [type] $value Valeur.
	 * @return void
	 */
	protected final static function register(string $key, $value = null): void
	{
		self::$constants[$key] = $value;
	}

	/**
	 * Retourne la valeur de la constante à partir de sa clé. Retourne null si clé inexistante.
	 *
	 * @param string $key Clé.
	 * @return mixed|null Valeur.
	 */
	public final static function get(string $key)
	{
		return self::$constants[$key] ?? null;
	}
}
