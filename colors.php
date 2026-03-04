<?php
    header('Content-Type: text/css; charset=utf-8');
    
    include 'PhpShits/conn.php';
    
    $pallete = $_COOKIE['theme'] ?? "purple";

?>

<?php if($pallete == "purple"): ?>
    :root{
        --background-app: #1a1522;
        --on-background: #ece7f6;
        --div: #2a174d;
        --on-div: #d0bcff;
        --button: #3f2b66;
        --on-button: #b7a4e5 ;
    }
<?php endif; ?>

<?php if($pallete == "orange"): ?>
    :root{
        --background-app: #3b2300;
        --on-background: #ffb86c;
        --div: #5a3a10;
        --on-div: #e0a25a;
        --button: #222115;
        --on-button: #f6efe7;
    }
<?php endif; ?>

<?php if($pallete == "yellow"): ?>
    :root{
        --background-app: #2f2a00;
        --on-background: #e6d76a;
        --div: #4a4300;
        --on-div: #cfc25f;
        --button: #1f2215;
        --on-button: #f5f6e7;
    }
<?php endif; ?>

<?php if($pallete == "cyan"): ?>
    :root{
        --background-app: #003737;
        --on-background: #6fe3e1;
        --div: #0f4f4f;
        --on-div: #5cc7c5;
        --button: #152022;
        --on-button: #e7f2f6;
    }
<?php endif; ?>

<?php if($pallete == "blue"): ?>
    :root{
        --background-app: #001a33;
        --on-background: #a6c8ff;
        --div: #0d2d5a;
        --on-div: #8cb4e6;
        --button: #152033;
        --on-button: #e7eeff;
    }
<?php endif; ?>

<?php if($pallete == "green"): ?>
    :root{
        --background-app: #002200;
        --on-background: #8aff8a;
        --div: #0a330a;
        --on-div: #6bc46b;
        --button: #152215;
        --on-button: #e7ffe7;
    }
<?php endif; ?>

<?php if($pallete == "red"): ?>
    :root{
        --background-app: #330000;
        --on-background: #ffaaaa;
        --div: #5a0d0d;
        --on-div: #e68a8a;
        --button: #331515;
        --on-button: #ffe7e7;
    }
<?php endif; ?>

<?php if($pallete == "Pink"): ?>
    :root{
        --background-app: #33001a;
        --on-background: #ffa6d6;
        --div: #5a0d2d;
        --on-div: #e68ab4;
        --button: #33151f;
        --on-button: #ffe7f0;
    }
<?php endif; ?>
