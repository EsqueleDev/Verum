<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Aparência do Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php">
    <style>
        .theme-option {
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .theme-option:hover {
            transform: scale(1.05);
        }
        
        .theme-selected {
            background-color: var(--on-div);
            color: var(--div);
            border-radius: 12px;
        }

        .theme-selected img {
            border: 2px solid var(--on-background);
        }
        
        .theme-preview {
            border-radius: 8px;
            border: 2px solid transparent;
        }
        
        .theme-preview.selected {
            border-color: var(--on-background);
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--on-button);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--div);
            color: var(--on-div);
            padding: 12px 24px;
            border-radius: 8px;
            display: none;
            z-index: 1000;
        }
        
        .toast.show {
            display: block;
            animation: fadeInOut 2s ease;
        }
        
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            10%, 90% { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- HEADER -->
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back();">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
        </button>
        <span class="app-title">Aparência do site</span>
        <span></span>
    </header>

    <!-- CONTEÚDO -->
    <div class="feed" style="align-items:center; padding-bottom: 100px;">

        <!-- PREVIEW GRANDE -->
        <img id="previewImage" src="ThemePagePreviewPurple.png" style="width: 100%; border: 4px white solid; border-radius: 8px;">
    
        <!-- TOAST -->
        <div id="toast" class="toast">Tema atualizado com sucesso!</div>

        <!-- OPÇÕES -->
        <div style="
            padding: 12px;
            width: 100%;
            background: var(--div);
            position: fixed;
            bottom: 0px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding-bottom: 24px;
        ">

            <!-- TEMA ROXO -->
            <div class="theme-option theme-selected" id="theme-Purple" onclick="selectTheme('Purple')" style="text-align:center; padding: 12px;">
                <img src='ThemePurplePreview.png' class="theme-preview" style="width: 90px; height: 90px;">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Roxo
                </p>
            </div>

            <!-- TEMA LARANJA -->
            <div class="theme-option" id="theme-Orange" onclick="selectTheme('Orange')" style="text-align:center; padding: 12px;">
                <img src='ThemeOrangePreview.png' class="theme-preview" style="width: 90px; height: 90px;">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Laranja
                </p>
            </div>

            <!-- TEMA AMARELO -->
            <div class="theme-option" id="theme-Yellow" onclick="selectTheme('Yellow')" style="text-align:center; padding: 12px;">
                <img src='ThemeYellowPreview.png' class="theme-preview" style="width: 90px; height: 90px;">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Amarelo
                </p>
            </div>

            <!-- TEMA CIANO -->
            <div class="theme-option" id="theme-Ciano" onclick="selectTheme('Ciano')" style="text-align:center; padding: 12px;">
                <img src='ThemeCyanPreview.png' class="theme-preview" style="width: 90px; height: 90px;">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Ciano
                </p>
            </div>

            <!-- TEMA AZUL -->
            <div class="theme-option" id="theme-Blue" onclick="selectTheme('Blue')" style="text-align:center; padding: 12px;">
                <img src='ThemePurplePreview.png' class="theme-preview" style="width: 90px; height: 90px; filter: hue-rotate(200deg);">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Azul
                </p>
            </div>

            <!-- TEMA VERDE -->
            <div class="theme-option" id="theme-Green" onclick="selectTheme('Green')" style="text-align:center; padding: 12px;">
                <img src='ThemePurplePreview.png' class="theme-preview" style="width: 90px; height: 90px; filter: hue-rotate(100deg);">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Verde
                </p>
            </div>

            <!-- TEMA VERMELHO -->
            <div class="theme-option" id="theme-Red" onclick="selectTheme('Red')" style="text-align:center; padding: 12px;">
                <img src='ThemePurplePreview.png' class="theme-preview" style="width: 90px; height: 90px; filter: hue-rotate(-50deg);">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Vermelho
                </p>
            </div>

            <!-- TEMA ROSA -->
            <div class="theme-option" id="theme-Pink" onclick="selectTheme('Pink')" style="text-align:center; padding: 12px;">
                <img src='ThemePurplePreview.png' class="theme-preview" style="width: 90px; height: 90px; filter: hue-rotate(-70deg) saturate(150%);">
                <p style="font-size:11px; margin-top:6px; margin-bottom: 0;">
                    Rosa
                </p>
            </div>

        </div>

    </div>

</div>

<script>
    // Preview image mapping
    const previewImages = {
        'Purple': 'ThemePagePreviewPurple.png',
        'Orange': 'ThemePagePreviewPurple.png',
        'Yellow': 'ThemePagePreviewPurple.png',
        'Ciano': 'ThemePagePreviewPurple.png',
        'Blue': 'ThemePagePreviewPurple.png',
        'Green': 'ThemePagePreviewPurple.png',
        'Red': 'ThemePagePreviewPurple.png',
        'Pink': 'ThemePagePreviewPurple.png'
    };
    
    let currentTheme = 'Purple';
    let isSelecting = false;
    
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2000);
    }
    
    function updateThemeUI(theme) {
        // Update all theme options
        document.querySelectorAll('.theme-option').forEach(el => {
            el.classList.remove('theme-selected');
            const img = el.querySelector('.theme-preview');
            if (img) img.classList.remove('selected');
        });
        
        // Add selected class to current theme
        const selectedEl = document.getElementById('theme-' + theme);
        if (selectedEl) {
            selectedEl.classList.add('theme-selected');
            const img = selectedEl.querySelector('.theme-preview');
            if (img) img.classList.add('selected');
        }
        
        // Update preview image
        const previewImg = document.getElementById('previewImage');
        if (previewImages[theme]) {
            previewImg.src = previewImages[theme];
        }
        
        currentTheme = theme;
    }
    
    async function selectTheme(theme) {
        if (isSelecting || theme === currentTheme) return;
        
        isSelecting = true;
        
        // Update UI immediately
        updateThemeUI(theme);
        
        try {
            const formData = new FormData();
            formData.append('theme', theme);
            
            const response = await fetch('setTheme.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Tema atualizado com sucesso!');
                // Reload to apply new theme colors
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(result.message || 'Erro ao atualizar tema');
                // Revert UI on error
                updateThemeUI(currentTheme);
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Erro ao conectar com o servidor');
            updateThemeUI(currentTheme);
        } finally {
            isSelecting = false;
        }
    }
    
    // Initialize - check current theme from cookie or database
    document.addEventListener('DOMContentLoaded', function() {
        // You could add an AJAX call here to get the current theme from the server
        // For now, we'll use localStorage as a fallback
        const savedTheme = localStorage.getItem('userTheme');
        if (savedTheme) {
            updateThemeUI(savedTheme);
        }
    });
</script>

</body>
</html>
