<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/connectionsUsersFuncs.php';
    include 'PhpShits/userFunctions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Configurações</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php">
</head>
<body>

<div class="app-container">

    <!-- HEADER -->
    <header class="app-header">
        <a href='home'><button class="icon-btn"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg></button></a>
        <span class="app-title">Notificações</span>
        <span></span>
    </header>

    <!-- CONTEÚDO -->
    <div class="feed">

        <?php $requests = getAllFriendRequest($conn, $_COOKIE['UserId']); ?>
        <?php if(is_null($requests)):?>
            <center><i>Você não tem novas mensagens</i></center>
        <?php endif; ?>
        <?php if(!is_null($requests)):?>
            <?php foreach($requests as $request): ?>
                <div class="post-card" style="padding:16px; gap:12px;">
                    <?php $userHowSended = getUserInfo($conn, $request['user1']) ?>
                    <div class="post-header">
                        <div class="avatar" style="width:64px; height:64px; background: url(<?= htmlspecialchars($userHowSended['profilePic'] ?? '') ?>); background-repeat: no-repeat; background-size: cover;"></div>
                        <div class="post-user">
                            <strong style="font-size:14px;"><?= $userHowSended['username'] ?></strong>
                            <span>Enviou um pedido de amizade</span>
                        </div>
                    </div>
                    <br>
                    <div class="button-group">
                        <a href="profile.php?id=<?= $request['user1'] ?>&request=accept" class="btn btn-primary" style="flex:1;">
                            <center>
                                Aceitar
                            </center>
                        </a>
                        <a href="profile.php?id=<?= $request['user1'] ?>&request=reject" class="btn btn-secondary" style="flex:1;">
                            <center>
                                Recusar
                            </center>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif ?>
        <!-- NOTIFICAÇÃO: MENSAGEM 
        <div class="post-card" style="padding:16px; gap:12px;">

            <div class="post-header">
                <div class="avatar"></div>

                <div class="post-user">
                    <strong style="font-size:14px;">Nome do usuario</strong>
                    <span>Enviou uma mensagem</span>
                </div>
            </div>
            <br>
            <button class="btn btn-secondary" style="width:100%;">
                ⭐ Visualizar
            </button>

        </div>
        -->
    </div>

</div>

</body>

<script src="push-notifications.js"></script>
</html>