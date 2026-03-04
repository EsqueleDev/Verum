<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Próximos Passos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="app-container" style="padding: 24px;">

    <div class="actions-box">

        <h1 class="page-title">Agora você:</h1>

        <div class="action-card">
            <p>
                Você pode enviar uma foto de perfil, adicionar gostos,
                configurar o spotify entre outros:
            </p>
            <a href='settings'><button class="btn btn-primary">
                <div style="display: grid; grid-template-columns: 16% 87%;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg> 
                    <span style="margin-top: 3px;">Ir para as configurações</span>
                </div>
            </button></a>
        </div>

        <div class="action-card">
            <p>
                Ative as notificações do Verum para ver Pedidos de Amizade, Mensagens e Novidades da Beta:
            </p>
            <button class="btn btn-primary" onclick="Notification.requestPermission()">
                <div style="display: grid; grid-template-columns: 16% 87%;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/></svg> 
                    <span style="margin-top: 3px;">&nbsp;&nbsp;Ativar</span>
                </div>
            </button>
        </div>

        <div class="action-card">
            <p>
                Ou simplesmente ir para seu feed:
            </p>
            <a href='home'><button class="btn btn-primary">
                <div style="display: grid; grid-template-columns: 16% 87%;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg> 
                    <span style="margin-top: 3px;">&nbsp;&nbsp;Ir para o feed</span>
                </div>
            </button></a>
        </div>

    </div>

</div>

</body>
</html>
