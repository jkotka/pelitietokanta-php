<?=template_header(DELETE_GAMESTAT)?>

<h1><?=DELETE_GAMESTAT?></h1>

<table class="deletelist">
    <thead>
        <tr>
            <td class="left"><?=TITLE?></td>
            <td><?=PLATFORM?></td>
            <td><?=MEDIATYPE?></td>
            <td><?=STARTED?></td>
            <td><?=STOPPED?></td>
            <td><?=DAYS?></td>
            <td><?=HOURS?></td>
            <td><?=HOURS_PER_DAY_SHORT?></td>
            <td><?=BEATEN?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-title="" class="truncate mobile-header" title="<?=clean($stat['title'])?>"><?=clean($stat['title'])?></td>
            <td data-title="<?=PLATFORM?>" class="center"><?=clean($stat['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>" class="center"><?=clean($stat['mediatype'])?></td>
            <td data-title="<?=STARTED?>" class="center"><?=date_cnv($stat['playstart'], 'date', '')?></td>
            <td data-title="<?=STOPPED?>" class="center"><?=date_cnv($stat['playstop'], 'date', '')?></td>
            <td data-title="<?=DAYS?>" class="center"><?=num_format($stat['playdays'], 0, '')?></td>
            <td data-title="<?=HOURS?>" class="center"><?=num_format($stat['playhours'], 0, '')?></td>
            <td data-title="<?=HOURS_PER_DAY?>" class="center"><?=dec_time($stat['hoursperday'], '')?></td>
            <td data-title="<?=BEATEN?>" class="center"><?=status_icon($stat['beaten'])?></td>
        </tr>
    </tbody>
</table>

<a class="button red" href="<?=$thisfile?>?stat=<?=$stat_id?>&confirm=yes"><?=DELETE?></a>
<a class="button green" href="<?=$thisfile?>?stat=<?=$stat_id?>&confirm=no"><?=CANCEL?></a>