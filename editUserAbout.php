<?php
    include 'PhpShits/conn.php';
    include 'PhpShits/userFunctions.php';

    $me = getUserInfo($conn, $_COOKIE['UserId']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $livros = $_POST['livro'];
        $musicas = $_POST['musicas'];
        $filmes = $_POST['filmes'];

        $sql = "UPDATE user SET livros ='$livros', musicas ='$musicas', filmes ='$filmes'
                WHERE id = " . $me['id'] .";";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Perfil Editado com sucesso!');</script>";
        } else{
            echo "<script>window.location.href = 'serverfallback.php?error=editProfileUpdate';</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Editar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
    <base href="/Project-Verum/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="two-options" id="about-options" style="max-height: 140px;">
        <nav onclick="window.history.back();"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></nav><br>
        <div class="a-option" onclick="showAboutSection('gostos')">
            <span class="title">Gostos Pessoais</span>
            <svg class="arrow" xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#FFFFFF">
                <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
            </svg>
        </div>
        <div class="a-option" onclick="showAboutSection('spotify')">
            <span class="title">Informações do Spotify</span>
            <svg class="arrow" xmlns="http://www.w3.org/2000/svg"
                height="24px"
                viewBox="0 -960 960 960"
                width="24px"
                fill="#FFFFFF">
                <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/>
            </svg>
        </div>
    </div>
    
    <!-- Gostos Pessoais Section -->
    <div id="about-gostos" class="about-detail" style="display: none;">
        <div class="about-header">
            <button class="icon-btn" onclick="hideAboutSection()">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
                </svg>
            </button>
            <h2 style="font-size:20px; font-weight:500;">Gostos Pessoais</h2>
        </div>
        <hr>
            <form class="form-box" method="post" id="interrests-form">
                <div class="form-header">
                    <h1>Edite seus<br>gostos e sua bio</h1>
                    <p>Mude algumas coisas para mostrar mais de você</p>
                </div>

                <div class="form-group">
                    <label>Artistas/Bandas Favoritas</label>
                    <input type="text" name="musicas" value="<?= $me['musicas'] ?>" placeholder="Exemplo: Nirvana, Cazuza, Radiohead...">
                </div>

                <div class="form-group">
                    <label>Filmes/Series Favoritas</label>
                    <input type="text" name="filmes" value="<?= $me['filmes'] ?>" placeholder="Exemplo: Friends, the Office, Click...">
                </div>

                <div class="form-group">
                    <label>Livros/Mangas Favoritos</label>
                    <input type="text" name="livro" value="<?= $me['livros'] ?>" placeholder="Exemplo: 1984, JoJo...">
                </div>

            </form>

            <div class="form-footer">
                <button class="btn btn-secondary" onclick='window.history.back()'>Voltar</button>
                <button class="btn btn-primary" onclick="document.getElementById('interrests-form').submit()">Salvar</button>
            </div>
    </div>
    
    <!-- Informações do Spotify Section -->
    <div id="about-spotify" class="about-detail" style="display: none;">
        <div class="about-header">
            <button class="icon-btn" onclick="hideAboutSection()">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                    <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/>
                </svg>
            </button>
            <h2 style="font-size:20px; font-weight:500;">Informações do Spotify</h2>
        </div>
        <hr>
        <p style="padding: 16px; text-align: center; color: var(--on-surface);">
            Em breve: integração com Spotify
        </p>
    </div>
    
    <script>
        function showAboutSection(section) {
            const aboutOptions = document.getElementById('about-options');
            const gostosSection = document.getElementById('about-gostos');
            const spotifySection = document.getElementById('about-spotify');
            
            // Hide the options menu
            if (aboutOptions) {
                aboutOptions.style.display = 'none';
            }
            
            // Show the appropriate section
            if (section === 'gostos' && gostosSection) {
                gostosSection.style.display = 'block';
                gostosSection.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            } else if (section === 'spotify' && spotifySection) {
                spotifySection.style.display = 'block';
                spotifySection.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            }
        }
        
        function hideAboutSection() {
            const aboutOptions = document.getElementById('about-options');
            const gostosSection = document.getElementById('about-gostos');
            const spotifySection = document.getElementById('about-spotify');
            
            // Hide all detail sections
            if (gostosSection) gostosSection.style.display = 'none';
            if (spotifySection) spotifySection.style.display = 'none';
            
            // Show the options menu
            if (aboutOptions) {
                aboutOptions.style.display = 'grid';
                aboutOptions.style.animation = 'fadeSlideIn 0.3s ease-out forwards';
            }
        }
    </script>
</body>
</html>
