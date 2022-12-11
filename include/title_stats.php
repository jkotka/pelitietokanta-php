<h2>
    <?=GAMESTATS?>
    (<?=count($stats)?>)
    <?php if (!empty($stats) && $hours_total > $stats[0]['playhours']): ?>
    (<?=$hours_total?> <span class="lowercase"><?=HOURS_SHORT?></span>)
    <?php endif; ?>
</h2>

<table class="titledetails">
    <thead>
        <tr>
            <td><?=STARTED?></td>
            <td><?=STOPPED?></td>
            <td><?=DAYS?></td>
            <td><?=HOURS?></td>
            <td><?=HOURS_PER_DAY_SHORT?></td>
            <td><?=BEATEN?></td>
            <td><?=INFO?></td>
            <td class="min-width" colspan="2"></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stats as $stat): ?>
        <tr>
            <td data-title="<?=STARTED?>" class="center"><?=date_cnv($stat['playstart'], 'date', '')?></td>
            <td data-title="<?=STOPPED?>" class="center"><?=date_cnv($stat['playstop'], 'date', '')?></td>
            <td data-title="<?=DAYS?>" class="center"><?=num_format($stat['playdays'], 0, '')?></td>
            <td data-title="<?=HOURS?>" class="center"><?=num_format($stat['playhours'], 0, '')?></td>
            <td data-title="<?=HOURS_PER_DAY?>" class="center"><?=dec_time($stat['hoursperday'], '')?></td>
            <td data-title="<?=BEATEN?>" class="center"><?=status_icon($stat['beaten'])?></td>
            <td data-title="<?=INFO?>" class="center truncate" title="<?=clean($stat['info'])?>"><?=clean($stat['info'])?></td>
            <td data-title="<?=EDIT?>" class="center min-width">
                <a href="<?=$thisfile?>?t=<?=$title_id?>&amp;stat=<?=$stat['stat_id']?>#stat"><i class="fas fa-pen"></i></a>
            </td>
            <td data-title="<?=DELETE?>" class="center min-width">
                <a href="delete.php?stat=<?=$stat['stat_id']?>"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
        <?php endforeach ?>
        <?php if (count($stats) < 1): ?>
        <tr>
            <td class="no-data" colspan="9"><?=NO_GAMESTATS?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>