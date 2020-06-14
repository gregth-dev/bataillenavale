<?php require_once 'inc/head.php' ?>

<body>
    <div class="se-pre-con"></div>
    <header>

    </header>
    <section>
        <div id="menuGame" class="flex-around">
            <div>
                <button class="hvr-radial-out" id="autoPosition"><i class="fas fa-magic"></i> Placement automatique</button>
            </div>
            <div>
                <select name="boat" id="selectBoat" onchange="selectBoat()">
                    <option value="porteAvions">Porte-Avions - 5 cases</option>
                    <option value="croiseur">Croiseur - 4 cases</option>
                    <option value="contreTorpilleur">Contre-Torpilleur - 3 cases</option>
                    <option value="sousMarin">Sous-Marin - 3 cases</option>
                    <option value="torpilleur">Torpilleur - 2 cases</option>
                </select>
                <p id="imgBoats"></p>
            </div>
            <div id="delMenu">
                <img id="delButton" class="del" src="/assets/img/del-icon.png" alt="icon del keyboard">
                <p><i class="fas fa-trash"></i> Supprimer le bateau</p>
            </div>
            <div>
                <button class="hvr-radial-out" id="quitGame" data="<?= $battle->idLaunchBattle ?? '' ?>">
                    <i class="fas fa-external-link-alt"></i> Quitter la Partie
                </button><br>
                <p id="scorePlayer" class="multiplayer">Score : <?= $battle->user1->score ?></p>
            </div>
        </div>
    </section>
    <main id="gameGrid" class="container-fluid flex-around">
        <section>
            <h1><img src="/assets/img/avatars/avatar<?= $battle->user1->avatar ?? '' ?>.png" alt="avatar">
                <?= $battle->user1->name ?? '' ?>
            </h1>
            <div class="grille">
                <table id="player1" grid="<?= $battle->user1->name ?? '' ?>">
                    <tr class="lign">
                        <td case="A1"></td>
                        <td case="B1"></td>
                        <td case="C1"></td>
                        <td case="D1"></td>
                        <td case="E1"></td>
                        <td case="F1"></td>
                        <td case="G1"></td>
                        <td case="H1"></td>
                        <td case="I1"></td>
                        <td case="J1"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A2"></td>
                        <td case="B2"></td>
                        <td case="C2"></td>
                        <td case="D2"></td>
                        <td case="E2"></td>
                        <td case="F2"></td>
                        <td case="G2"></td>
                        <td case="H2"></td>
                        <td case="I2"></td>
                        <td case="J2"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A3"></td>
                        <td case="B3"></td>
                        <td case="C3"></td>
                        <td case="D3"></td>
                        <td case="E3"></td>
                        <td case="F3"></td>
                        <td case="G3"></td>
                        <td case="H3"></td>
                        <td case="I3"></td>
                        <td case="J3"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A4"></td>
                        <td case="B4"></td>
                        <td case="C4"></td>
                        <td case="D4"></td>
                        <td case="E4"></td>
                        <td case="F4"></td>
                        <td case="G4"></td>
                        <td case="H4"></td>
                        <td case="I4"></td>
                        <td case="J4"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A5"></td>
                        <td case="B5"></td>
                        <td case="C5"></td>
                        <td case="D5"></td>
                        <td case="E5"></td>
                        <td case="F5"></td>
                        <td case="G5"></td>
                        <td case="H5"></td>
                        <td case="I5"></td>
                        <td case="J5"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A6"></td>
                        <td case="B6"></td>
                        <td case="C6"></td>
                        <td case="D6"></td>
                        <td case="E6"></td>
                        <td case="F6"></td>
                        <td case="G6"></td>
                        <td case="H6"></td>
                        <td case="I6"></td>
                        <td case="J6"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A7"></td>
                        <td case="B7"></td>
                        <td case="C7"></td>
                        <td case="D7"></td>
                        <td case="E7"></td>
                        <td case="F7"></td>
                        <td case="G7"></td>
                        <td case="H7"></td>
                        <td case="I7"></td>
                        <td case="J7"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A8"></td>
                        <td case="B8"></td>
                        <td case="C8"></td>
                        <td case="D8"></td>
                        <td case="E8"></td>
                        <td case="F8"></td>
                        <td case="G8"></td>
                        <td case="H8"></td>
                        <td case="I8"></td>
                        <td case="J8"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A9"></td>
                        <td case="B9"></td>
                        <td case="C9"></td>
                        <td case="D9"></td>
                        <td case="E9"></td>
                        <td case="F9"></td>
                        <td case="G9"></td>
                        <td case="H9"></td>
                        <td case="I9"></td>
                        <td case="J9"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A10"></td>
                        <td case="B10"></td>
                        <td case="C10"></td>
                        <td case="D10"></td>
                        <td case="E10"></td>
                        <td case="F10"></td>
                        <td case="G10"></td>
                        <td case="H10"></td>
                        <td case="I10"></td>
                        <td case="J10"></td>
                    </tr>
                </table>
                <div class="reduce">
                    <button class="hvr-radial-out" id="reducePlayer1">Réduire la grille</button>
                </div>
            </div>
        </section>
        <section class="container-info">
            <div id="speaker">
                <img id="speakerIMG" src="/assets/img/speaker.png" alt="icon sound">
                <p id="speakerText">ON</p>
            </div>
            <div id="powers">
                <div class="powersPlayer">
                    <div class="power powerOn" id="power1ButtonPlayer1"><img src="/assets/img/power1.png" alt="power1"></div>
                    <div class="power powerOn" id="power2ButtonPlayer1"><img src="/assets/img/power2.png" alt="power2"></div>
                    <div class="power powerOn" id="power3ButtonPlayer1"><img src="/assets/img/power3.png" alt="power2"></div>
                </div>
                <div id="animation"></div>
                <div class="powersPlayer">
                    <div class="power powerOn" id="power1ButtonPlayer2"><img src="/assets/img/power1Player2.png" alt="power1"></div>
                    <div class="power powerOn" id="power2ButtonPlayer2"><img src="/assets/img/power2Player2.png" alt="power2"></div>
                    <div class="power powerOn" id="power3ButtonPlayer2"><img src="/assets/img/power3.png" alt="power2"></div>
                </div>
            </div>
            <div>
                <button class="btn btn-info-dark ready hvr-radial-out">Je suis prêt !!</button>
            </div>
            <div id="infoScore" class="displayNone">
                <button class="hvr-radial-out">Sauvegarder le score</button>
                <p class="message" data=""></p>
            </div>
            <div id="score">Score :</div>
            <div id="infoGame"></div>
        </section>
        <section>
            <h1><img src="/assets/img/avatars/avatar<?= $battle->user2->avatar ?? '' ?>.png" alt="avatar">
                <?= $battle->user2->name ?? '' ?>
            </h1>
            <div class="grille">
                <table id="player2" grid='<?= $battle->user2->name ?? '' ?>'>
                    <tr class="lign">
                        <td case="A1"></td>
                        <td case="B1"></td>
                        <td case="C1"></td>
                        <td case="D1"></td>
                        <td case="E1"></td>
                        <td case="F1"></td>
                        <td case="G1"></td>
                        <td case="H1"></td>
                        <td case="I1"></td>
                        <td case="J1"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A2"></td>
                        <td case="B2"></td>
                        <td case="C2"></td>
                        <td case="D2"></td>
                        <td case="E2"></td>
                        <td case="F2"></td>
                        <td case="G2"></td>
                        <td case="H2"></td>
                        <td case="I2"></td>
                        <td case="J2"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A3"></td>
                        <td case="B3"></td>
                        <td case="C3"></td>
                        <td case="D3"></td>
                        <td case="E3"></td>
                        <td case="F3"></td>
                        <td case="G3"></td>
                        <td case="H3"></td>
                        <td case="I3"></td>
                        <td case="J3"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A4"></td>
                        <td case="B4"></td>
                        <td case="C4"></td>
                        <td case="D4"></td>
                        <td case="E4"></td>
                        <td case="F4"></td>
                        <td case="G4"></td>
                        <td case="H4"></td>
                        <td case="I4"></td>
                        <td case="J4"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A5"></td>
                        <td case="B5"></td>
                        <td case="C5"></td>
                        <td case="D5"></td>
                        <td case="E5"></td>
                        <td case="F5"></td>
                        <td case="G5"></td>
                        <td case="H5"></td>
                        <td case="I5"></td>
                        <td case="J5"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A6"></td>
                        <td case="B6"></td>
                        <td case="C6"></td>
                        <td case="D6"></td>
                        <td case="E6"></td>
                        <td case="F6"></td>
                        <td case="G6"></td>
                        <td case="H6"></td>
                        <td case="I6"></td>
                        <td case="J6"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A7"></td>
                        <td case="B7"></td>
                        <td case="C7"></td>
                        <td case="D7"></td>
                        <td case="E7"></td>
                        <td case="F7"></td>
                        <td case="G7"></td>
                        <td case="H7"></td>
                        <td case="I7"></td>
                        <td case="J7"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A8"></td>
                        <td case="B8"></td>
                        <td case="C8"></td>
                        <td case="D8"></td>
                        <td case="E8"></td>
                        <td case="F8"></td>
                        <td case="G8"></td>
                        <td case="H8"></td>
                        <td case="I8"></td>
                        <td case="J8"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A9"></td>
                        <td case="B9"></td>
                        <td case="C9"></td>
                        <td case="D9"></td>
                        <td case="E9"></td>
                        <td case="F9"></td>
                        <td case="G9"></td>
                        <td case="H9"></td>
                        <td case="I9"></td>
                        <td case="J9"></td>
                    </tr>
                    <tr class="lign">
                        <td case="A10"></td>
                        <td case="B10"></td>
                        <td case="C10"></td>
                        <td case="D10"></td>
                        <td case="E10"></td>
                        <td case="F10"></td>
                        <td case="G10"></td>
                        <td case="H10"></td>
                        <td case="I10"></td>
                        <td case="J10"></td>
                    </tr>
                </table>
                <div class="reduce">
                    <button class="hvr-radial-out" id="reducePlayer2">Réduire la grille</button>
                </div>
            </div>
        </section>
    </main>
    <button class="chat-button" onclick="openChat()">Chat<span class="badge"></span></button>
    <div id="chatMulti">
        <div class="card">
            <div class="card-header">Chat</div>
            <div class="card-bodyMulti height3">
                <ul class="chat-list"></ul>
            </div>
        </div>
        <div>
            <form id="formChat">
                <input type="text" class="form-control" name="content" id="message" required placeholder="Votre message...">
                <button type="submit" id="chatButton" class="btn btn-primary col-sm-2 col-form-button"><i class="far fa-paper-plane"></i></button>
            </form>
            <small id="textAlert" class="form-text text-danger"></small>
            <button class="chat-button" onclick="closeChat()">Close</button>
        </div>
    </div>
    <footer>
        <?php require_once 'inc/footer.php' ?>
    </footer>
    <audio id="alerte" src="/assets/sounds/alert.mp3"></audio>
    <audio id="fireSound" src="/assets/sounds/fireSound.mp3"></audio>
    <audio id="fireMiss" src="/assets/sounds/fireMiss.mp3"></audio>
    <audio id="fireTouch" src="/assets/sounds/fireTouch.mp3"></audio>
    <audio id="bombing" src="/assets/sounds/bombardement.mp3"></audio>
    <audio id="megaBomb" src="/assets/sounds/megabomb.mp3"></audio>
    <audio id="gameOver" src="/assets/sounds/gameover.mp3"></audio>
    <audio id="gameWin" src="/assets/sounds/gamewin.mp3"></audio>
    <script src="/assets/js/game/Player.js"></script>
    <script src="/assets/js/game/Boat.js"></script>
    <script src="/assets/js/game/GameEvent.js"></script>
    <script src="/assets/js/game/Grid.js"></script>
    <script src="/assets/js/game/GridMulti.js"></script>
    <script src="/assets/js/game/GameControl.js"></script>
    <script src="/assets/js/game/Battle.js"></script>
    <script src="/assets/js/game/gameMulti.js"></script>
    <script src="/assets/js/Chat.js"></script>
    <script src="/assets/js/appCom.js"></script>
</body>

</html>