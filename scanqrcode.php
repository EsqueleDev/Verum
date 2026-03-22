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
    <header class="app-header">
        <button class="icon-btn"></button>
        <span class="app-title">Escanei o QR Code</span>
        <span></span>
    </header>
    <br>
    <center>
        <div id="reader" width="100%"></div>
    </center>
</div>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script type='module'>
    function onScanSuccess(decodedText, decodedResult) {
      // handle the scanned code as you like, for example:
      alert(`Code matched = ${decodedText}`, decodedResult);
    }
    
    function onScanFailure(error) {
      // handle scan failure, usually better to ignore and keep scanning.
      // for example:
      console.warn(`Code scan error = ${error}`);
    }
    
    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader",
      { fps: 10, qrbox: {width: 250, height: 250} },
      /* verbose= */ false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
</body>
</html>