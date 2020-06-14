<?php

declare(strict_types=1);

namespace peps\core;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Implémentation de la persistance ORM en DB via DBAL. Les classes entité DEVRAIENT étendre cette classe.
 ***********************************************************
 * Règles à respecter pour profiter de cette implémentation.
 * Sinon, redéfinir ses méthodes dans les classes entités.
 ***********************************************************
 * Tables nommées selon cet exemple: classe 'TrucChose', table 'trucChose'.
 * PK auto-incrémentée nommée selon cet exemple: table 'trucChose', PK 'idTrucChose'.
 * Chaque colonne correspond à une propriété PUBLIC du même nom. Les autres propriétés NE sont PAS PUBLIC.
 * Si une propriété 'trucChose' est inaccessible, la méthode 'getTrucChose()' sera invoquée si elle existe. Sinon, null sera retourné.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
class ORMDB implements ORM
{
	/**
	 * Constructeur privé. Les classes entité enfants DOIVENT avoir un constructeur public.
	 */
	private function __construct()
	{
	}

	/**
	 * Hydrate l'entité depuis la DB. La PK doit être renseignée.
	 *
	 * @return boolean True ou false selon que l'hydratation a réussi ou non.
	 */
	public function hydrate(): bool
	{
		// Récupérer le nom de la classe de $this pour en déduire le nom de la table.
		$objClass = new ReflectionClass($this);
		$shortClassName = $objClass->getShortName();
		$tableName = lcfirst($shortClassName);
		// Récupérer le nom de la PK déduit du nom de la classe.
		$pkName = "id{$shortClassName}";
		// Si PK non renseignée, retourner false.
		if (!$this->$pkName)
			return false;
		// Exécuter la requête et hydrater $this.
		$q = "SELECT * FROM {$tableName} WHERE {$pkName} = :id";
		$params = [':id' => $this->$pkName];
		return DBAL::getDB()->xeq($q, $params)->into($this);
	}

	/**
	 * Persiste l'entité dans la DB.
	 *
	 * @return boolean True systématiquement.
	 */
	public function persist(): bool
	{
		// Récupérer le nom de la classe de $this pour en déduire le nom de la table.
		$objClass = new ReflectionClass($this);
		$shortClassName = $objClass->getShortName();
		$tableName = lcfirst($shortClassName);
		// Récupérer le nom de la PK déduit du nom de la classe.
		$pkName = "id{$shortClassName}";
		// Récupérer le tableau des propriétés publiques de la classe.
		$properties = $objClass->getProperties(ReflectionProperty::IS_PUBLIC);
		// Initialiser les chaînes et les paramètres nécessaires pour construire la requête SQL.
		$strUpdate = $strInsert = '';
		$params = [];
		// Pour chaque propriété publique de la classe, récupérer son nom pour construire les chaînes et les paramètres.
		foreach ($properties as $property) {
			$propertyName = $property->getName();
			$strInsert .= ",:{$propertyName}";
			$strUpdate .= ",{$propertyName}=:{$propertyName}";
			$params[":{$propertyName}"] = $this->$propertyName;
		}
		// Supprimer la virgule de début des 2 chaînes.
		$strInsert = mb_substr($strInsert, 1);
		$strUpdate = mb_substr($strUpdate, 1);
		// Définir la requête INSERT ou UPDATE avec ses paramètres.
		$q = $this->$pkName ? "UPDATE {$tableName} SET {$strUpdate} WHERE {$pkName} = {$this->$pkName}" : "INSERT INTO {$tableName} VALUES({$strInsert})";
		DBAL::getDB()->xeq($q, $params);
		// Si INSERT, récupérer la PK auto-incrémentée.
		$this->$pkName = $this->$pkName ?: DBAL::getDB()->pk();
		// Retourner systématiquement true.
		return true;
	}

	/**
	 * Supprime l'entité de la DB. La PK doit être renseignée.
	 *
	 * @return boolean True ou false selon que la suppression a réussi ou non.
	 */
	public function remove(): bool
	{
		// Récupérer le nom de la classe de $this pour en déduire le nom de la table.
		$objClass = new ReflectionClass($this);
		$shortClassName = $objClass->getShortName();
		$tableName = lcfirst($shortClassName);
		// Récupérer le nom de la PK déduit du nom de la classe.
		$pkName = "id{$shortClassName}";
		// Si PK non renseigné, retourner false.
		if (!$this->$pkName)
			return false;
		// Exécuter la requête et retourner la conclusion.
		$q = "DELETE FROM {$tableName} WHERE {$pkName} = :id";
		$params = [':id' => $this->$pkName];
		return (bool) DBAL::getDB()->xeq($q, $params)->nb();
	}

	/**
	 * Sélectionne des entités correspondant aux critères dans la DB et retourne un tableau d'instances.
	 * 
	 * @param array $filters Tableau associatif de filtres d'égalité reliés par 'ET' sous la forme 'champ' => 'valeur'. Exemple: ['name' => 'truc', 'idCategory' => 3].
	 * @param array $sortKeys Tableau associatif de clés de tri sous la forme 'champ' => 'sens' avec  'ASC' ou 'DESC' pour le sens. Exemple: ['name' => 'DESC', 'price' => 'ASC'].
	 * @param string $limit Limite de la sélection. Exemple: '2,3'.
	 * @return ORM[] Tableau d'instances.
	 */
	public static function findAllBy(array $filters = [], array $sortKeys = [], string $limit = ''): array
	{
		// Récupérer le nom de la classe statique pour en déduire le nom de la table.
		$objClass = new ReflectionClass(static::class);
		$shortClassName = $objClass->getShortName();
		$tableName = lcfirst($shortClassName);
		// Commencer la requête.
		$q = "SELECT * FROM {$tableName}";
		$params = [];
		// Si filtres, construire la clause WHERE.
		if ($filters) {
			$strWhere = '';
			foreach ($filters as $col => $val) {
				$strWhere .= " AND {$col}=:{$col}";
				$params[":{$col}"] = $val;
			}
			$strWhere = mb_substr($strWhere, 5);
			$q .= " WHERE {$strWhere}";
		}
		// Si clés de tri, construire la clause ORDER BY.
		if ($sortKeys) {
			$strOrderBy = '';
			foreach ($sortKeys as $col => $sortOrder)
				$strOrderBy .= ",{$col} {$sortOrder}";
			$strOrderBy = mb_substr($strOrderBy, 1);
			$q .= " ORDER BY {$strOrderBy}";
		}
		// Si limite, construire la clause LIMIT.
		if ($limit)
			$q .= " LIMIT {$limit}";
		// Exécuter la requête et retourner les instances.
		return DBAL::getDB()->xeq($q, $params)->findAll(static::class);
	}

	/**
	 * Sélectionne une entité correspondant aux critères dans la DB et retourne une instance ou null si aucune correspondance.
	 * 
	 * @param array $filters Tableau associatif de filtres d'égalité reliés par 'ET' sous la forme 'champ' => 'valeur'. Exemple: ['name' => 'truc', 'idCategory' => 3].
	 * @return ORM Instance ou null si aucune entité correspondante.
	 */
	public static function findOneBy(array $filters = []): ?ORM
	{
		return self::findAllBy($filters, [], '1')[0] ?? null;
	}

	/**
	 * Méthode get magique. Invoque $this->get{propertyName}() si existante.
	 *
	 * @param string $propertyName Nom de la propriété.
	 * @return mixed|null Retourne le retour de l'invocation de la méthode ou null si méthode inexistante.
	 */
	public function __get(string $propertyName)
	{
		// Récupérer le nom de la classe de $this.
		$objClass = new ReflectionClass($this);
		// Construire le nom de la méthode à invoquer à partir de $propertyName.
		$methodName = 'get' . ucfirst($propertyName);
		// Si la méthode n'existe pas dans la classe, retourner null.
		try {
			$objClass->getMethod($methodName);
		} catch (ReflectionException $e) {
			return null;
		}
		// Sinon, retourner le retour de la méthode.
		return $this->$methodName();
	}
}
