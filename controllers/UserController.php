<?php

declare(strict_types=1);

namespace controllers;

use captcha\Captcha;
use peps\core\Cfg;
use entities\Session;
use entities\User;
use peps\image\Image;
use peps\image\ImageJPEGException;
use peps\image\ImagePNG;
use peps\core\Router;
use peps\image\ImageJpeg;
use peps\image\ImagePngException;
use peps\upload\Upload;
use peps\upload\UploadException;
use stdClass;

/**
 * Contrôle la connexion/déconnexion des utilisateurs.
 * 
 * @see User
 * @see Router
 * 
 */
final class UserController
{

    /**
     * Affiche le formulaire de connexion.
     * GET user/connexion
     * @return void
     */
    public static function connexion(): void
    {
        Router::render(Cfg::get('connexion'));
    }

    /**
     * Affiche le formulaire d'inscription.
     * GET user/inscription
     * @return void
     */
    public static function inscription(): void
    {
        Router::render(Cfg::get('inscription'));
    }

    /**
     * Connecte l'utilisateur si possible puis redirige.
     * POST user/login
     * @return void
     */
    public static function login(): void
    {
        // Prévoir le tableau des messages d'erreur.
        $errors = [];
        // Instancier un utilisateur.
        $user = new User();
        // Récupérer les données POST.
        $user->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null;
        $user->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) ?: null;
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING) ?: '';
        // On test le captcha, on renvoie une erreur s'il est incorrect.
        if (!Captcha::validateCaptcha($token)) {
            $errors[] = User::ERR_INVALID_LOGIN;
            Router::render(Cfg::get('connexion'), ['errors' => $errors, 'data' => 'error']);
        }
        // Si données POST valides et login OK, rediriger vers l'accueil.
        if ($user->email && $user->password && $user->login())
            Router::redirect('/battle/gameOne');
        // Sinon, afficher de nouveau le formulaire avec le message d'erreur.
        $errors[] = User::ERR_INVALID_LOGIN;
        Router::render(Cfg::get('connexion'), ['errors' => $errors, 'data' => 'error']);
    }

    /**
     * Inscrit un utilisateur puis redirige.
     * POST user/inscription
     * @return void
     */
    public static function create(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null;
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) ?: null;
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING) ?: '';
        $postData = [$password, $email];
        // On renvoie le champ qui vaut null, s'il y en a.
        foreach ($postData as $data => $value) {
            if (!$value) {
                $obj->field = $data;
                Router::json(json_encode($obj));
            }
        }
        // On test le captcha, on renvoie une erreur s'il est incorrect.
        if (!Captcha::validateCaptcha($token)) {
            $obj->errorCaptcha = false;
            Router::json(json_encode($obj));
        }
        // Instancier un utilisateur.
        $user = new User();
        // Récupérer les données POST.
        $user->email = $email;
        $user->password = $password;
        // Si données POST valides et login OK, rediriger vers l'accueil.
        if ($user->validateCreateAccount($obj)) {
            $user->persist();
            $user->createAccount();
            $user->persist();
            $obj->value = true;
            Router::json(json_encode($obj));
        }
        // Sinon, afficher de nouveau le formulaire avec le message d'erreur.
        Router::json(json_encode($obj));
    }

    /**
     * Déconnecte l'utilisateur puis redirige.
     * GET user/logout
     * @return void
     */
    public static function logout(): void
    {
        // Détruire la session.
        session_destroy();
        // Rediriger vers la vue connexion.
        $errors = [];
        $errors[] = 'Vous êtes déconnecté';
        Router::redirect('/user/connexion', ['errors' => $errors, 'data' => 'error']);
    }

    /**
     * Affiche la vue profil de l'utilisateur.
     * GET user/profil
     * @return void
     */
    public static function profil(): void
    {
        $errors = [];
        if (!$user = User::getUserSession()) {
            $errors[] = USER::ERR_INVALID_SESSION;
            Router::render(Cfg::get('connexion'), ['errors' => $errors, 'data' => 'error']);
        }
        $directory = Cfg::get('rootDir') . '/assets/img/avatars/';
        $avatarsList = array_diff(scandir($directory), array('..', '.'));
        Router::render(Cfg::get('profil'), ['user' => $user, 'avatarsList' => $avatarsList]);
    }

    /**
     * Met à jour le profil de l'utilisateur.
     * POST user/update
     * @return void
     */
    public static function update(): void
    {
        $errors = [];
        $obj = new stdClass();
        $obj->value = false;
        if (!$user = User::getUserSession()) {
            $obj->message = USER::ERR_INVALID_SESSION;
            Router::json(JSON_encode($obj));
        }
        //On récupère les données du formulaire et on les affecte.
        $user->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) ?: '';
        $user->avatar = filter_input(INPUT_POST, 'avatar', FILTER_SANITIZE_STRING) ?: null;
        $user->name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?: null;
        $user->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null;
        $newPassword = filter_input(INPUT_POST, 'newPassword', FILTER_SANITIZE_STRING) ?: '';
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING) ?: '';
        //On vérifie le mot de passe et les données.
        if (!$user->validatePassword($errors) || !$user->validateUpdate($newPassword, $errors)) {
            $obj->message = $errors;
            Router::json(JSON_encode($obj));
        }
        // On test le captcha, on renvoie une erreur s'il est incorrect.
        if (!Captcha::validateCaptcha($token)) {
            $obj->errorCaptcha = false;
            Router::json(json_encode($obj));
        }
        $user->persist();
        //On ne renvoie pas les données complètes de l'utilisateur.
        $obj->message = 'Profil mis à jour';
        $obj->avatar = $user->avatar;
        $obj->name = $user->name;
        $obj->email = $user->email;
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }

    /**
     * Supprime le compte de l'utilisateur.(Depuis le compte utilisateur)
     * POST user/deleteProfil
     * @return void
     */
    public static function deleteProfil(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$user = User::getUserSession()) {
            $obj->message = 'Vous n\'êtes pas connecté';
            Router::json(JSON_encode($obj));
        }
        //On récupère les données du formulaire et on les affecte.
        $user->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) ?: '';
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING) ?: '';
        //On vérifie le mot de passe.
        if (!$user->validatePassword($errors)) {
            $obj->message = $errors;
            Router::json(JSON_encode($obj));
        }
        // On test le captcha, on renvoie une erreur s'il est incorrect.
        if (!Captcha::validateCaptcha($token)) {
            $obj->errorCaptcha = false;
            Router::json(json_encode($obj));
        }
        $user->delete();
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }

    /**
     * Affiche la vue des meilleurs scores.
     * GET user/bestscore
     * @return void
     */
    public static function bestscore(): void
    {
        $users = User::getAll();
        Router::render(Cfg::get('bestscore'), ['users' => $users, 'i' => 1]);
    }

    /**
     * Affiche la vue pour la récupération du mot de passe.
     * GET user/recuperation
     * @return void
     */
    public static function recuperation(): void
    {
        if ($user = User::getUserSession())
            Router::render(Cfg::get('profil'), ['user' => $user]);
        Router::render(Cfg::get('recuperation'));
    }

    /**
     * Envoie un mail de récupération sur la boite mail de l'utilisateur.
     * POST user/recupmdp
     * @return void
     */
    public static function recupMdp(): void
    {
        //Prévoir le tableau des messages d'erreur.
        $errors = [];
        //Instancier un utilisateur.
        $user = new User();
        //Récupérer les données POST.
        $user->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null;
        //On vérifie les donnée et on crée les données de récupération
        if (!$user->createRecupMdp($errors))
            Router::render(Cfg::get('recuperation'), ['errors' => $errors, 'data' => 'error']);
        //Si valide, envoyer le mail et rédiriger vers connexion.
        $to = $user->email;
        $subject = "Bataille Navale - Reset mot de passe";
        $header = "Vous recevez ce mail car vous avez fait une demande de récupération de mot de passe.";
        $message = "Voici le lien pour reset votre password
		Ce code est valable 24H.
        Cliquez sur le lien suivant : 
        http://gregorythorel.alwaysdata.net/user/resetmdp/$user->restoreCode";
        $mail = mail($to, $subject, $message, $header);
        if (!$mail) {
            $errors[] = 'Une erreur est survenue, veuillez recommencer.';
            Router::render(Cfg::get('recuperation'), ['errors' => $errors, 'data' => 'error']);
        }
        $errors[] = 'Vérifiez votre boite mail';
        Router::render(Cfg::get('recuperation'), ['errors' => $errors, 'data' => 'success']);
    }

    /**
     * Affiche la vue de restauration du mot de passe.
     * POST user/resetmdp
     * @param array $params Contient la clé de restauration.
     * @return void
     */
    public static function resetmdp(array $params): void
    {
        if (!$user = User::getUserSession())
            Router::redirect('/');
        else {
            $restoreCode = filter_var($params['restoreCode'], FILTER_SANITIZE_STRING);
            Router::render(Cfg::get('resetmdp'), ['restoreCode' => $restoreCode, 'titleOption' => 'Restaurer votre mot de passe']);
        }
    }

    /**
     * Récupère les données saisies par l'utilisateur, les valide et restaure le mot de passe.
     * POST user/restoremdp
     * @return void
     */
    public static function restoremdp(): void
    {
        // Prévoir le tableau des messages d'erreur.
        $errors = [];
        // Instancier un utilisateur.
        $user = new User();
        // Récupérer les données POST.
        $user->email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: null;
        // Instancier un nouvel objet pour stocker et traiter les données post.
        $obj = new stdClass();
        $obj->password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) ?: null;
        $obj->newPassword = filter_input(INPUT_POST, 'newPassword', FILTER_SANITIZE_STRING) ?: null;
        $obj->restoreCode = filter_input(INPUT_POST, 'restoreCode', FILTER_SANITIZE_STRING) ?: null;
        // On vérifie les données.
        if (!$user->validateRestoreCode($obj, $errors))
            Router::render(Cfg::get('resetmdp'), ['errors' => $errors, 'data' => 'error', 'restoreCode' => $obj->restoreCode]);
        $errors[] = 'Mot de passe restauré';
        Router::render(Cfg::get('connexion'), ['errors' => $errors, 'data' => 'success']);
    }

    /**
     * Affiche la vue de gestion des utilisateurs pour l'administrateur.
     * GET user/updateUsers
     * @return void
     */
    public static function updateUsers(): void
    {
        if (!User::getUserSession()->role)
            self::logout();
        $users = User::getAll();
        Router::render(Cfg::get('updateusers'), ['users' => $users]);
    }

    /**
     * Supprime le compte de l'utilisateur.(Depuis le compte administrateur)
     * POST user/deleteUser
     * @return void
     */
    public static function deleteUser(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        // On vérifie que l'utilisateur soit bien administrateur.
        if (!User::getUserSession()->role)
            self::logout();
        //On récupère les données du formulaire et on les affecte.
        $user = new User();
        $user->idUser = filter_input(INPUT_POST, 'idUser', FILTER_VALIDATE_INT) ?: 0;
        if (!$user->idUser)
            Router::json(JSON_encode($obj));
        if (!$user->delete())
            Router::json(JSON_encode($obj));
        $obj->id = $user->idUser;
        $obj->message = 'Utilisateur supprimé';
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }

    /**
     * Affiche la vue de gestion des avatars.
     * GET user/updateAvatars
     * @return void
     */
    public static function updateAvatars(): void
    {
        // On vérifie que l'utilisateur soit bien administrateur.
        if (!User::getUserSession()->role)
            self::logout();
        $directory = Cfg::get('rootDir') . '/assets/img/avatars/';
        $avatarsList = array_diff(scandir($directory), array('..', '.'));
        Router::render(Cfg::get('updateavatars'), ['avatarsList' => $avatarsList, 'i' => 1]);
    }

    /**
     * Supprime l'avatar.(Depuis le compte administrateur)
     * POST user/deleteAvatar
     * @return void
     */
    public static function deleteAvatar(): void
    {
        // On vérifie que l'utilisateur soit bien administrateur.
        if (!User::getUserSession()->role)
            self::logout();
        $directory = Cfg::get('rootDir') . '/assets/img/avatars/';
        $obj = new stdClass();
        $obj->value = false;
        //On récupère les données du formulaire et on les affecte.
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
        if (!$id)
            Router::json(JSON_encode($obj));
        if (!unlink($directory . 'avatar' . $id . '.png'))
            Router::json(JSON_encode($obj));
        $obj->id = $id;
        $obj->message = 'Avatar supprimé';
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }

    /**
     * Ajoute un avatar.(Depuis le compte administrateur)
     * POST user/avatarSave
     * @return void
     */
    public static function avatarSave(): void
    {
        // On vérifie que l'utilisateur soit bien administrateur.
        if (!User::getUserSession()->role)
            self::logout();
        $errors = [];
        $directory = Cfg::get('rootDir') . '/assets/img/avatars/';
        $avatarsList = array_diff(scandir($directory), array('..', '.'));
        $number = count($avatarsList) + 1;
        try {
            // Traiter l'upload.
            $upload = new Upload('avatar', (array) Cfg::get('imgMimes'));
            // Si image uploadée, la redimensionner.
            if ($upload->mimeType === 'image/png' && $upload->errorCode !== UPLOAD_ERR_NO_FILE) {
                $imagePNG = new ImagePNG($upload->tmpFilePath);
                $imagePNG->copyResize((int) Cfg::get('imgSmallWidth'), (int) Cfg::get('imgSmallHeight'), Cfg::get('rootDir') . "/assets/img/avatars/avatar{$number}.png", Image::COVER);
                $errors[] = "L'avatar a été ajouté avec succès";
            } else if ($upload->mimeType === 'image/jpeg' && $upload->errorCode !== UPLOAD_ERR_NO_FILE) {
                $imagePNG = new ImageJpeg($upload->tmpFilePath);
                $imagePNG->copyResize((int) Cfg::get('imgSmallWidth'), (int) Cfg::get('imgSmallHeight'), Cfg::get('rootDir') . "/assets/img/avatars/avatar{$number}.png", Image::COVER);
                $errors[] = "L'avatar a été ajouté avec succès";
            }
        } catch (UploadException | ImageJPEGException | ImagePngException $e) {
            // Si erreur, l'ajouter au tableau des erreurs.
            $errors[] = $e->getMessage();
        }
        $avatarsListRefresh = array_diff(scandir($directory), array('..', '.'));
        Router::render(Cfg::get('updateavatars'), ['avatarsList' => $avatarsListRefresh, 'i' => 1, 'errors' => $errors, 'data' => 'success']);
    }

    /**
     * Charge la liste des utilisateurs en ligne.
     * @return void
     */
    public static function onlineUsers(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        //Tableau vierge des utilisateurs
        $users = [];
        // on récupère la liste des ID en ligne.
        if (!$idList = Session::getOnline())
            Router::json(JSON_encode($obj));
        foreach ($idList as $idUser) {
            //On crée un utilisateur pour chaque id.
            $user = new User();
            $user->idUser = $idUser;
            $user->hydrate();
            //On insère chaque utilisateur dans le tableau.
            $users[] = ['name' => $user->name, 'avatar' => $user->avatar, 'id' => $user->idUser];
        }
        $obj->users = $users;
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }
}
