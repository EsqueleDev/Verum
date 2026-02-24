<?php
    $error = $_GET['error'];
    switch($error){
        case 'incorrectpassword':
            $title = "A senha digitada esta incorreta.";
            $subtitle = "Tente novamente";
            $option = "Voltar";
            $optionDoes = "onclick='window.history.back();'";
            break;
        
        case 'nousername':
            $title = "Não existe conta com este nome.";
            $subtitle = "Tente novamente";
            $option = "Voltar";
            $optionDoes = "onclick='window.history.back();'";
            break;

        case '404':
            $title = "Esta pagina não foi encontrada.";
            $subtitle = "Verifique o link, mas pode ser culpa nossa tambem.";
            $option = "Voltar";
            $optionDoes = "onclick='window.history.back();'";
            break;

        case '500':
            $title = "Algo muito de errado aconteceu.";
            $subtitle = "Assim como no seu termino, sou eu não você.<br>Por favor retorne onde estava e<br> tente novamente depois";
            $option = "Voltar";
            $optionDoes = "onclick='window.history.back();'";
            break;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Error</title>
    <base href="/Mobile_Verum/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
</head>
<body>

<div class="app-container">
    <div class="center-screen">
        <div class="welcome-box">
            <h1><?= $title ?></h1>
            <p><?= $subtitle ?></p>
            <button <?= $optionDoes ?> class="btn btn-primary"><?= $option ?></button>
        </div>
    </div>
</div>

</body>
</html>
