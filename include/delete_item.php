<?=template_header(DELETE . ' ' . strtolower(type_text($item['itemtype'])))?>

<h1><?=DELETE . ' ' . strtolower(type_text($item['itemtype']))?></h1>

<table class="deletelist">
    <thead>
        <tr>
            <td class="left"><?=TITLE?></td>
            <td><?=PUBLISHED?></td>
            <td><?=PLATFORM?></td>
            <td><?=MEDIATYPE?></td>
            <td><?=TITLETYPE?></td>
            <td><?=OWNED?></td>
            <td><?=INCLUDED_IN?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-title="" class="truncate mobile-header" title="<?=clean($item['title'])?>"><?=clean($item['title'])?></td>
            <td data-title="<?=PUBLISHED?>" class="center"><?=$item['published']?></td>
            <td data-title="<?=PLATFORM?>" class="center"><?=clean($item['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>" class="center"><?=clean($item['mediatype'])?></td>
            <td data-title="<?=TITLETYPE?>" class="center"><?=type_text($item['itemtype'])?></td>
            <td data-title="<?=OWNED?>" class="center"><span><?=status_icon($item['owned'])?></span></td>
            <td data-title="<?=INCLUDED_IN?>" class="center truncate" title="<?=clean($item['parent_title'])?>"><?=clean($item['parent_title'])?></td>
        </tr>
    </tbody>
</table>

<a class="button red" href="<?=$thisfile?>?t=<?=$title_id?>&amp;item=<?=$item_id?>&confirm=yes"><?=DELETE?></a>
<a class="button green" href="<?=$thisfile?>?t=<?=$title_id?>&amp;item=<?=$item_id?>&confirm=no"><?=CANCEL?></a>