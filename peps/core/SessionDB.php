<?php

declare(strict_types=1);

namespace peps\core;

use PDOException;
use SessionHandlerInterface;

/**
 * Gestion des sessions en base de données.
 * NECESSITE une table "session" avec les colonnes "sid", "data", "dateSession".
 * 3 modes possibles:
 * PERSISTENT: La session se termine exclusivement après l'expiration du timeout au-delà de la dernière requête du client.
 * HYBRID: La session se termine à la fermeture du navigateur ou après l'expiration du timeout au-delà de la dernière requête du client. Mode par défaut.
 * ABSOLUTE: La session se termine exclusivement après l'expiration du timeout au-delà de la première requête du client.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
class SessionDB implements SessionHandlerInterface
{
	/**
	 * Constante du mode PERSISTENT.
	 */
	public const MODE_PERSISTENT = 'MODE_PERSISTENT';

	/**
	 * Constante du mode HYBRID.
	 */
	public const MODE_HYBRID = 'MODE_HYBRID';

	/**
	 * Constante du mode ABSOLUTE.
	 */
	public const MODE_ABSOLUTE = 'MODE_ABSOLUTE';

	/**
	 * Durée maxi de la session (secondes).
	 */
	protected static ?int $timeout = null;

	/**
	 * Vrai si session expirée, faux sinon.
	 */
	protected static bool $expired = false;

	/**
	 * Initialise et démarre la session.
	 *
	 * @param int $timeout Durée maxi de la session (secondes).
	 * @param string $mode Mode de la session.
	 * @param string $sameSite Mitigation CSRF. Strict par défaut.
	 * @return void
	 */
	public static function init(int $timeout, string $mode = self::MODE_HYBRID, string $samesite = 'strict'): void
	{
		// Initialiser le timeout.
		self::$timeout = $timeout;
		// Définir la durée de vie du cookie.
		switch ($mode) {
			case self::MODE_PERSISTENT:
				ini_set('session.cookie_lifetime', (string) (86400 * 365 * 20)); // 20 ans = infini...
				break;
			case self::MODE_ABSOLUTE:
				ini_set('session.cookie_lifetime', "{$timeout}");
				break;
			default:
				ini_set('session.cookie_lifetime', '0');
		}
		// Définir le timeout de GC pour supprimer les sessions expirées.
		ini_set('session.gc_maxlifetime', "{$timeout}");
		// Utiliser les cookies.
		ini_set('session.use_cookies', '1');
		// Utiliser seulement les cookies.
		ini_set('session.use_only_cookies', '1');
		// Ne pas passer l'ID de session en GET.
		ini_set('session.use_trans_sid', '0');
		// Mitiger les attaques XSS (Cross Site Scripting = injections) en interdisant l'accès aux cookies via JS.
		ini_set('session.cookie_httponly', '1');
		// Mitiger les attaques SFA (Session Fixation Attack) en refusant les cookies non générés par PHP.
		ini_set('session.use_strict_mode', '1');
		// Mitiger les attaques CSRF (Cross Site Request Forgery).
		ini_set('session.cookie_samesite', "{$samesite}");
		// Définir une instance de cette classe comme gestionnaire des sessions.
		session_set_save_handler(new self());
		// Démarrer la session.
		session_start();
		// Si session expirée, la détruire et démarrer une nouvelle.
		if (self::$expired) {
			session_destroy();
			self::$expired = false;
			session_start();
		}
	}

	/**
	 * Inutile ici.
	 * 
	 * @param string $savePath Chemin du fichier de sauvegarde de la session. Inutile ici.
	 * @param string $sessionName Nom de la session (habituellement PHPSESSID).
	 * @return bool Pour usage interne PHP, ici systématiquement true.
	 */
	public function open($savePath, $sessionName): bool
	{
		//var_dump("SessionDB.open({$savePath}, {$sessionName})"); // DEBUG
		return true;
	}

	/**
	 * Lit les données de session.
	 *
	 * @param string $sid SID.
	 * @return string Données de session (sérialisées).
	 */
	public function read($sid): string
	{
		// Créer la requête de sélection.
		$q = "SELECT * FROM session WHERE sid = :sid";
		$params = [':sid' => $sid];
		// Si une session retrouvée, vérifier sa validité. 
		if ($objSession = DBAL::getDB()->xeq($q, $params)->findOne()) {
			// Si expirée, passer le booléen expired à true et retourner une chaîne vide.
			if ($objSession->dateSession < date('Y-m-d H:i:s', time() - self::$timeout)) {
				//var_dump("SessionDB.read({$sid}): EXPIRED"); // DEBUG
				self::$expired = true;
				return '';
			}
			// Sinon, retourner les données.
			else {
				//var_dump("SessionDB.read({$sid}): FOUND {$objSession->data}"); // DEBUG
				return $objSession->data;
			}
		}
		// Sinon, pas encore de session, retourner une chaîne vide.
		else {
			//var_dump("SessionDB.read({$sid}): NOT FOUND"); // DEBUG
			return '';
		}
	}

	/**
	 * Ecrit les données de session.
	 *
	 * @param string $sid SID.
	 * @param string $data Données de session (sérialisées).
	 * @return boolean Pour usage interne PHP, ici systématiquement true.
	 */
	public function write($sid, $data): bool
	{
		// Tenter la requête d'insertion.
		try {
			$q = "INSERT INTO session VALUES(:sid, :data, :dateSession)";
			$params = [':sid' => $sid, ':data' => $data, ':dateSession' => date('Y-m-d H:i:s')];
			DBAL::getDB()->xeq($q, $params);
			//var_dump("SessionDB.write({$sid}): INSERT {$data}"); // DEBUG
		}
		// Si échec, mettre à jour.
		catch (PDOException $e) {
			$q = "UPDATE session SET data = :data, dateSession = :dateSession WHERE sid = :sid";
			$params = [':sid' => $sid, ':data' => $data, ':dateSession' => date('Y-m-d H:i:s')];
			DBAL::getDB()->xeq($q, $params);
			//var_dump("SessionDB.write({$sid}): UPDATE {$data}"); // DEBUG
		}
		// Retourner true.
		return true;
	}

	/**
	 * Inutile ici.
	 * 
	 * @return bool Pour usage interne PHP, ici systématiquement true.
	 */
	public function close(): bool
	{
		//var_dump("SessionDB.close"); // DEBUG
		return true;
	}

	/**
	 * Détruit la session (cookie et DB).
	 *
	 * @param string $sid SID.
	 * @return bool Pour usage interne PHP, ici systématiquement true.
	 */
	public function destroy($sid): bool
	{
		//var_dump("SessionDB.destroy({$sid})"); // DEBUG
		$sessionName = session_name();
		// Supprimer le cookie du navigateur.
		setcookie($sessionName, '', time() - 3600, '/');
		// Supprimer la clé du tableau des cookies du serveur.
		unset($_COOKIE[$sessionName]);
		// Supprimer la session de la DB.
		$q = "DELETE FROM session WHERE sid = :sid";
		$params = [':sid' => $sid];
		DBAL::getDB()->xeq($q, $params);
		// Retourner true.
		return true;
	}

	/**
	 * Garbage Collector, supprime les sessions expirées en base de données.
	 *
	 * @param int $maxLifetime Durée de vie maxi d'une session (secondes).
	 * @return bool True si la suppression a réussi, false sinon.
	 */
	public function gc($maxLifetime): bool
	{
		//var_dump("SessionDB.gc({$maxLifetime})"); // DEBUG
		// Créer la requête de suppression.
		$q = "DELETE FROM session WHERE dateSession < :dateMin";
		$params = [':dateMin' => date('Y-m-d H:i:s', time() - $maxLifetime)];
		// Retourner le nombre d'enregistrements supprimés casté en booléen.
		return (bool) DBAL::getDB()->xeq($q, $params)->nb();
	}
}
