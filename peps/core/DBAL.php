<?php

declare(strict_types=1);

namespace peps\core;

use PDO;
use PDOStatement;

/**
 * Database Abstraction Layer via PDO. Design-pattern singleton.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
final class DBAL
{
	/**
	 * Options de connexion PDO (communes à l'ensemble des DB).
	 */
	private const OPTIONS = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Générer des exceptions plutôt que des erreurs.
		PDO::ATTR_STRINGIFY_FETCHES => false, // Ne pas systématiquement convertir les données en string.
		PDO::ATTR_EMULATE_PREPARES => false // Ne pas faire un simple remplacement des paramètres en PHP dans les requêtes préparées.
	];

	/**
	 * Instance singleton.
	 */
	private static ?self $instance = null;

	/**
	 * Instance de PDO.
	 */
	private ?PDO $db = null;

	/**
	 * Instance de PDOStatement.
	 */
	private ?PDOStatement $stmt = null;

	/**
	 * Nombre d'enregistrements retrouvés (SELECT) ou affectés par la dernière requête.
	 */
	private ?int $nb = null;

	/**
	 * Constructeur privé.
	 */
	private function __construct()
	{
	}

	/**
	 * Crée l'instance singleton puis définit ses paramètres dont le DSN.
	 *
	 * @param string $host Hôte de la DB.
	 * @param integer $port Port de l'hôte.
	 * @param string $dbName Nom de la DB.
	 * @param string $log Identifiant de l'utilisateur DB.
	 * @param string $pwd Mot de passe de l'utilisateur DB.
	 * @param string $charset Jeu de caractères.
	 * @return void
	 */
	public static function init(string $driver, string $host, int $port, string $dbName, string $log, string $pwd, string $charset): void
	{
		// Si déjà initialisé, ne rien faire.
		if (self::$instance)
			return;
		// Créer la chaîne DSN.
		$dsn = "{$driver}:host={$host};port={$port};dbname={$dbName};charset={$charset}";
		// Auto-instancier.
		self::$instance = new self();
		// Créer et stocker la connexion PDO encapsulée.
		self::$instance->db = new PDO($dsn, $log, $pwd, self::OPTIONS);
	}

	/**
	 * Retourne l'instance singleton.
	 *
	 * @return self|null Instance singleton. La méthode init() doit avoir été appelée.
	 */
	public static function getDB(): ?self
	{
		return self::$instance;
	}

	/**
	 * Exécute la requête SQL.
	 *
	 * @param string $q Requête SQL (avec ou sans paramètres).
	 * @param array $params Paramètres éventuels.
	 * @return self this pour chaînage.
	 */
	public function xeq(string $q, array $params = []): self
	{
		// Prépare (si nécessaire) puis exécute la requête SQL $q avec les paramètres $params.
		if ($params) {
			// Si paramètres, préparer puis exécuter la requête via PDOStatement.
			$this->stmt = $this->db->prepare($q);
			$this->stmt->execute($params);
			// Récupérer le nombre d'enregistrements retrouvés ou affectés.
			$this->nb = $this->stmt->rowCount();
		} elseif (mb_stripos(ltrim($q), 'SELECT') === 0) {
			// Si requête SELECT, utiliser PDO->query().
			$this->stmt = $this->db->query($q);
			// Récupérer le nombre d'enregistrements retrouvés.
			$this->nb = $this->stmt->rowCount();
		} else {
			// Si pas requête SELECT, utiliser PDO->exec() et récupérer le nombre d'enregistrements affectés.
			$this->nb = $this->db->exec($q);
		}
		return $this;
	}

	/**
	 * Retourne le nombre d'enregistrements retrouvés (SELECT) ou affectés par la dernière requête.
	 *
	 * @return int Nombre d'enregistrements.
	 */
	public function nb(): int
	{
		return $this->nb;
	}

	/**
	 * Retourne un tableau d'instances d'une classe donnée correspondant aux enregistrements sélectionnés. Une requête SELECT devrait avoir été exécutée avant d'invoquer cette méthode.
	 *
	 * @param string $className Classe donnée.
	 * @return array Tableau d'instances.
	 */
	public function findAll(string $className = 'stdClass'): array
	{
		// Si pas de recordset, retourner un tableau vide.
		if (!$this->stmt)
			return [];
		// Sinon, exploiter le recordset et retourner un tableau d'instances.
		$this->stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
		return $this->stmt->fetchAll();
	}

	/**
	 * Retourne le premier des enregistrements du recordset sous la forme d'une instance d'une classe donnée. Une requête SELECT devrait avoir été exécutée avant d'invoquer cette méthode.
	 *
	 * @param string $className Classe donnée.
	 * @return object|null Instance ou null si aucun enregistrement dans le recordset.
	 */
	public function findOne(string $className = 'stdClass'): ?object
	{
		// Si pas de recordset, retourner null.
		if (!$this->stmt)
			return null;
		// Sinon, exploiter le recordset et retourner la première instance (ou null si aucun enregistrement).
		$this->stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
		return $this->stmt->fetch() ?: null;
	}

	/**
	 * Hydrate une instance donnée avec le premier enregistrement présent dans le recordset. Une requête SELECT devrait avoir été exécutée avant d'invoquer cette méthode.
	 *
	 * @param object $obj Instance donnée.
	 * @return boolean True ou false selon que l'hydratation a réussi ou pas.
	 */
	public function into(object $obj): bool
	{
		// Si pas de recordset, alors retourner false.
		if (!$this->stmt)
			return false;
		// Sinon, exploiter le recordet et hydrater l'instance.
		$this->stmt->setFetchMode(PDO::FETCH_INTO, $obj);
		return (bool) $this->stmt->fetch();
	}

	/**
	 * Retourne la dernière PK auto-incrémentée.
	 *
	 * @return int PK.
	 */
	public function pk(): int
	{
		return (int) $this->db->lastInsertId();
	}

	/**
	 * Démarre une transaction.
	 *
	 * @return self this pour chaînage.
	 */
	public function start(): self
	{
		$this->db->beginTransaction();
		return $this;
	}

	/**
	 * Définit un point de restauration dans la transaction en cours.
	 *
	 * @param string $label Nom du point de restauration.
	 * @return self this pour chaînage.
	 */
	public function savepoint(string $label): self
	{
		$q = "SAVEPOINT {$label}";
		return $this->xeq($q);
	}

	/**
	 * Effectue un rollback (au point de restauration donné ou au départ si absent) dans la transaction en cours.
	 *
	 * @param string $label Nom du point de restauration.
	 * @return self this pour chaînage.
	 */
	public function rollback(?string $label = null): self
	{
		$q = "ROLLBACK";
		if ($label)
			$q .= " TO {$label}";
		return $this->xeq($q);
	}

	/**
	 * Valide la transaction en cours.
	 *
	 * @return self this pour chaînage.
	 */
	public function commit(): self
	{
		$this->db->commit();
		return $this;
	}
}
