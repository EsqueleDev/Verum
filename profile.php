<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verum</title>
    <style>
        :root{
            --background-app: #1a1522;
            --on-background: #ece7f6;
            --div: #2a174d;
            --on-div: #d0bcff;
            --button: #3f2b66;
            --on-button: #b7a4e5 ;
        }

        body{
            background: var(--background-app);
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--on-background);
        }
        
        .page-container{
            width: 80%;
            margin: auto;
        }

        .page-cover{
            width: 100%;
            height: 348px;
            background: linear-gradient(0deg,rgba(0, 0, 0, 1) 0%, var(--button) 100%);
            border-radius: 12px;
        }

        .user-info{
            width: 100%;
            padding: 24px;
            padding-left: 48px;
            padding-right: 48px;
            display: grid;
            grid-template-columns: 25% 28% 47%;
            place-items: center;
        }

        .userProfilePic{
            width: 200px;
            height: 200px;
            background: url(https://placehold.co/400);
            background-size: cover;
            background-repeat: no-repeat;
            border-radius: 50%;
        }

        .btn{
            border: none;
            border-radius: 12px;
            font-weight: 600; 
            padding: 12px;
        }

        .btn-primary{
            background: var(--button);
            color: var(--on-button);
        }

        .btn-secondary{
            color: var(--button);
            background: var(--on-button);
        }

        .all-about{
            display: grid;
            grid-template-columns: 1fr 2fr;
        }

        .personal-data{
            background: var(--div);
            padding-left: 24px;
            padding-right: 24px;
            border-radius: 12px;
            padding-bottom: 12px;
        }

        .personal-data-grid{
            display: grid;
            grid-template-columns: 18% 82%;
            place-items: center;
        }

        .personal-data-header{
            display: grid;
            grid-template-columns: 2fr 1fr;
        }

        .tree-columns-grid{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
        }

        .friends-container{
            background: var(--div);
            padding-left: 24px;
            padding-right: 24px;
            border-radius: 12px;
            padding-bottom: 12px;
        }

        .block{
            display: block;
            width: 100%;
            height: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 12px;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    <script>
        console.log("%cESPERAA!", "color: red; font-size: 48px; font-weight: bold;");
        console.log("%cEste menu é para desenvolvedores, SE ALGUEM PEDIU PARA COLAR ALGO AQUI, NÃO COLE, ESTAM TENTANDO ROUBAR SUA CONTA.", "color: red; font-size: 20px; font-weight: bold;");
        console.log("%cSe veio tentar indetificar um problema va em frente", "color: red; font-size: 20px; font-weight: bold;");
    </script>
</head>
<body>
    <div class='page-container'>
        <div class='page-cover'>

        </div>
        <div class='user-info'>
            <span class='userProfilePic'></span>
            <span style='font-weight: 600;'>
                <h1>Nome do Fulano</h1>
                <p>0 Amigos</p>
                <p>Inferno</p>
            </span>
            <span>
                <button class='btn btn-secondary'>Enviar Pedido de Amizade</button>
                <button class='btn btn-primary'>Enviar Mensagem</button>
            </span>
        </div>
        <hr><br>
        <div class='all-about'>
            <div class='left-side'>
                <div class='personal-data'>
                    <span class='personal-data-header'>
                        <h2>Dados Pessoais</h2><br>
                    </span>
                    <div class='personal-data-grid'>
                        <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M160-80q-17 0-28.5-11.5T120-120v-212.67q0-27.5 19.58-47.08 19.59-19.58 47.09-19.58h18V-574q0-27.5 19.58-47.08 19.58-19.59 47.08-19.59h175.34v-62q-19.34-13.33-30-30.02Q406-749.38 406-772.94q0-14.73 5.67-28.56 5.66-13.83 16.33-24.5l52-54 52 54q10.67 10.67 16.67 24.5 6 13.83 6 28.56 0 23.56-11 40.25t-30.34 30.02v62h175.34q27.5 0 47.08 19.59 19.58 19.58 19.58 47.08v174.67h18q27.5 0 47.09 19.58Q840-360.17 840-332.67V-120q0 17-11.5 28.5T800-80H160Zm111.33-319.33h417.34V-574H271.33v174.67Zm-84.66 252.66h586.66v-186H186.67v186Zm84.66-252.66h417.34-417.34Zm-84.66 252.66h586.66-586.66Zm586.66-252.66H186.67h586.66Z"/></svg>
                        <h3>5 de Junho de 2012</h3>
                    </div>
                </div>
                <br>
                <div class='friends-container'>
                    <span class='personal-data-header'>
                        <h2 style='margin-top: 12px;'>Amigos:</h2>
                    </span>
                    <div class='tree-columns-grid'>
                        <span class='block' style='background: url(https://placehold.co/400);'></span>
                        <span class='block' style='background: url(https://placehold.co/400);'></span>
                        <span class='block' style='background: url(https://placehold.co/400);'></span>
                        <span class='block' style='background: url(https://placehold.co/400);'></span>
                        <span class='block' style='background: url(https://placehold.co/400);'></span>
                        <span class='block' style='background: url(https://placehold.co/400);'></span>
                    </div>
                </div>
                <br>
                <div class='friends-container'>
                    <span class='personal-data-header'>
                        <h2 style='margin-top: 12px;'>Fotos:</h2>
                    </span>
                    <div class='tree-columns-grid'>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                        <span style='background: url(https://placehold.co/400);' class='block'></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>