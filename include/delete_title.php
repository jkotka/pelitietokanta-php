<?=template_header(DELETE . ' ' . strtolower(type_text($titledetails['titletype'])))?>

<h1><?=DELETE . ' ' . strtolower(type_text($titledetails['titletype']))?></h1>

<table class="deletelist">
    <thead>
        <tr>
            <td class="left"><?=TITLE?></td>
            <td><?=PUBLISHED?></td>
            <td><?=PLATFORM?></td>
            <td><?=MEDIATYPE?></td>
            <td><?=TITLETYPE?></td>
            <td><?=OWNED?></td>
            <td><?=INFO?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-title="" class="truncate mobile-header" title="<?=clean($titledetails['title'])?>"><?=clean($titledetails['title'])?></td>
            <td data-title="<?=PUBLISHED?>" class="center"><?=$titledetails['published']?></td>
            <td data-title="<?=PLATFORM?>" class="center"><?=clean($titledetails['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>" class="center"><?=clean($titledetails['mediatype'])?></td>
            <td data-title="<?=TITLETYPE?>" class="center"><?=type_text($titledetails['titletype'])?></td>
            <td data-title="<?=OWNED?>" class="center"><span><?=status_icon($titledetails['owned'])?></span></td>
            <td data-title="<?=INFO?>" class="center truncate" title="<?=clean($titledetails['info'])?>"><?=clean($titledetails['info'])?></td>
        </tr>
    </tbody>
</table>

<a class="button red" href="<?=$thisfile?>?t=<?=$title_id?>&confirm=yes"><?=DELETE?></a>
<a class="button green" href="<?=$thisfile?>?t=<?=$title_id?>&confirm=no"><?=CANCEL?></a>