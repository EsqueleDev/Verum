<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Escanear QR Code</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">

    <style>
        #reader {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick='window.history.back()'><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Escaneie o QR Code</span>
        <span></span>
    </header>

    <br>

    <center>
        <video id="video" autoplay playsinline muted style="width:100%; max-width:400px;"></video>
    </center>
</div>

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
            alert("Seu navegador não suporta BarcodeDetector");
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