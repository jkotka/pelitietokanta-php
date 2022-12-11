<?php header('Cache-Control: no-cache, must-revalidate'); ?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$title?></title>
        <link rel="shortcut icon" href="favicon.ico">
        <link rel="stylesheet" href="style.css?<?=filemtime('style.css')?>" type="text/css">
        <link rel="stylesheet" href="fontawesome/css/all.min.css">
        <link rel="stylesheet" href="jquery/jquery-ui.min.css" type="text/css">
        <script src="jquery/jquery.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
    </head>
<body>

<div class="menu">
    <label class="menu-label" for="menu-toggle"><span><i class="fas fa-server fa-sm"></i>&nbsp;<?=GAME_DATABASE?></span><i class="fas fa-caret-down fa-sm"></i></label>
    <input class="menu-check" type="checkbox" id="menu-toggle">

    <a class="nav" href="settings.php">
        <i class="fas fa-cog fa-sm"></i>
        <?=SETTINGS?>
    </a>

    <a class="nav" href="index.php">
        <i class="fas fa-home fa-sm"></i>
        <?=HOME?>
    </a>

    <form class="search" action="library.php" method="get" autocomplete="off">
        <input type="text" name="title" id="search" minlength="2" maxlength="100" placeholder="<?=SEARCH?>" autofocus>
    </form>

    <a class="nav" href="library.php?sort=created&amp;order=desc&amp;show=owned">
        <i class="fas fa-dice-d6 fa-sm"></i>
        <?=LIBRARY?>
    </a>

    <a class="nav" href="played.php?sort=playstop&amp;order=desc&amp;show=beaten">
        <i class="far fa-check-circle fa-sm"></i>
        <?=PLAYED?>
    </a>

    <a class="nav" href="purchased.php?sort=purchased&amp;order=desc">
        <i class="fas fa-coins fa-sm"></i>
        <?=PURCHASED?>
    </a>
    
    <a class="nav" href="library.php?sort=modified&amp;order=desc&amp;show=wishlist">
        <i class="far fa-heart fa-sm"></i>
        <?=WISHLIST?>
    </a>

    <a class="nav" href="library.php?sort=modified&amp;order=desc&amp;show=backlog">
        <i class="far fa-list-alt fa-sm"></i>
        <?=BACKLOG?>
    </a>

    <a class="nav" href="insert-update.php">
        <i class="fas fa-plus-circle fa-sm"></i>
        <?=ADD?>
    </a>
</div>

<?=message()?>
<div class="content">