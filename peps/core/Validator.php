<?php

declare(strict_types=1);

namespace peps\core;

/**
 * DEVRAIT être implémentée par les classes entité pour valider les données qu'elles contiennent avant persistance.
 * 
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
interface Validator
{
	/**
	 * Vérifie si l'entité contient des données valides avant persistance.
	 * 
	 * @param array<mixed>|null &$errors Tableau des erreurs.
	 * @return boolean True si données valides, false sinon.
	 */
	public function validate(?array &$errors = null): bool;
}
