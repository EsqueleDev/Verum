<?php
    $post_id = $_GET['postid'];
    $post_mine = $_GET['isMine'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Opções</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?id=1">
    <link rel="stylesheet" href="colors.php?id<?= rand(1,10000) ?>">
    <style>
        body{
            font-size: 24px;
        }
        .SideBarContent{
            grid-template-columns: 15% 85%;   
        }
    </style>
</head>
<body>

<div class="app-container">
    <header class="app-header">
        <button class="icon-btn" onclick="window.history.back()"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg></button>
        <span class="app-title">Opções</span>
        <span></span>
    </header>
    <span style='padding: 12px;'>
        <?php if($post_mine == 'true'): ?>
            <a href='editPost.php?postId=<?= $post_id ?>' class='SideBarContent'>
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M186.67-186.67H235L680-631l-48.33-48.33-445 444.33v48.33ZM120-120v-142l559.33-558.33q9.34-9 21.5-14 12.17-5 25.5-5 12.67 0 25 5 12.34 5 22 14.33L821-772q10 9.67 14.5 22t4.5 24.67q0 12.66-4.83 25.16-4.84 12.5-14.17 21.84L262-120H120Zm652.67-606-46-46 46 46Zm-117 71-24-24.33L680-631l-24.33-24Z"/></svg>
                <h3>Editar Post</h3>
            </a>
            <a href='deletePost.php?postId=<?= $post_id ?>' class='SideBarContent'>
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="m366-299.33 114-115.34 114.67 115.34 50-50.67-114-115.33 114-115.34-50-50.66L480-516 366-631.33l-50.67 50.66L430-465.33 315.33-350 366-299.33ZM267.33-120q-27 0-46.83-19.83-19.83-19.84-19.83-46.84V-740H160v-66.67h192V-840h256v33.33h192V-740h-40.67v553.33q0 27-19.83 46.84Q719.67-120 692.67-120H267.33Zm425.34-620H267.33v553.33h425.34V-740Zm-425.34 0v553.33V-740Z"/></svg>
                <h3>Deletar Post</h3>
            </a>
        <?php endif; ?>
        <a href='errorPage.php?code=1' class='SideBarContent'>
            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M200-120v-656.67q0-27 19.83-46.83 19.84-19.83 46.84-19.83h426.66q27 0 46.84 19.83Q760-803.67 760-776.67V-120L480-240 200-120Zm66.67-101.33L480-312l213.33 90.67v-555.34H266.67v555.34Zm0-555.34h426.66-426.66Z"/></svg>
            <h3>Salvar</h3>
        </a>
        <a href='errorPage.php?code=1' class='SideBarContent'>
            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M684-80q-48.33 0-82.17-33.83Q568-147.67 568-196q0-7.33 4.33-32l-293-171.33q-16.18 16.56-37.42 25.94Q220.67-364 196-364q-48.33 0-82.17-33.67Q80-431.33 80-480t33.83-82.33Q147.67-596 196-596q24 0 45 9.03T278-562l294.33-170q-2-7.67-3.16-15.5Q568-755.33 568-764q0-48.33 33.83-82.17Q635.67-880 684-880t82.17 33.83Q800-812.33 800-764t-33.83 82.17Q732.33-648 684-648q-23.52 0-44.09-8.83-20.58-8.84-36.58-23.84L307-513.33q2.67 7.66 3.83 16.16 1.17 8.5 1.17 16.84 0 8.33-.83 15.5-.84 7.16-2.84 14.83L604-280q16-15 36.4-23.5 20.39-8.5 43.7-8.5 48.57 0 82.23 33.83Q800-244.33 800-196t-33.83 82.17Q732.33-80 684-80Zm.02-66.67q20.98 0 35.15-14.19 14.16-14.19 14.16-35.16 0-20.98-14.19-35.15-14.19-14.16-35.16-14.16-20.98 0-35.15 14.19-14.16 14.19-14.16 35.16 0 20.98 14.19 35.15 14.19 14.16 35.16 14.16Zm-488-284q20.98 0 35.15-14.19 14.16-14.19 14.16-35.16 0-20.98-14.19-35.15-14.19-14.16-35.16-14.16-20.98 0-35.15 14.19-14.16 14.19-14.16 35.16 0 20.98 14.19 35.15 14.19 14.16 35.16 14.16Zm523.15-298.19q14.16-14.19 14.16-35.16 0-20.98-14.19-35.15-14.19-14.16-35.16-14.16-20.98 0-35.15 14.19-14.16 14.19-14.16 35.16 0 20.98 14.19 35.15 14.19 14.16 35.16 14.16 20.98 0 35.15-14.19ZM684-196ZM196-480Zm488-284Z"/></svg>
            <h3>Compartilhar</h3>
        </a>
    </span>
</div>
</body>
</html>