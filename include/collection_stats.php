<h2>
    <?=GAMESTATS?>
    (<?=count($stats)?>)
    <?=!empty($hours_total) ? '(' . $hours_total . '<span class="lowercase">' . HOURS_SHORT . '</span>)' : ''; ?>
</h2>

<table class="titledetails">
    <thead>
        <tr>
            <td class="left med-width"><?=TITLE?></td>
            <td><?=STARTED?></td>
            <td><?=STOPPED?></td>
            <td><?=DAYS?></td>
            <td><?=HOURS?></td>
            <td><?=HOURS_PER_DAY_SHORT?></td>
            <td class="min-width"><?=BEATEN?></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats as $stat):?>
        <tr class="limit">
            <td data-title="<?=TITLE?>"><a href="title.php?t=<?=$stat['title_id']?>"><?=clean($stat['title'])?></a></td>
            <td data-title="<?=STARTED?>" class="center"><?=date_cnv($stat['playstart'], 'date', '')?></td>
            <td data-title="<?=STOPPED?>" class="center"><?=date_cnv($stat['playstop'], 'date', '')?></td>
            <td data-title="<?=DAYS?>" class="center"><?=num_format($stat['playdays'], 0, '')?></td>
            <td data-title="<?=HOURS?>" class="center"><?=num_format($stat['playhours'], 0, '')?></td>
            <td data-title="<?=HOURS_PER_DAY?>" class="center"><?=dec_time($stat['hoursperday'], '')?></td>
            <td data-title="<?=BEATEN?>" class="center"><?=status_icon($stat['beaten'])?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>