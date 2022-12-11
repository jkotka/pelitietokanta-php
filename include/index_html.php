<?=template_header(GAME_DATABASE)?>

<h1><i class="fas fa-home fa-sm"></i>&nbsp;<?=HOME?></h1>

<div class="index-flex">
    <table id="game-stats">
        <thead>
            <tr>
                <td class="left"><?=GAMES?></td>
                <td><?=num_format($games_total, 0, 0)?></td>
            </tr>
        </thead>
        <?php if (!empty($games_total) && !empty($played_hours)): ?>
        <tbody>
            <tr>
                <td class="left"><?=BEATEN?></td>
                <td class="center"><?=num_format($beaten_total, 0, 0)?></td>
            </tr>
            <tr>
                <td class="left"><?=HOURS_PLAYED?></td>
                <td class="center dotted" title="<?=num_format($played_hours/24, 0, 0)?>&nbsp;<?=DAYS_SHORT?>&nbsp;/&nbsp;<?=num_format($played_hours/24/365, 2, 0)?>&nbsp;<?=YEARS_SHORT?>">
                    <?=num_format($played_hours, 0, 0)?>
                </td>
            </tr>
            <tr>
                <td class="left"><?=HOURS_PER_DAY?></td>
                <td class="center"><?=dec_time($played_hours/$played_days, 0)?></td>
            </tr>
            <tr>
                <td class="left"><?=HOURS_PER_GAME?></td>
                <td class="center"><?=dec_time($played_hours/$played_total, 0)?></td>
            </tr>
        </tbody>
        <?php endif; ?>
    </table>

    <table class="latest added">
        <thead>
            <tr>
                <td colspan="5"><a class="white" href="library.php?sort=created&amp;order=desc"><?=LAST_ADDED?>&nbsp;<i class="fas fa-caret-right fa-sm"></i></a></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach($last_added as $latest):
                // Set thumbnails - get parent ids recursively if set
                $images['main'] = $thumbnails . $latest['id'] . '_thumb.jpg';
                $images['placeholder'] = $thumbnails . 'placeholder_thumb.jpg';
                $images['parent2'] = $images['parent1'] = $images['root'] = '';
                if (!empty($latest['parent_id'])) {
                    $parents = get_parents($latest['parent_id'], $pdo);
                    $images['parent2'] = !empty($parents[2]['id']) ? $thumbnails . $parents[2]['id'] . '_thumb.jpg' : '';
                    $images['parent1'] = !empty($parents[1]['id']) ? $thumbnails . $parents[1]['id'] . '_thumb.jpg' : '';
                    $images['root'] = $thumbnails . $parents[0]['id'] . '_thumb.jpg';
                }
                ?>
                <td class="center top">
                    <a href="title.php?t=<?=$latest['id']?>">
                        <img class="thumbnail" src="<?=get_image($images)?>" alt="<?=clean($latest['title'])?>">
                        <div><?=clean($latest['platform'])?></div>
                        <span><?=clean($latest['title'] . $latest['parent_title'])?></span>
                    </a>                    
                </td>
                <?php endforeach; ?>
            </tr> 
        </tbody>
    </table>

    <table id="purchase-stats">
        <thead>
            <tr>
                <td class="left"><?=PURCHASED?></td>
                <td class="center"><?=num_format($purchases, 0, 0)?></td>
            </tr>
        </thead>
        <?php if (!empty($sum_total)): ?>
        <tbody>
            <tr>
                <td class="left"><?=TOTAL_PRICE?></td>
                <td class="center"><?=CURRENCY_BEFORE?><?=num_format($sum_total, 2, 0)?><?=CURRENCY_AFTER?></td>
            </tr>
            <tr>
                <td class="left"><?=AVERAGE_PRICE?></td>
                <td class="center"><?=CURRENCY_BEFORE?><?=num_format($sum_total/$purchases, 2, 0)?><?=CURRENCY_AFTER?></td>
            </tr>
            <tr>
                <td class="left"><?=WISHLISTED?></td>
                <td class="center"><?=$wishlisted?></td>
            </tr>
            <tr>
                <td class="left"><?=BACKLOGGED?></td>
                <td class="center"><?=$backlogged?></td>
            </tr>
        </tbody>
        <?php endif; ?>
    </table>

    <table class="latest played">
        <thead>
            <tr>
                <td colspan="5"><a class="white" href="played.php?sort=playstop&amp;order=desc"><?=LAST_PLAYED?>&nbsp;<i class="fas fa-caret-right fa-sm"></i></a></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach($last_played as $played):
                // Set thumbnails - get parent ids recursively if set
                $images['main'] = $thumbnails . $played['id'] . '_thumb.jpg';
                $images['placeholder'] = $thumbnails . 'placeholder_thumb.jpg';
                $images['parent2'] = $images['parent1'] = $images['root'] = '';
                if (!empty($played['parent_id'])) {
                    $parents = get_parents($played['parent_id'], $pdo);
                    $images['parent2'] = !empty($parents[2]['id']) ? $thumbnails . $parents[2]['id'] . '_thumb.jpg' : '';
                    $images['parent1'] = !empty($parents[1]['id']) ? $thumbnails . $parents[1]['id'] . '_thumb.jpg' : '';
                    $images['root'] = $thumbnails . $parents[0]['id'] . '_thumb.jpg';
                }
                ?>
                <td class="center top">
                    <a href="title.php?t=<?=$played['id']?>">
                        <img class="thumbnail" src="<?=get_image($images)?>" alt="<?=clean($played['title'])?>">
                        <div><?=clean($played['platform'])?></div>
                        <span><?=clean($played['title'])?></span>
                    </a>
                </td>
                <?php endforeach; ?>
            </tr> 
        </tbody>
    </table>

    <table class="latest purchased">
        <thead>
            <tr>
                <td colspan="5"><a class="white" href="purchased.php?sort=purchased&amp;order=desc"><?=LAST_PURCHASED?>&nbsp;<i class="fas fa-caret-right fa-sm"></i></a></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php foreach($last_purchased as $purchased):
                // Set thumbnails - get parent ids recursively if set
                $images['main'] = $thumbnails . $purchased['id'] . '_thumb.jpg';
                $images['placeholder'] = $thumbnails . 'placeholder_thumb.jpg';
                $images['parent2'] = $images['parent1'] = $images['root'] = '';
                if (!empty($purchased['parent_id'])) {
                    $parents = get_parents($purchased['parent_id'], $pdo);
                    $images['parent2'] = !empty($parents[2]['id']) ? $thumbnails . $parents[2]['id'] . '_thumb.jpg' : '';
                    $images['parent1'] = !empty($parents[1]['id']) ? $thumbnails . $parents[1]['id'] . '_thumb.jpg' : '';
                    $images['root'] = $thumbnails . $parents[0]['id'] . '_thumb.jpg';
                }
                ?>
                <td class="center top">
                    <a href="title.php?t=<?=$purchased['id']?>">
                        <img class="thumbnail" src="<?=get_image($images)?>" alt="<?=clean($purchased['title'])?>">
                        <div><?=clean($purchased['platform'])?></div>
                        <span><?=clean($purchased['title'] . $purchased['parent_title'])?></span>
                    </a>
                </td>
                <?php endforeach; ?>
            </tr> 
        </tbody>
    </table>
</div>

<?=template_footer()?>