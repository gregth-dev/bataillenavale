<?php

declare(strict_types=1);

namespace controllers;

use cfg\CfgApp;
use entities\Battle;
use entities\Boat;
use entities\LaunchBattle;
use entities\Message;
use entities\Session;
use entities\User;
use peps\core\Cfg;
use peps\core\Router;
use stdClass;

final class BattleController
{

    /**
     * Affiche la vue Game 1 seul joueur.
     * @return void
     */
    public static function gameOne(): void
    {
        Router::render(Cfg::get('gameOne'));
    }

    /**
     * Affiche la vue des parties Multijoueur.
     * @return void
     */
    public static function partieMultijoueur(): void
    {
        if (!$user = User::getUserSession()) {
            $errors[] = USER::ERR_INVALID_SESSION;
            Router::render(Cfg::get('partieMultijoueur'), ['errors' => $errors, 'data' => 'error']);
        }
        $battle = new Battle();
        $battle->idUser1 = $user->idUser;
        $battle->deleteBattle();
        Router::render(Cfg::get('partieMultijoueur'));
    }

    /**
     * Enregistre le score de l'utilisateur.
     * POST user/score
     * @return void
     */
    public static function score(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$user = User::getUserSession()) {
            $obj->message = 'Vous n\'êtes pas connecté';
            Router::json(JSON_encode($obj));
        }
        $user->score = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_INT) ?: null;
        if ($user->score > 3050) {
            $obj->message = 'Score impossible';
            Router::json(JSON_encode($obj));
        }
        $user->persist();
        $obj->message = 'Score sauvegardé';
        $obj->value = true;
        Router::json(JSON_encode($obj));
    }

    /**
     * Enregistre en BDD la demande d'une partie multijoueur.
     * @return void
     */
    public static function launchBattle(): void
    {
        //Vérifier si l'utilisateur est connecté, sinon rediriger.
        $obj = new stdClass();
        $obj->value = false;
        $launchBattle = new LaunchBattle();
        if (!$launchBattle->idUser1 = User::getUserSession()->idUser)
            Router::json(json_encode($obj));
        $launchBattle->idUser2 = filter_input(INPUT_POST, 'idUser2', FILTER_VALIDATE_INT) ?: null;
        if ($launchBattle->validate($obj))
            $launchBattle->persist();
        $obj->value = true;
        $obj->id = $launchBattle->idLaunchBattle;
        Router::json(JSON_encode($obj));
    }

    /**
     * Regarde en BDD si le joueur à répondu à l'invitation.
     * @return void
     */
    public static function listenResponse(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        //On écoute si le joueur est le lanceur de l'invitation.
        if ($launchBattle = LaunchBattle::findOneBy(['idUser1' => User::getUserSession()->idUser])) {
            $obj->value = true;
            $obj->id = $launchBattle->idLaunchBattle;
            $obj->from = $launchBattle->user2->name;
            if (LaunchBattle::getTime() > $launchBattle->maxTime)
                $obj->timeOut = true;
            else if ($launchBattle->statut === 1)
                $obj->accept = true;
            else if ($launchBattle->statut === 2)
                $obj->refused = true;
            Router::json(JSON_encode($obj));
        }
        Router::json(JSON_encode($obj));
    }

    /**
     * Regarde en BDD si le joueur à réçu une inivitation à une partie multijoueur.
     * @return void
     */
    public static function listenDemand(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        //On écoute si le joueur est l'invité.
        if ($launchBattle = LaunchBattle::findOneBy(['idUser2' => User::getUserSession()->idUser])) {
            if (!$launchBattle->statut && !$launchBattle->readLaunch) {
                $obj->value = true;
                $obj->id = $launchBattle->idLaunchBattle;
                $obj->from = $launchBattle->user1->name;
                $launchBattle->readLaunch = 1;
                $launchBattle->persist();
                Router::json(JSON_encode($obj));
            }
        }
        Router::json(JSON_encode($obj));
    }

    /**
     * Enregistre en BDD la réponse du joueur qui à réçu l'invitation.
     * @return void
     */
    public static function responseLaunch(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!User::getUserSession())
            Router::json(json_encode($obj));
        $option = filter_input(INPUT_POST, 'option', FILTER_VALIDATE_INT);
        $launchBattle = new LaunchBattle();
        $launchBattle->idLaunchBattle = filter_input(INPUT_POST, 'idLaunchBattle', FILTER_VALIDATE_INT) ?: null;
        if ($option === 3) {
            $launchBattle->remove();
            return;
        }
        if ($launchBattle->hydrate()) {
            switch ($option) {
                case 1:
                    $launchBattle->statut = 1;
                    break;
                case 2:
                    $launchBattle->statut = 2;
                    break;
            }
            $launchBattle->persist();
            $obj->id = $launchBattle->idLaunchBattle;
            $obj->value = true;
            Router::json(JSON_encode($obj));
        }
        Router::json(JSON_encode($obj));
    }

    /**
     * Affiche la vue de la partie multijoueur.
     * @param array|null $params Tableau associatif des paramètres.
     * @return void
     */
    public static function gameMultijoueur(?array $params = null): void
    {
        $errors = [];
        if (!$user = User::getUserSession()) {
            $errors[] = User::ERR_INVALID_SESSION;
            Router::render(Cfg::get('connexion'), ['errors' => $errors, 'data' => 'error']);
        }
        $launchBattle = new LaunchBattle();
        $launchBattle->idLaunchBattle = filter_var($params['idLaunchBattle'], FILTER_VALIDATE_INT) ?: null;
        //Si on ne trouve pas de lancement de partie avec l'id on redirige (redirige également si on rafraîchit la page pendant la partie).
        //Supprime les parties en cours du joueur.
        if (!$launchBattle->hydrate()) {
            if ($battle = Battle::findOneBy(['idUser1' => $user->idUser]))
                $battle->remove();
            Router::redirect('/battle/partieMultijoueur');
        }
        //On supprime les anciens messages privées s'il y en a.
        $message = new Message();
        $message->idUser = $user->idUser;
        $message->deletePrivateMsg();
        //On crée une nouvelle partie multijoueur et on supprime les anciennes s'il y en a.
        $battle = new Battle();
        $battle->idLaunchBattle = $launchBattle->idLaunchBattle;
        $battle->deleteOldBattle();
        $battle->idUser1 = $user->idUser;
        $battle->idUser2 = $user->idUser === $launchBattle->user2->idUser ? $launchBattle->user1->idUser : $launchBattle->user2->idUser;
        $battle->persist();
        //On supprime le lancement de la partie qui n'est plus utile une fois les informations transmises aux joueurs et une fois la battle créée.
        //On s'assure que ce soit le lanceur (dernier arrivé dans la partie) qui supprime le lancement.
        if ($launchBattle->user1->idUser === $user->idUser)
            $launchBattle->remove();
        //On supprime les anciens bateaux du joueur créés lors des parties précédentes.
        $boat = new Boat();
        $boat->idUser = $user->idUser;
        $boat->deleteBoats();
        // On passe titleOption car le dernier élément de l'url est un chiffre aléatoire.
        Router::render(Cfg::get('gameMultijoueur'), ['battle' => $battle, 'titleOption' => 'Jeu Multijoueur']);
    }

    /**
     * Retourne les données de la table battle.
     * @return void
     */
    public static function getBattles(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$battlePlayer1 = Battle::findOneBy(['idUser1' => User::getUserSession()->idUser]))
            Router::json(json_encode($obj));
        // On test si l'adversaire est le bon et s'il est disponible, sinon on quitte.
        if (!($battlePlayer2 = Battle::findOneBy(['idUser1' => $battlePlayer1->idUser2])) || !Session::sessionUserExist($battlePlayer1->idUser2))
            Router::json(json_encode($obj));
        $obj->player1 = $battlePlayer1;
        $obj->player2 = $battlePlayer2;
        $obj->value = true;
        Router::json(json_encode($obj));
    }

    /**
     * Enregistre en BDD la valeur de ready de l'utilisateur.
     * @return void
     */
    public static function postReady(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$battlePlayer = Battle::findOneBy(['idUser1' => User::getUserSession()->idUser]))
            Router::json(json_encode($obj));
        $battlePlayer->ready = filter_input(INPUT_POST, 'ready', FILTER_VALIDATE_INT) ?: null;
        $battlePlayer->persist();
        $obj->value = true;
        Router::json(json_encode($obj));
    }

    /**
     * Enregistre en BDD la valeur de target "touh" ou "miss" de l'utilisateur.
     * @return void
     */
    public static function postTarget(): void
    {
        $obj = new stdClass();
        $obj->value = false;
        if (!$battlePlayer = Battle::findOneBy(['idUser1' => User::getUserSession()->idUser]))
            Router::json(json_encode($obj));
        $target = filter_input(INPUT_POST, 'target', FILTER_SANITIZE_STRING) ?: null;
        $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING) ?: null;
        switch ($value) {
            case 'touch':
                $battlePlayer->touch = $target;
                break;
            case 'miss':
                $battlePlayer->miss = $target;
                break;
            case 'bombing':
                $battlePlayer->bombing = $target;
                break;
            case 'megaBomb':
                $battlePlayer->megaBomb = $target;
                break;
            case 'repair':
                $battlePlayer->repair = $target;
                $battleOpponent = Battle::findOneBy(['idUser1' => $battlePlayer->idUser2]);
                $battleOpponent->touch = null;
                $battleOpponent->persist();
                break;
        }
        $battlePlayer->persist();
        $obj->value = true;
        Router::json(json_encode($obj));
    }
}
