<h2>
    <?=PURCHASE_INFO?>
    (<?=count($purchases)?>)
    <?php if (!empty($purchases) && $title_total + $items_total_recursive > $purchases[0]['price']): ?>
    (<?=CURRENCY_BEFORE?><?=num_format($title_total + $items_total_recursive, 2, 0)?><?=CURRENCY_AFTER?>)
    <?php endif; ?>
</h2>

<table class="titledetails">
    <thead>
        <tr>
            <td><?=PRICE?></td>
            <td><?=PAYMETHOD?></td>
            <td><?=STORE?></td>
            <td><?=PURCHASED?></td>
            <td><?=INFO?></td>
            <td class="min-width" colspan="2"></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($purchases as $purchase): ?>
        <tr>
            <td data-title="<?=PRICE?>" class="center"><?=CURRENCY_BEFORE?><?=num_format($purchase['price'], 2, 0)?><?=CURRENCY_AFTER?></td>
            <td data-title="<?=PAYMETHOD?>" class="center"><?=clean($purchase['paymethod'])?></td>
            <td data-title="<?=STORE?>" class="center"><?=clean($purchase['store'])?></td>
            <td data-title="<?=PURCHASED?>" class="center"><?=date_cnv($purchase['purchased'], 'date', '')?></td>
            <td data-title="<?=INFO?>" class="center truncate" title="<?=clean($purchase['info'])?>"><?=clean($purchase['info'])?></td>
            <td data-title="<?=EDIT?>" class="center min-width">
                <a href="<?=$thisfile?>?t=<?=$title_id?>&amp;purchase=<?=$purchase['purchase_id']?>#purchase"><i class="fas fa-pen"></i></a>
            </td>
            <td data-title="<?=DELETE?>" class="center min-width">
                <a href="delete.php?purchase=<?=$purchase['purchase_id']?>"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
        <?php endforeach ?>
        <?php if (count($purchases) < 1): ?>
        <tr>
            <td class="no-data" colspan="7"><?=NO_PURCHASE_INFO?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>