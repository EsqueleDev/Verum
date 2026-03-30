<body>
    <h3>Se você é da beta, e encontrou isto, por favor ignore, amanha fara sentido.</h3>
    <button onclick="writeNFC()">Gravar NFC</button>
    
    <script>
    async function writeNFC() {
      if (!('NDEFReader' in window)) {
        alert("NFC não suportado");
        return;
      }
    
      try {
        const ndef = new NDEFReader();
    
        // Exemplo: pegando ID do usuário do seu sistema
        const userId = localStorage.getItem("<?= $_COOKIE['UserId'] ?>") || "0";
    
        const data = {
          type: "user",
          id: userId
        };
    
        await ndef.write({
          records: [
            {
              recordType: "text",
              data: JSON.stringify(data)
            }
          ]
        });
    
        alert("Dados gravados na tag NFC!");
      } catch (error) {
        alert(error);
        alert("Erro ao gravar NFC");
      }
    }
    </script>
</body>