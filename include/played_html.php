<?=template_header(PLAYED)?>

<h1><i class="far fa-check-circle fa-sm"></i>&nbsp;<?=PLAYED?></h1>

<label class="filter-label" for="filter-toggle"><?=FILTER?><i class="fas fa-caret-down"></i></label>
<input class="toggle-check" type="checkbox" id="filter-toggle">

<form class="filter hidden" action="<?=$thisfile?>" method="get">
    <input type="hidden" name="sort" value="<?=$column?>">
    <input type="hidden" name="order" value="<?=$order?>">
    <input type="hidden" name="records" value="<?=$records_per_page?>">

    <input type="text" name="title" id="filter-title" value="<?=clean($form_title)?>" minlength="2" maxlength="100" placeholder="<?=TITLE?>">

    <label class="mobile-label" for="published"><?=PUBLISHED?>:</label>
    <select class="multiselect" id="published" name="published[]" multiple size="3">
        <?php for($year = date('Y') + 1; $year >= 1980; $year = $year - 1): $selected = in_array($year, explode(',', $form_published)) == $year ? ' selected' : '' ?>
        <option value="<?=$year?>"<?=$selected?>><?=$year?></option>
        <?php endfor; ?>
    </select>

    <label class="mobile-label" for="platform"><?=PLATFORM?>:</label>
    <select class="multiselect" id="platform" name="platform[]" multiple size="3">
        <?php $platforms = get_list('*', 'platform', 'platform_name', $pdo); foreach($platforms as $platform): $selected = in_array($platform['platform_id'], explode(',', $form_platform)) ? ' selected' : ''; ?>
        <option value="<?=$platform['platform_id']?>"<?=$selected?>><?=clean($platform['platform_name'])?></option>
        <?php endforeach; ?>
    </select>

    <label class="mobile-label" for="mediatype"><?=MEDIATYPE?>:</label>
    <select class="multiselect" id="mediatype" name="mediatype[]" multiple size="3">
        <?php $mediatypes = get_list('*', 'mediatype', 'mediatype_name', $pdo); foreach($mediatypes as $mediatype): $selected = in_array($mediatype['mediatype_id'], explode(',', $form_mediatype)) ? ' selected' : ''; ?>
        <option value="<?=$mediatype['mediatype_id']?>"<?=$selected?>><?=clean($mediatype['mediatype_name'])?></option>
        <?php endforeach; ?>
    </select>

    <label class="mobile-label" for="from"><?=START_DATE?>:</label>
    <input type="date" name="from" id="from" min="1980-01-01" max="<?=date('Y-m-d')?>" value="<?=$form_from?>">

    <label class="mobile-label" for="to"><?=END_DATE?>:</label>
    <input type="date" name="to" id="to" min="1980-01-01" max="<?=date('Y-m-d')?>" value="<?=$form_to?>">

    <?=filter_show($form_show)?>

    <button class="filter-submit" type="submit"><i class="fas fa-redo-alt fa-sm"></i></button>
</form>

<div class="total">
    <span>
        <?=$total?>: <?=num_format($played_totals['rowcount'], 0, 0)?>
        <?php if (!empty($played_totals['totalhours']) && !empty($played_totals['totaldays'])): ?>
        &nbsp;&bull;&nbsp;
        <?=HOURS_PLAYED?>: <?=num_format($played_totals['totalhours'], 0, 0)?>
        &nbsp;&bull;&nbsp;
        <?=HOURS_PER_DAY?>: <?=dec_time($played_totals['totalhours']/$played_totals['totaldays'], 0)?>
        <?php endif; ?>
    </span>

    <form class="records-per-page" action="<?=$thisfile?>" method="get">
        <input type="hidden" name="sort" value="<?=$column?>">
        <input type="hidden" name="order" value="<?=$order?>">
        <?php records_dropdown($records_per_page) ?>
        <input type="hidden" name="title" value="<?=clean($form_title)?>">
        <input type="hidden" name="published[]" value="<?=$form_published?>">
        <input type="hidden" name="platform[]" value="<?=$form_platform?>">
        <input type="hidden" name="mediatype[]" value="<?=$form_mediatype?>">
        <input type="hidden" name="from" value="<?=$form_from?>">
        <input type="hidden" name="to" value="<?=$form_to?>">
        <input type="hidden" name="show" value="<?=$form_show?>">
        <button class="records-submit" type="submit"><i class="fas fa-redo-alt fa-sm"></i></button>
    </form>
</div>

<div class="mobile-sort">
    <?php foreach ($columns as $col_txt => $col_value): $sort_link = sort_link($col_value, $column, $order); ?>
    <span><a class="white" href="<?=$thisfile?>?page=<?=$page?>&amp;sort=<?=$col_value?>&amp;order=<?=$sort_link["asc_desc"]?><?=$filter_url?>"><?=$col_txt?>&nbsp;<?=$sort_link["sort_icon"]?></a></span>    
    <?php endforeach; ?>
</div>

<table class="titlelist">
    <thead>
        <tr>
            <td>#</td>
            <?php foreach ($columns as $col_txt => $col_value): $sort_link = sort_link($col_value, $column, $order); ?>
            <td class="<?=$col_value?>"><a class="white" href="<?=$thisfile?>?page=<?=$page?>&amp;sort=<?=$col_value?>&amp;order=<?=$sort_link["asc_desc"]?><?=$filter_url?>"><?=$col_txt?>&nbsp;<?=$sort_link["sort_icon"]?></a></td>    
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($played_titles as $played): ?>
        <tr>
            <td data-title="" class="center min-width"><?=$row_count++?></td>
            <td data-title="" class="truncate mobile-header"><a href="title.php?t=<?=$played['id']?>"><?=clean($played['title'])?></a></td>
            <td data-title="<?=PUBLISHED?>" class="center"><?=$played['published']?></td>
            <td data-title="<?=PLATFORM?>"><?=clean($played['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>"><?=clean($played['mediatype'])?></td>
            <td data-title="<?=STARTED?>" class="center"><?=date_cnv($played['playstart'], 'date', '')?></td>
            <td data-title="<?=STOPPED?>" class="center"><?=date_cnv($played['playstop'], 'date', '')?></td>
            <td data-title="<?=HOURS?>" class="center"><?=num_format($played['playhours'], 0, '')?></td>
            <td data-title="<?=BEATEN?>" class="center"><?=status_icon($played['beaten'])?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?=template_pagination($thisfile, $page, $column, $order, $filter_url, $played_totals['rowcount'], $records_per_page)?>
<?=template_footer()?>