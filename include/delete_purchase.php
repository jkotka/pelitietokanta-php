<?=template_header(DELETE_PURCHASE)?>

<h1><?=DELETE_PURCHASE?></h1>

<table class="deletelist">
    <thead>
        <tr>
            <td class="left"><?=TITLE?></td>
            <td><?=PLATFORM?></td>
            <td><?=MEDIATYPE?></td>
            <td><?=PRICE?></td>
            <td><?=PAYMETHOD?></td>
            <td><?=STORE?></td>
            <td><?=PURCHASED?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-title="" class="truncate mobile-header" title="<?=clean($purchase['title'])?>"><?=clean($purchase['title'])?></td>
            <td data-title="<?=PLATFORM?>" class="center"><?=clean($purchase['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>" class="center"><?=clean($purchase['mediatype'])?></td>
            <td data-title="<?=PRICE?>" class="center"><?=CURRENCY_BEFORE?><?=num_format($purchase['price'], 2, 0)?><?=CURRENCY_AFTER?></td>
            <td data-title="<?=PAYMETHOD?>" class="center"><?=clean($purchase['paymethod'])?></td>
            <td data-title="<?=STORE?>" class="center"><?=clean($purchase['store'])?></td>
            <td data-title="<?=PURCHASED?>" class="center"><?=date_cnv($purchase['purchased'], 'date', '')?></td>
        </tr>
    </tbody>
</table>

<a class="button red" href="<?=$thisfile?>?purchase=<?=$purchase_id?>&confirm=yes"><?=DELETE?></a>
<a class="button green" href="<?=$thisfile?>?purchase=<?=$purchase_id?>&confirm=no"><?=CANCEL?></a>