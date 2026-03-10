<head>
    <meta charset="UTF-8">
    <title>Verum - Busca</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php">
    <link rel="manifest" href="manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="Page_Icon.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#1a1522" />
    <meta name="theme-color" content="#1a1522" />
</head>
<body>
    <div class='hideMobile'></div>
    <div class="app-container">
        <header class="app-header">
            <button class="icon-btn" onclick="window.history.back();"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg></button>
            <span class="app-title">Buscador</span>
            <span></span>
        </header>
        <br>
        <div class="form-group">
            <input id="searchParameter" style="margin-left: 8px; margin-right: 8px;" placeholder="Barra de busca...">
        </div>
        <div class="suggestions" style="padding: 12px;" id='results'>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <center><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></center>
            <span></span>
            <span></span>
            <center>Digite algo na barra de busca para encontrar</center>
        </div>
    </div>
    <script>
        const searchParameter = document.getElementById("searchParameter");
        const resultsContainer = document.getElementById("results");
        
        searchParameter.addEventListener("input", async () => {
        
            const url = `returnResultsOfUsers.php?parameter=${encodeURIComponent(searchParameter.value)}`;
        
            try {
        
                const response = await fetch(url);
        
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
        
                const result = await response.json();
        
                // limpa resultados anteriores
                resultsContainer.innerHTML = "";
        
                result.forEach(profile => {
        
                    const userHTML = `
                        <a href="profile.php?id=${profile.id}">
                            <div class="suggestion-card">
                                <div class="suggest-avatar avatar-a"
                                     style="background:url('${profile.profilePic}');
                                            background-repeat:no-repeat;
                                            background-size:cover;">
                                </div>
                                <span>${profile.username}</span>
                            </div>
                        </a>
                    `;
        
                    resultsContainer.insertAdjacentHTML("beforeend", userHTML);
        
                    console.log(profile.username);
                });
        
            } catch (error) {
                console.error(error.message);
            }
        });
    </script>
</body>