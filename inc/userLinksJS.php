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
        <button class="chat-button" onclick="closeChat()"><i class="fas fa-arrow-circle-down"></i></button>
    </div>
</div>
<button class="online-button" onclick="openOnline()">Online<span class="badgeOnline"></span></button>
<div class="" id="onlinePopup">
    <table class="table table-dark">
        <tbody class="onlineUsers">

        </tbody>
    </table>
    <button class="online-button" onclick="closeOnline()"><i class="fas fa-arrow-circle-down"></i></button>
</div>
<script src="/assets/js/Online.js"></script>
<script src="/assets/js/Chat.js"></script>
<script src="/assets/js/appCom.js"></script>
<script src="/assets/js/LaunchBattle.js"></script>
<script src="/assets/js/appLaunchBattle.js"></script>