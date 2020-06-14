<?php

declare(strict_types=1);

namespace peps\core;

/**
 * Classe 100% statique de routage. Offre 5 méthodes de routage:
 * route(): routage initial mais aussi redirection côté serveur.
 * render(): rendre une vue.
 * json(): envoyer un JSON.
 * download(): envoyer un fichier (flux binaire).
 * redirect(): redirection côté client.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
final class Router
{
	/**
	 * Constructeur privé.
	 */
	private function __construct()
	{
	}

	/**
	 * Sans paramètre, analyse la requête du client, détermine la route et invoque la méthode appropriée du contrôleur approprié.
	 * Avec paramètres, effectue une redirection côté serveur.
	 *
	 * @param string|null $verb Verbe HTTP de la requête.
	 * @param string|null $uri URI de la requête.
	 * @return void
	 */
	public static function route(?string $verb = null, ?string $uri = null): void
	{
		// Si absents, récupérer le verbe HTTP et l'URI de la requête cliente.
		$verb = $verb ?: filter_var(getenv( 'REQUEST_METHOD'), FILTER_SANITIZE_STRING);
		$uri = $uri ?:filter_var(getenv('REQUEST_URI'), FILTER_SANITIZE_STRING);
		// Si pas de verbe ou d'URI, alors rendre la vue 404.
		if (!$verb || !$uri)
			self::render(Cfg::get('ERROR_404_VIEW'));
		$routes = json_decode(file_get_contents(Cfg::get('ROUTES_FILE')));
		foreach ($routes as $route) {
			// Utiliser l'expression régulière de l'URI (avec un slash final optionnel).
			$regexp = "@^{$route->uri}/?$@i";
			// Si une route correspondante est trouvée...
			if (!strcasecmp($route->verb, $verb) && preg_match($regexp, $uri, $params)) {
				// Supprimer le premier paramètre.
				array_shift($params);
				// Si paramètres, utiliser les noms fournis (si disponibles) pour obtenir un tableau associatif, sinon tableau indicé.
				if (($assocParams = $params) && !empty($route->params))
					@$assocParams = array_combine($route->params, $params) ?: $params;
				// Séparer le nom du contrôleur de celui de la méthode.
				$controllerName = Cfg::get('CONTROLLERS_NAMESPACE') . '\\' . mb_substr($route->method, 0, mb_strpos($route->method, '.'));
				$methodName = mb_substr($route->method, mb_strpos($route->method, '.') + 1);
				// Invoquer la méthode du contrôleur.
				$controllerName::$methodName($assocParams);
				return;
			}
		}
		// Si aucune route trouvée, rendre la vue 404.
		self::render(Cfg::get('ERROR_404_VIEW'));
	}

	/**
	 * Rend une vue.
	 *
	 * @param string $view Nom de la vue.
	 * @param array $params Tableau associatif des paramètres à transmettre à la vue.
	 * @return void
	 */
	public static function render(string $view, array $params = []): void
	{
		// Transformer chaque clé en variable.
		extract($params);
		// Envoyer la vue vers le client.
		require Cfg::get('VIEWS_DIR') . DIRECTORY_SEPARATOR . $view;
		// Arrêter le script.
		exit;
	}

	/**
	 * Envoie au client une chaîne JSON.
	 *
	 * @param string $json Chaîne JSON.
	 * @return void
	 */
	public static function json(string $json): void
	{
		// Paramétrer l'entête HTTP.
		header('Content-Type: application/json');
		// Envoyer la chaîne JSON au client et arrêter le script.
		exit($json);
	}

	/**
	 * Envoie au client un fichier pour download (ou intégration comme une image par exemple).
	 *
	 * @param string $filePath Chemin du fichier (depuis la racine de l'application).
	 * @param string $mimeType Type MIME du fichier.
	 * @param string $fileName Nom du fichier proposé à l'internaute.
	 * @return void
	 */
	public static function download(string $filePath, string $mimeType, string $fileName = "Fichier"): void
	{
		// Paramétrer l'entête HTTP.
		header("Content-Type: {$mimeType}");
		header("Content-Transfer-Encoding: Binary");
		header('Content-Length: ' . filesize($filePath));
		header("Content-Disposition: attachment; filename={$fileName}");
		// Envoyer le contenu du fichier vers le client.
		readfile($filePath);
		// Arrêter le script.
		exit;
	}

	/**
	 * Redirection côté client. Envoie une requête vers le client pour demander une redirection vers une URI.
	 *
	 * @param string $uri URI.
	 * @return void
	 */
	public static function redirect(string $uri): void
	{
		// Envoyer la demande de redirection au client.
		header("Location: {$uri}");
		// Arrêter le script.
		exit;
	}
}
