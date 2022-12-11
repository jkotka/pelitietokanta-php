<h2>
    <?=$titledetails['titletype'] === 2 ? GAMES : ADDONS ?>
    (<?=count($items)?>)
    <?php if (!empty($items_total)): ?>
    (<?=CURRENCY_BEFORE?><?=num_format($items_total, 2, 0)?><?=CURRENCY_AFTER?>)
    <?php endif; ?>
</h2>

<table class="titledetails">
    <thead>
        <tr>
            <td class="left med-width"><?=TITLE?></td>
            <td><?=PUBLISHED?></td>
            <td><?=MEDIATYPE?></td>
            <td><?=OWNED?></td>
            <td><?=INFO?></td>
            <td class="min-width" colspan="2"></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td data-title="" class="truncate mobile-header"><a href="<?=$thisfile?>?t=<?=$item['item_id']?>"><?=clean($item['title'])?></a></td>
            <td data-title="<?=PUBLISHED?>" class="center"><?=$item['published']?></td>
            <td data-title="<?=MEDIATYPE?>" class="center"><?=clean($item['mediatype'])?></td>
            <td data-title="<?=OWNED?>" class="center"><?=status_icon($item['itemstatus'])?></td>
            <td data-title="<?=INFO?>" class="center truncate" title="<?=clean($item['info'])?>"><?=clean($item['info'])?></td>
            <td data-title="<?=EDIT?>" class="center min-width">
                <a href="<?=$thisfile?>?t=<?=$title_id?>&amp;item=<?=$item['item_id']?>#item"><i class="fas fa-pen"></i></a>
            </td>
            <td data-title="<?=DELETE?>" class="center min-width">
                <a href="delete.php?t=<?=$title_id?>&amp;item=<?=$item['item_id']?>"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
        <?php endforeach ?>
        <?php if (count($items) < 1): ?>
        <tr>
            <td class="no-data" colspan="7"><?=$titledetails['titletype'] === 2 ? NO_GAMES : NO_ADDONS ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>