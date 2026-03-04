<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/connectionsUsersFuncs.php';
    include 'PhpShits/userFunctions.php';
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        setcookie('theme', $_POST['themeSelected'], time()+60*60+24*356);
        echo "<script>window.location.href = document.referrer;</script>";
    }
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
        <button class="icon-btn"></button>
        <span class="app-title">Aparência do Site</span>
        <span></span>
    </header>

    <!-- CONTEÚDO -->
    <div class="feed theme-selector">

        <!-- PREVIEW GRANDE -->
        <div class="theme-preview">
            <article class="post-card" style="width: 100%;">
                    <div class="post-header">
                        <div class="avatar" style="background: url(Default_Profile_Pics/1.png); background-repeat: no-repeat; background-size: cover;"></div>
                        <div class="post-user">
                            <strong>Alguem</strong>
                            <span>22/02/26</span>
                        </div>
                    </div>

                    <p class="post-text">
                        <h2>Titulo Exemplar</h2>
                        Post Exemplo
                    </p>
                </article>
        </div>

        <!-- OPÇÕES -->
        <div class="theme-options">

            <div class="theme-option active" onclick="changeThemeDisplay('purple')">
                <div class="theme-thumb">
                    <img src="ThemePurplePreview.png">
                </div>
                <span>Roxo (Padrão)</span>
            </div>

            <div class="theme-option" onclick="changeThemeDisplay('orange')">
                <div class="theme-thumb orange">
                    <img src="ThemeOrangePreview.png">
                </div>
                <span>Laranja</span>
            </div>

            <div class="theme-option" onclick="changeThemeDisplay('yellow')">
                <div class="theme-thumb orange">
                    <img src="ThemeYellowPreview.png">
                </div>
                <span>Amarelo</span>
            </div>

            <div class="theme-option" onclick="changeThemeDisplay('cyan')">
                <div class="theme-thumb orange">
                    <img src="ThemeCyanPreview.png">
                </div>
                <span>Ciano</span>
            </div>

            <div class="theme-option" onclick="changeThemeDisplay('blue')">
                <div class="theme-thumb orange">
                    <img src="ThemeBluePreview.png">
                </div>
                <span>Azul</span>
            </div>

            <div class="theme-option" onclick="changeThemeDisplay('green')">
                <div class="theme-thumb orange">
                    <img src="ThemeGreenPreview.png">
                </div>
                <span>Verde</span>
            </div>

            <div class="theme-option" onclick="changeThemeDisplay('red')">
                <div class="theme-thumb orange">
                    <img src="ThemeRedPreview.png">
                </div>
                <span>Vermelho</span>
            </div>

        </div>
    </div>
    <form method="POST">
        <input type="hidden" id="themeSelected" name="themeSelected">
        <div class="form-footer">
            <a href='home'><button class="btn btn-secondary" type="button">Voltar</button></a>
            <button class="btn btn-primary">Salvar</button>
        </div>
    </form>

</div>
<script>
    function changeThemeDisplay(color){
        const root = document.documentElement;
        switch(color){
            case 'purple':
                root.style.setProperty('--background-app', '#1a1522');
                root.style.setProperty('--on-background', '#ece7f6');
                root.style.setProperty('--div', '#2a174d');
                root.style.setProperty('--on-div', '#d0bcff');
                root.style.setProperty('--button', '#3f2b66');
                root.style.setProperty('--on-button', '#b7a4e5');
                break;
            case 'orange':
                root.style.setProperty('--background-app', '#3b2300');
                root.style.setProperty('--on-background', '#ffb86c');
                root.style.setProperty('--div', '#5a3a10');
                root.style.setProperty('--on-div', '#e0a25a');
                root.style.setProperty('--button', '#222115');
                root.style.setProperty('--on-button', '#f6efe7');
                break;
            case 'yellow':
                root.style.setProperty('--background-app', '#2f2a00');
                root.style.setProperty('--on-background', '#e6d76a');
                root.style.setProperty('--div', '#4a4300');
                root.style.setProperty('--on-div', '#cfc25f');
                root.style.setProperty('--button', '#1f2215');
                root.style.setProperty('--on-button', '#f5f6e7');
                break;
            case 'cyan':
                root.style.setProperty('--background-app', '#003737');
                root.style.setProperty('--on-background', '#6fe3e1');
                root.style.setProperty('--div', '#0f4f4f');
                root.style.setProperty('--on-div', '#5cc7c5');
                root.style.setProperty('--button', '#152022');
                root.style.setProperty('--on-button', '#e7f2f6');
                break;
            case 'blue':
                root.style.setProperty('--background-app', '#001a33');
                root.style.setProperty('--on-background', '#a6c8ff');
                root.style.setProperty('--div', '#0d2d5a');
                root.style.setProperty('--on-div', '#8cb4e6');
                root.style.setProperty('--button', '#152033');
                root.style.setProperty('--on-button', '#e7eeff');
                break;
            case 'green':
                root.style.setProperty('--background-app', '#002200');
                root.style.setProperty('--on-background', '#8aff8a');
                root.style.setProperty('--div', '#0a330a');
                root.style.setProperty('--on-div', '#6bc46b');
                root.style.setProperty('--button', '#152215');
                root.style.setProperty('--on-button', '#e7ffe7');
                break;
            case 'red':
                root.style.setProperty('--background-app', '#330000');
                root.style.setProperty('--on-background', '#ffaaaa');
                root.style.setProperty('--div', '#5a0d0d');
                root.style.setProperty('--on-div', '#e68a8a');
                root.style.setProperty('--button', '#331515');
                root.style.setProperty('--on-button', '#ffe7e7');
                break;
        }
        document.getElementById('themeSelected').value = color;
    }
</script>
</body>
</html>