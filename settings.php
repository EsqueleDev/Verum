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
        <button class="icon-btn" onclick="window.history.back();"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></button>
        <span class="app-title"></span>
        <span></span>
    </header>

    <!-- CONTEÚDO -->
    <div class="feed">

        <h1 style="font-size:26px; font-weight:600;">Configurações</h1>

        <!-- ITEM -->
        
        <a href='editProfileInfo.php'><div class="action-card option-name" onclick="window.location.href='themeSelector'">
            <span>
                <strong>Seu Perfil</strong>
                <p style="font-size:13px; color:var(--on-button);">
                    Edite sua foto, sua bio entre outros.
                </p>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
        </div></a>
        <div class="action-card option-name" onclick="window.location.href='themeSelector'">
            <span>
                <strong>Aparencia do site</strong>
                <p style="font-size:13px; color:var(--on-button);">
                    Escolha a paleta de cores, muda a fonte e outros.
                </p>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
        </div>
        
        <div class="action-card option-name" onclick="window.location.href='editUserAbout.php'">
            <span>
                <strong>Seus Gostos</strong>
                <p style="font-size:13px; color:var(--on-button);">
                    Mostre seus gostos ao mundo
                </p>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
        </div>

        <i><h2>Ainda não operando:</h2></i>
        <!-- ITEM -->

        <!-- ITEM -->
        <div class="action-card option-name">
            <span>
                <strong>Sua Privacidade</strong>
                <p style="font-size:13px; color:var(--on-button);">
                    Decida quem pode ver certas informações
                </p>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
        </div>

        <!-- ITEM -->
        <div class="action-card option-name">
            <span>
                <strong>Informações de login</strong>
                <p style="font-size:13px; color:var(--on-button);">
                    Mude seu e-mail e senha se necessario
                </p>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
        </div>

    </div>

</div>

</body>
</html>