<label id="item" class="toggle-label" for="item-toggle"><?=$titledetails['titletype'] === 2 ? GAMES : ADDONS ?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="item-toggle"<?=!empty($item_update) ? ' checked' : '' ?>>

<form id="item-form" class="hidden" action="<?=$item_action?>" method="post" autocomplete="off"> 
    <input type="text" name="title" id="title" value="<?=clean($item_title)?>" placeholder="<?=TITLE?>" minlength="2" maxlength="100" required>
    
    <?php if ($titledetails['titletype'] === 2): ?>
    <input type="text" name="edition" id="edition" value="<?=clean($item_edition)?>" placeholder="<?=EDITION?>" minlength="2" maxlength="100">
    <?php endif; ?>

    <select name="published" required>
        <option value=""><?=PUBLISHED_DROPDOWN?></option>
        <?php for($year = date('Y') + 1; $year >= 1980; $year = $year - 1): $selected = (int)$item_published === $year ? ' selected' : ''; ?>
        <option value="<?=$year?>"<?=$selected?>><?=$year?></option>
        <?php endfor; ?>
    </select>

    <select name="mediatype" required>
        <option value=""><?=MEDIATYPE_DROPDOWN?></option>
        <?php $mediatypes = get_list('*', 'mediatype', 'mediatype_name', $pdo); foreach($mediatypes as $mediatype): $selected = $item_mediatype === $mediatype['mediatype_id'] ? ' selected' : ''; ?>
        <option value="<?=$mediatype['mediatype_id']?>"<?=$selected?>><?=clean($mediatype['mediatype_name'])?></option>
        <?php endforeach; ?>
    </select>

    <a class="add" href="settings.php?show=mediatypes"><i class="fas fa-plus-circle fa-sm"></i></a>

    <label>
        <?=OWNED?>
        <?php $checked = str_contains($item_status, 1) ? ' checked' : ''; ?>
        <input type="checkbox" name="owned" id="owned" value="1"<?=$checked?>>
    </label>

    <label>
        <?=WISHLISTED?>
        <?php $checked = str_contains($item_status, 2) ? ' checked' : ''; ?>
        <input type="checkbox" name="wishlist" id="wishlist" value="2"<?=$checked?>>
    </label>

    <label>
        <?=BACKLOGGED?>
        <?php $checked = str_contains($item_status, 3) ? ' checked' : ''; ?>
        <input type="checkbox" name="backlog" id="backlog" value="3"<?=$checked?>>
    </label>

    <textarea name="info" placeholder="<?=INFO?>" rows="6" cols="90" maxlength="255"><?=clean($item_info)?></textarea>
    
    <button class="submit" type="submit"><?=SAVE?></button>

    <?php if (!empty($item_update)): ?>
    <a class="button red" href="<?=$thisfile?>?t=<?=$title_id?>"><?=CANCEL?></a>
    <?php endif; ?>
</form>