<?=template_header($page_title)?>

<h1><?=$header?></h1>

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

    <label class="mobile-label" for="titletype"><?=TITLETYPE?>:</label>
    <select class="multiselect" id="titletype" name="titletype[]" multiple size="3">
        <?php $titletypes = get_list('title_type', 'title', 'title_type', $pdo); foreach($titletypes as $titletype): $selected = in_array($titletype['title_type'], explode(',', $form_titletype)) ? ' selected' : ''; ?>
        <option value="<?=$titletype['title_type']?>"<?=$selected?>><?=type_text($titletype['title_type'])?></option>
        <?php endforeach; ?>
    </select>

    <?=filter_show($form_show)?>

    <button class="filter-submit" type="submit"><i class="fas fa-redo-alt fa-sm"></i></button>
</form>

<div class="total">
    <span>
        <?=$total?>: <?=num_format($library_rows, 0, 0)?>
    </span>

    <form class="records-per-page" action="<?=$thisfile?>" method="get">
        <input type="hidden" name="sort" value="<?=$column?>">
        <input type="hidden" name="order" value="<?=$order?>">
        <?php records_dropdown($records_per_page) ?>
        <input type="hidden" name="title" value="<?=clean($form_title)?>">
        <input type="hidden" name="published[]" value="<?=$form_published?>">
        <input type="hidden" name="platform[]" value="<?=$form_platform?>">
        <input type="hidden" name="mediatype[]" value="<?=$form_mediatype?>">
        <input type="hidden" name="titletype[]" value="<?=$form_titletype?>">
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
        <?php foreach($library as $title): ?>
        <tr>
            <td data-title="" class="center min-width"><?=$row_count++?></td>
            <?php
                $parents = get_parents($title['id'], $pdo);
                $root_title = !empty($title['parent_id']) ? ' (' . $parents[0]['title'] . ')' : '';
            ?>
            <td data-title="" class="truncate mobile-header"><a href="title.php?t=<?=$title['id']?>"><?=clean($title['title'] . $root_title)?></a></td>
            <td data-title="<?=PUBLISHED?>" class="center"><?=$title['published']?></td>
            <td data-title="<?=PLATFORM?>"><?=clean($title['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>"><?=clean($title['mediatype'])?></td>
            <td data-title="<?=TITLETYPE?>"><?=type_text($title['titletype'])?></td>
            <td data-title="<?=OWNED?>" class="center"><span><?=status_icon($title['owned'])?></span></td>
            <td data-title="<?=CREATED?>" class="center"><?=date_cnv($title['created'], 'date', '')?></td>
            <td data-title="<?=MODIFIED?>" class="center"><?=date_cnv($title['modified'], 'date', '')?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?=template_pagination($thisfile, $page, $column, $order, $filter_url, $library_rows, $records_per_page)?>
<?=template_footer()?>