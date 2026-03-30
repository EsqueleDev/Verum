<?php
require 'vendor/autoload.php';
include 'PhpShits/userFunctions.php';
include 'PhpShits/conn.php';
use chillerlan\QRCode\QRCode;

$MyId = $_COOKIE['UserId'];

$data = "profile.php?id=$MyId";

$me = getUserInfo($conn, $MyId);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Rapido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=100000">
    <link rel="stylesheet" href="colors.php">
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Adição Rapida</span>
        <span></span>
    </header>
    <div class='options-top'><center>
        <span class='option option-active' id='optionRead' onclick="changeTab('lerQR')">Ler QR</span>
        <span class='option' id='optionMake' onclick="changeTab('gerarQR')">Gerar QR</span>
    </center></div>
    
    <div id='lerQR'>
        <center>
            <h2>Escaneie o QR-Code de alguem para ir para seu perfil.</h2>
            <video id="video" autoplay playsinline muted style="width:80%; max-width:400px;"></video>
        </center>
    </div>
    
    <div id='gerarQR' style='display: none;'><center>
        <?php echo '<img style="width: 80%; border-radius: 25px;" src="'.(new QRCode)->render($data).'" />'; ?><br>
        <h3 style='width: 80%;'>Escaneie este QR-Code no seu Verum para ver o perfil de <b><?= $me['username'] ?></b></h3>
    </center></div>
</div>
<script>
    const readQRTab = document.getElementById('lerQR');
    const generateQRTab = document.getElementById('gerarQR');
    const readQRButton = document.getElementById('optionRead');
    const generateQRButton = document.getElementById('optionMake');
    
    function changeTab(tabToChange){
        switch(tabToChange){
            case 'lerQR':
                readQRTab.style.display = 'block';
                generateQRTab.style.display = 'none';
                readQRButton.classList.add('option-active');
                generateQRButton.classList.remove('option-active');
                break;
                
            case 'gerarQR':
                readQRTab.style.display = 'none';
                generateQRTab.style.display = 'block';
                readQRButton.classList.remove('option-active');
                generateQRButton.classList.add('option-active');
                break;
        }
    }
</script>

<script>
const video = document.getElementById('video');

async function startScanner() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" }
        });

        video.srcObject = stream;

        // 🔥 espera o vídeo realmente iniciar
        await video.play();

        if (!('BarcodeDetector' in window)) {
            alert("Seu navegador não suporta escanear QR-Codes");
            return;
        }

        const detector = new BarcodeDetector({ formats: ['qr_code'] });

        // 🔥 loop contínuo (melhor que setInterval)
        async function scan() {
            try {
                const barcodes = await detector.detect(video);

                if (barcodes.length > 0) {
                    alert("Codigo escaneado, espere...");
                    window.location.href = barcodes[0].rawValue;
                }
            } catch (err) {
                console.error(err);
            }

            requestAnimationFrame(scan);
        }

        scan();

    } catch (err) {
        console.error("Erro câmera:", err);
        alert("Erro ao acessar câmera");
    }
}

startScanner();
</script>


</body>
</html>