<?php

declare(strict_types=1);

namespace entities;

use peps\core\DBAL;
use peps\core\ORMDB;

/**
 * Entité Boat.
 * 
 * @see DBAL
 * @see ORMDB
 */
class Boat extends ORMDB
{
    /**
     * Id.
     */
    public ?int $idBoat = null;

    /**
     * Id du joueur.
     */
    public ?int $idUser = null;

    /**
     * Nom du bateau.
     */
    public ?string $name = null;

    /**
     * Positions du bateau sur la grille de jeu.
     */
    public ?string $positions = null;

    /**
     * Direction du bateau.
     */
    public ?int $direction = null;

    /**
     * Longueur du bateau.
     */
    public int $count = 0;

    /**
     * User.
     */
    private ?User $user = null;

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
     * Supprime tous les bateaux associés à l'idUser.
     *
     * @return void
     */
    public function deleteBoats(): void
    {
        $q = "DELETE FROM boat WHERE idUser = :idUser";
        $params = [':idUser' => $this->idUser];
        DBAL::getDB()->xeq($q, $params);
    }

    /**
     * Supprime un bateau en fonction de son nom associés à l'idUser.
     *
     * @return void
     */
    public function deleteBoat(): void
    {
        $q = "DELETE FROM boat WHERE idUser = :idUser AND name = :name";
        $params = [':idUser' => $this->idUser, ':name' => $this->name];
        DBAL::getDB()->xeq($q, $params);
    }
}
