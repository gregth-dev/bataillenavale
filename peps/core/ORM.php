<?php

declare(strict_types=1);

namespace peps\core;

/**
 * Abstraction ORM (Object Relational Mapping) de la persistance pour les entités.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
interface ORM
{
	/**
	 * Hydrate l'entité depuis le système de stockage (DB ou autre).
	 *
	 * @return boolean True ou false selon que l'hydratation a réussi ou non.
	 */
	public function hydrate(): bool;

	/**
	 * Persiste l'entité dans le système de stockage (DB ou autre).
	 *
	 * @return boolean True ou false selon que la persistance a réussi ou non.
	 */
	public function persist(): bool;

	/**
	 * Supprime l'entité du système de stockage (DB ou autre).
	 *
	 * @return boolean True systématiquement.
	 */
	public function remove(): bool;

	/**
	 * Sélectionne des entités correspondant aux critères dans le système de stockage (DB ou autre) et retourne un tableau d'instances.
	 * 
	 * @param array $filters Tableau associatif de filtres d'égalité reliés par 'ET' sous la forme 'champ' => 'valeur'. Exemple: ['name' => 'truc', 'idCategory' => 3].
	 * @param array $sortKeys Tableau associatif de clés de tri sous la forme 'champ' => 'sens' avec  'ASC' ou 'DESC' pour le sens. Exemple: ['name' => 'DESC', 'price' => 'ASC'].
	 * @param string $limit Limite de la sélection. Exemple: '2,3'.
	 * @return ORM[] Tableau d'instances.
	 */
	public static function findAllBy(array $filters = [], array $sortKeys = [], string $limit = ''): array;

	/**
	 * Sélectionne une entité correspondant aux critères dans le système de stockage (DB ou autre) et retourne une instance ou null si aucune correspondance.
	 * 
	 * @param array $filters Tableau associatif de filtres d'égalité reliés par 'ET' sous la forme 'champ' => 'valeur'. Exemple: ['name' => 'truc', 'idCategory' => 3].
	 * @return ORM Instance ou null si aucune entité correspondante.
	 */
	public static function findOneBy(array $filters = []): ?ORM;
}
