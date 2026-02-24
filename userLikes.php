<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        include "PhpShits/conn.php";
        $me = $_COOKIE['UserId'];

        foreach($_POST['gostos'] as $selected_interest) {
            $sql = "INSERT INTO users_likes (user_id, likes_id) VALUES ('$me', '$selected_interest')";
            $conn->query($sql);
        }

        echo "<script>window.location.href = 'endLogin.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verum - Categorias</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="colors.php">
    <base href="/Project-Verum/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="app-container" style="display: block;">
    <div class="categories-box">
        <h2>Selecione os seus gostos para costumizar o Verum:</h2>
        
        <!-- Categoria -->
        <div class="category">
            <h3>Arte:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='arte-digital' onclick="changeCategory('arte-digital')">Arte Digital</button>
                <button type='button' class="chip" id='fotografias' onclick="changeCategory('fotografias')">Fotografias</button>
                <button type='button' class="chip" id='comics' onclick="changeCategory('comics')">Comics</button>
                <button type='button' class="chip" id='ocs' onclick="changeCategory('ocs')">OCS</button>
                <button type='button' class="chip" id='fanarts' onclick="changeCategory('fanarts')">Fanarts</button>
                <button type='button' class="chip" id='pixel-art' onclick="changeCategory('pixel-art')">Pixel Art</button>
            </div>
        </div>
        
        <!-- Categoria -->
        <div class="category">
            <h3>Anime:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='webcomic' onclick="changeCategory('webcomic')">Webcomic</button>
                <button type='button' class="chip" id='naruto' onclick="changeCategory('naruto')">Naruto</button>
                <button type='button' class="chip" id='jujutsu-kaisen' onclick="changeCategory('jujutsu-kaisen')">Jujutsu Kaisen</button>
                <button type='button' class="chip" id='jojo' onclick="changeCategory('jojo')">Jojo</button>
                <button type='button' class="chip" id='my-hero-academy' onclick="changeCategory('my-hero-academy')">My hero Academy</button>
                <button type='button' class="chip"  id='bungou-stray-dogs' onclick="changeCategory('bungou-stray-dogs')">Bungou Stray Dogs</button>
            </div>
        </div>


        <!-- Categoria -->
        <div class="category">
            <h3>Memes:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='engraçadinhos' onclick="changeCategory('engraçadinhos')">Engraçadinhos</button>
                <button type='button' class="chip" id='shitpost' onclick="changeCategory('shitpost')">shitpost</button>
                <button type='button' class="chip" id='humor-acido' onclick="changeCategory('humor-acido')">Humor Acido</button>
            </div>
        </div>

        <div class="category">
            <h3>Literatura:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='percy-jackson' onclick="changeCategory('percy-jackson')">Percy Jakson</button>
                <button type='button' class="chip" id='book-worm' onclick="changeCategory('book-worm')">Book Worm</button>
                <button type='button' class="chip" id='mangas' onclick="changeCategory('mangas')">Mangas</button>
                <button type='button' class="chip" id='romances' onclick="changeCategory('romances')">Romances</button>
                <button type='button' class="chip" id='biografia' onclick="changeCategory('biografia')">Biografia</button>
                <button type='button' class="chip" id='poesias' onclick="changeCategory('poesias')">Poesias</button>
            </div>
        </div>

        <div class="category">
            <h3>Jogos:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='minecraft' onclick="changeCategory('minecraft')">Minecraft</button>
                <button type='button' class="chip" id='fnaf' onclick="changeCategory('fnaf')">FNAF</button>
                <button type='button' class="chip" id='sims' onclick="changeCategory('sims')">Sims</button>
                <button type='button' class="chip" id='dnd' onclick="changeCategory('dnd')">DND</button>
                <button type='button' class="chip" id='lol' onclick="changeCategory('lol')">LOL</button>
                <button type='button' class="chip" id='indie' onclick="changeCategory('indie')">Indie</button>
            </div>
        </div>

        <div class="category">
            <h3>Musicas e Bandas:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='emo' onclick="changeCategory('emo')">Emo</button>
                <button type='button' class="chip" id='rap' onclick="changeCategory('rap')">Rap</button>
                <button type='button' class="chip" id='kpop' onclick="changeCategory('kpop')">Kpop</button>
                <button type='button' class="chip" id='indie-music' onclick="changeCategory('indie-music')">Indie Music</button>
                <button type='button' class="chip" id='vocaloid' onclick="changeCategory('vocaloid')">Vocaloid</button>
                <button type='button' class="chip" id='pop' onclick="changeCategory('pop')">Pop</button>
            </div>
        </div>

        <div class="category" style="margin-bottom: 64px;">
            <h3>Programas de TV:</h3>
            <div class="chip-group">
                <button type='button' class="chip" id='arcane' onclick="changeCategory('arcane')">Arcane</button>
                <button type='button' class="chip" id='the-own-house' onclick="changeCategory('the-own-house')">The Own House</button>
                <button type='button' class="chip" id='tadc' onclick="changeCategory('tadc')">TADC</button>
                <button type='button' class="chip" id='supernatural' onclick="changeCategory('supernatural')">Supernatural</button>
                <button type='button' class="chip" id='the-office' onclick="changeCategory('the-office')">The Office</button>
                <button type='button' class="chip" id='animacoes' onclick="changeCategory('animacoes')">Animações</button>
            </div>
        </div>
        
    </div>
    
    <form method="POST">
        <div style="position: fixed; bottom: 0px; background: var(--div); width: 100%; padding: 12px;"><button class="btn btn-primary">Salvar e continuar</button></div>

        <div style="display: none;">
            <!-- arte -->
            <input type="checkbox" name="gostos[]" value="1" id="check-arte-digital">
            <input type="checkbox" name="gostos[]" value="2" id="check-fotografias">
            <input type="checkbox" name="gostos[]" value="3" id="check-comics">
            <input type="checkbox" name="gostos[]" value="4" id="check-ocs">
            <input type="checkbox" name="gostos[]" value="5" id="check-fanarts">
            <input type="checkbox" name="gostos[]" value="6" id="check-pixel-art">
            <!-- anime -->
            <input type="checkbox" name="gostos[]" value="7" id="check-webcomic">
            <input type="checkbox" name="gostos[]" value="8" id="check-naruto">
            <input type="checkbox" name="gostos[]" value="9"  id="check-jujutsu-kaisen">
            <input type="checkbox" name="gostos[]" value="10" id="check-jojo">
            <input type="checkbox" name="gostos[]" value="11" id="check-my-hero-academy">
            <input type="checkbox" name="gostos[]" value="12" id="check-bungou-stray-dogs">
            <!-- memes -->
            <input type="checkbox" name="gostos[]" value="13" id="check-engraçadinhos">
            <input type="checkbox" name="gostos[]" value="14" id="check-shitpost">
            <input type="checkbox" name="gostos[]" value="15" id="check-humor-acido">
            <!-- literatura -->
            <input type="checkbox" name="gostos[]" value="16" id="check-percy-jackson">
            <input type="checkbox" name="gostos[]" value="17" id="check-book-worm">
            <input type="checkbox" name="gostos[]" value="18" id="check-mangas">
            <input type="checkbox" name="gostos[]" value="19" id="check-romances">
            <input type="checkbox" name="gostos[]" value="20" id="check-biografia">
            <input type="checkbox" name="gostos[]" value="21" id="check-poesias">
            <!-- jogos -->
            <input type="checkbox" name="gostos[]" value="22" id="check-minecraft">
            <input type="checkbox" name="gostos[]" value="23" id="check-fnaf">
            <input type="checkbox" name="gostos[]" value="24" id="check-sims">
            <input type="checkbox" name="gostos[]" value="25" id="check-dnd">
            <input type="checkbox" name="gostos[]" value="26" id="check-lol">
            <input type="checkbox" name="gostos[]" value="27" id="check-indie">
            <!-- musicas -->
            <input type="checkbox" name="gostos[]" value="28" id="check-emo">
            <input type="checkbox" name="gostos[]" value="29" id="check-rap">
            <input type="checkbox" name="gostos[]" value="30" id="check-kpop">
            <input type="checkbox" name="gostos[]" value="31" id="check-indie-music">
            <input type="checkbox" name="gostos[]" value="32" id="check-vocaloid">
            <input type="checkbox" name="gostos[]" value="33" id="check-pop">
            <!-- TV -->
            <input type="checkbox" name="gostos[]" value="34" id="check-arcane">
            <input type="checkbox" name="gostos[]" value="35" id="check-the-own-house">
            <input type="checkbox" name="gostos[]" value="36" id="check-tadc">
            <input type="checkbox" name="gostos[]" value="37" id="check-supernatural">
            <input type="checkbox" name="gostos[]" value="38" id="check-the-office">
            <input type="checkbox" name="gostos[]" value="39" id="check-animacoes">
        </div>
    </form>
</div>
<script>
    function changeCategory(category){
        document.getElementById(category).classList.toggle("selected");
        document.getElementById(`check-${category}`).click("selected"); 
    }
</script>
</body>
</html>
