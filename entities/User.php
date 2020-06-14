<?php

declare(strict_types=1);

namespace entities;

use DateInterval;
use DateTime;
use peps\core\DBAL;
use peps\core\ORMDB;
use peps\core\UserLoggable;
use peps\core\Validator;

/**
 * Entité User.
 * 
 * @see DBAL
 * @see ORMDB
 * @see UserLoggable
 * @author Gilles Vanderstraeten <contact@gillesvds.com>
 * @copyright 2020 Gilles Vanderstraeten
 */
class User extends ORMDB implements UserLoggable
{
	/**
	 * Id.
	 */
	public ?int $idUser = null;

	/**
	 * Identifiant de connexion.
	 */
	public ?string $email = null;

	/**
	 * Mot de passe de connexion (toujours chiffré).
	 */
	public ?string $password = null;

	/**
	 * Role: 0 = joueur, 1 = admin.
	 * Changé manuellement dans la base.
	 */
	public int $role = 0;

	/**
	 * name. Pseudo du joueur
	 */
	public ?string $name = null;

	/**
	 * score. Score du joueur
	 */
	public int $score = 0;

	/**
	 * Avatar. Avatar du joueur
	 */
	public string $avatar = "1";

	/**
	 * restoreCode. Code de restauration du mot de passe.
	 */
	public ?string $restoreCode = null;

	/**
	 * timeRestore. Délai de validité du code de restauration.
	 */
	public ?string $timeRestore = null;

	/**
	 * Constante d'erreur si l'email existe déjà en BDD.
	 */
	public const ERR_EMAIL_ALREADY_EXISTS = "Cet email existe déjà";

	/**
	 * Constante d'erreur si le nom existe déjà en BDD.
	 */
	public const ERR_NAME_ALREADY_EXISTS = "Ce pseudo existe déjà";

	/**
	 * Constante d'erreur si l'email existe déjà en BDD.
	 */
	public const ERR_INVALID_EMAIL = "Cet email est invalide";

	/**
	 * Constante d'erreur si le mot de passe est invalide.
	 */
	public const ERR_INVALID_PASSWORD = "Le mot de passe est invalide";

	/**
	 * Constante d'erreur si le nouveau de passe est invalide.
	 */
	public const ERR_INVALID_NEWPASSWORD = "Le nouveau mot de passe est invalide";

	/**
	 * Constante d'erreur si l'avatar est invalide.
	 */
	public const ERR_INVALID_AVATAR = "L'avatar est invalide";

	/**
	 * Constante d'erreur si le name est invalide.
	 */
	public const ERR_INVALID_NAME = "Le pseudo est invalide";

	/**
	 * Constante d'erreur si le name est invalide.
	 */
	public const ERR_INVALID_RESTORECODE = "Le code de restauration est invalide";

	/**
	 * Constante d'erreur si le name est invalide.
	 */
	public const ERR_NOT_EQUAL_PASSWORD = "Les mots de passe doivent être identiques";

	/**
	 * Constante d'erreur si le name est invalide.
	 */
	public const ERR_INVALID_SESSION = "Vous n'êtes pas connecté";

	/**
	 * Constante d'erreur si le name ou le password est invalide.
	 */
	public const ERR_INVALID_LOGIN = "Identifiant ou mot de passe absent(s) ou invalide(s).";

	/**
	 * Instance de l'utilisateur en session. En cache pour lazy loading.
	 */
	private static ?self $userSession = null;


	/**
	 * Constructeur public nécessaire.
	 */
	public function __construct()
	{
	}

	/**
	 * Tente de loguer l'utilisateur.
	 *
	 * @return boolean True ou false selon que le login a réussi ou non.
	 */
	public function login(): bool
	{
		// Si email ou password non renseignées, retourner false.
		if (!$this->email || !$this->password)
			return false;
		// Sélectionner la PK et le mot de passe (crypté) de l'éventuel utilisateur correspondant dans la DB.
		$q = "SELECT idUser, password FROM user WHERE email = :email";
		$params = [':email' => $this->email];
		// Si introuvable, retourner false.
		if (!$obj = DBAL::getDB()->xeq($q, $params)->findOne())
			return false;
		// Si trouvé mais mot de passe incorrect, retourner false.
		if (!password_verify($this->password, $obj->password))
			return false;
		// Si OK, incrire l'utilisateur dans la session et retourner true.
		$_SESSION['idUser'] = $obj->idUser;
		return true;
	}

	/**
	 * Retourne l'utilisateur en session ou null si aucun. Lazy loading.
	 *
	 * @return self|null Utilisateur en session.
	 */
	public static function getUserSession(): ?self
	{
		// Si pas en cache, créer et hydrater l'utilisateur en session.
		if (!self::$userSession) {
			// Créer une instance.
			$user = new self();
			// Définir son idUser.
			$user->idUser = $_SESSION['idUser'] ?? null;
			// Si hydratation réussie, stocker l'instance dans le cache.
			self::$userSession = $user->hydrate() ? $user : null;
		}
		// Retourner l'utilisateur en session.
		return self::$userSession;
	}

	/**
	 * Vérifie si l'email de l'utilisateur existe déjà dans la DB (sans tenir compte de l'utilisateur lui-même).
	 *
	 * @return boolean True si l'email existe déjà, false sinon.
	 */
	private function emailExists(): bool
	{
		$q = "SELECT * FROM user WHERE email = :email AND idUser != :idUser";
		$params = [':email' => $this->email, ':idUser' => $this->idUser ?: 0];
		return (bool) DBAL::getDB()->xeq($q, $params)->nb();
	}

	/**
	 * Vérifie si le nom de l'utilisateur existe déjà dans la DB (sans tenir compte de l'utilisateur lui-même).
	 *
	 * @return boolean True si le nom existe déjà, false sinon.
	 */
	private function nameExists(): bool
	{
		$q = "SELECT * FROM user WHERE name = :name AND idUser != :idUser";
		$params = [':name' => $this->name, ':idUser' => $this->idUser ?: 0];
		return (bool) DBAL::getDB()->xeq($q, $params)->nb();
	}

	/**
	 * Création d'un nouveau compte.
	 *
	 */
	public function createAccount(): void
	{
		$id = DBAL::getDB()->pk();
		$this->name = 'Gamer' . $id;
		$_SESSION['idUser'] = $id;
	}

	/**
	 * Vérifie si le mot de passe a une correspondance en BDD.
	 *
	 * @return boolean True si le mot de passe est valide, false sinon.
	 */
	public function validatePassword(?array &$errors = null): bool
	{
		$q = "SELECT * FROM user WHERE idUser = :idUser";
		$params = [':idUser' => $this->idUser];
		if (!$obj = DBAL::getDB()->xeq($q, $params)->findOne())
			return false;
		if (!password_verify($this->password, $obj->password)) {
			$errors[] = self::ERR_INVALID_PASSWORD;
			return false;
		}
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		return true;
	}

	/**
	 * Valide ou non l'inscription d'un utilisateur.
	 *
	 * @param object|null $obj Objet dans lequel on mettra le message pour l'affichage.
	 * @return boolean
	 */
	public function validateCreateAccount(?object &$obj = null): bool
	{
		// Vérifier l'email.
		if (!$this->email || mb_strlen($this->email) > 45) {
			$obj->message = self::ERR_INVALID_EMAIL;
			return false;
		}
		// Vérifier l'unicité de l'email en DB.
		if ($this->emailExists()) {
			$obj->message = self::ERR_EMAIL_ALREADY_EXISTS;
			return false;
		}
		// Vérifier l'unicité du nom en DB.
		if ($this->nameExists()) {
			$obj->message = self::ERR_NAME_ALREADY_EXISTS;
			return false;
		}
		if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#', $this->password)) {
			$obj->message = self::ERR_INVALID_PASSWORD;
			return false;
		}
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
		// Si valide, retourner true.
		return true;
	}


	/**
	 * Vérifie les données pour la mise à jour.
	 */
	public function validateUpdate($newPassword, ?array &$errors = null): bool
	{
		//Vérifier l'email
		if (!$this->email || mb_strlen($this->email) > 45) {
			$errors[] = self::ERR_INVALID_EMAIL;
			return false;
		}
		// Vérifier l'unicité de l'email en DB.
		if ($this->emailExists()) {
			$errors[] = self::ERR_EMAIL_ALREADY_EXISTS;
			return false;
		}
		// Vérifier l'unicité du nom en DB.
		if ($this->nameExists()) {
			$errors[] = self::ERR_NAME_ALREADY_EXISTS;
			return false;
		}
		// Vérifier l'avatar.
		if (!$this->avatar || (int) $this->avatar < 0) {
			$errors[] = self::ERR_INVALID_AVATAR;
			return false;
		}
		// Vérifier le pseudo.
		if (!$this->name || mb_strlen($this->name) > 45) {
			$errors[] = self::ERR_INVALID_NAME;
			return false;
		}
		if ($newPassword) {
			if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#', $newPassword)) {
				$errors[] = self::ERR_INVALID_NEWPASSWORD;
				return false;
			}
			$this->password = password_hash($newPassword, PASSWORD_DEFAULT);
		}
		// Si valide, retourner true.
		return true;
	}

	/**
	 * Vérifie si l'email de l'utilisateur existe déjà dans la DB (sans tenir compte de l'utilisateur lui-même).
	 *
	 * @return boolean True si l'email existe déjà, false sinon.
	 */
	public function delete(): bool
	{
		$q = "DELETE FROM user WHERE idUser = :idUser";
		$params = [':idUser' => $this->idUser];
		return (bool) DBAL::getDB()->xeq($q, $params)->nb();
	}

	/**
	 * Retourne la liste de tous les utilisateurs.
	 *
	 */
	public static function getAll(): array
	{
		$q = "SELECT * FROM user ORDER BY score DESC";
		return DBAL::getDB()->xeq($q)->findAll();
	}

	/**
	 * Vérifie le mail et crée les données de récupération du mot de passe.
	 */
	public function createRecupMdp(?array &$errors = null): bool
	{
		// Vérifier l'email.
		if (!$this->email || mb_strlen($this->email) > 45 || !$this->emailExists()) {
			$errors[] = self::ERR_INVALID_EMAIL;
			return false;
		}
		// Vérifier l'unicité du nom en DB.
		if ($this->nameExists()) {
			$errors[] = self::ERR_NAME_ALREADY_EXISTS;
			return false;
		}
		$this->restoreCode = uniqid('');
		//On définit une durée max pour récupérer le mot de 24h.
		$timeEnd = new DateTime('now');
		$timeEnd = $timeEnd->add(new DateInterval('PT' . 1440 . 'M'));
		$timeEnd = $timeEnd->format('Y-m-d H:i');
		$this->timeRestore = $timeEnd;
		$q = "UPDATE user SET restoreCode = :restoreCode, timeRestore = :timeRestore WHERE email = :email";
		$params = [':restoreCode' => $this->restoreCode, 'timeRestore' => $this->timeRestore, ':email' => $this->email];
		DBAL::getDB()->xeq($q, $params);
		// retourne true si tout c'est bien passé.
		return true;
	}

	/**
	 * Vérifie les données de récupération du mot de passe.
	 */
	public function validateRestoreCode(?object $obj = null, ?array &$errors = null): bool
	{
		// Vérifier l'email.
		if (!$this->email || mb_strlen($this->email) > 45 || !$this->emailExists()) {
			$errors[] = self::ERR_INVALID_EMAIL;
			return false;
		}
		//Récupérer les données de l'utilisateur depuis son mail, sinon retourner false.
		if (!$user = User::findOneBy(['email' => $this->email])) {
			$errors[] = self::ERR_INVALID_EMAIL;
			return false;
		}
		if ($obj->password && $obj->newPassword) {
			if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#', $obj->password)) {
				$errors[] = self::ERR_INVALID_PASSWORD;
				return false;
			}
			if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#', $obj->newPassword)) {
				$errors[] = self::ERR_INVALID_NEWPASSWORD;
				return false;
			}
			if ($obj->password !== $obj->newPassword) {
				$errors[] = self::ERR_NOT_EQUAL_PASSWORD;
				return false;
			}
		}
		$time = date('Y-m-d H:i', time());
		if ($time > $user->timeRestore || $obj->restoreCode !== $user->restoreCode) {
			$errors[] = self::ERR_INVALID_RESTORECODE;
			return false;
		}
		$user->password = password_hash($obj->password, PASSWORD_DEFAULT);
		$user->persist();
		return true;
	}
}
