<?=template_header($page_title)?>

<?php if (!empty($title_id) && $edit_mode === 'advanced'): ?>
<a class="advanced-edit" href="<?=$thisfile?>?t=<?=$title_id?>"><?=ADVANCED_EDIT?><i class="fas fa-toggle-on"></i></a>
<?php elseif (!empty($title_id)): ?>
<a class="advanced-edit" href="<?=$thisfile?>?t=<?=$title_id?>&amp;mode=advanced"><?=ADVANCED_EDIT?><i class="fas fa-toggle-off"></i></a>  
<?php endif; ?>

<h1><?=$header?></h1>

<form action="<?=$form_action?>" method="post" autocomplete="off">
    <input type="text" name="title" id="title" value="<?=clean($form_title)?>" placeholder="<?=TITLE?>" minlength="2" maxlength="100" required>
    <input type="text" name="edition" id="edition" value="<?=clean($form_edition)?>" placeholder="<?=EDITION?>" minlength="2" maxlength="100">

    <select name="published" required>
        <option value=""><?=PUBLISHED_DROPDOWN?></option>
        <?php for($year = date('Y') + 1; $year >= 1980; $year = $year - 1): $selected = (int)$form_published === $year ? ' selected' : ''; ?>
        <option value="<?=$year?>"<?=$selected?>><?=$year?></option>
        <?php endfor; ?>
    </select>
    
    <?php if (empty($form_parent_id) || $edit_mode === 'advanced'): ?>
    <select id="platform" name="platform" required>
        <option value=""><?=PLATFORM_DROPDOWN?></option>
        <?php $platforms = get_list('*', 'platform', 'platform_name', $pdo); foreach($platforms as $platform): $selected = $form_platform === $platform['platform_id'] ? ' selected' : ''; ?>
        <option value="<?=$platform['platform_id']?>"<?=$selected?>><?=clean($platform['platform_name'])?></option>
        <?php endforeach; ?>
    </select>
    <?php endif; ?>

    <a class="add" href="settings.php?show=platforms"><i class="fas fa-plus-circle fa-sm"></i></a>

    <select id="mediatype" name="mediatype" required>
        <option value=""><?=MEDIATYPE_DROPDOWN?></option>
        <?php $mediatypes = get_list('*', 'mediatype', 'mediatype_name', $pdo); foreach($mediatypes as $mediatype): $selected = $form_mediatype === $mediatype['mediatype_id'] ? ' selected' : ''; ?>
        <option value="<?=$mediatype['mediatype_id']?>"<?=$selected?>><?=clean($mediatype['mediatype_name'])?></option>
        <?php endforeach; ?>
    </select>

    <a class="add" href="settings.php?show=mediatypes"><i class="fas fa-plus-circle fa-sm"></i></a>

    <?php if ($edit_mode === 'advanced'): ?>
    <label for="parent_id"><?=PARENT_ID?>:</label>
    <input type="number" name="parent_id" id="parent_id" min="0" value="<?=empty($form_parent_id) ? 0 : $form_parent_id?>" step="1" required>
    <?php endif; ?>

    <?php if (empty($form_parent_id) || $edit_mode === 'advanced'): ?>
    <label>
        <?=GAME?>
        <?php $checked = $form_type === 0 ? ' checked' : ''; ?>
        <input type="radio" name="type" id="r_game" value="game"<?=$checked?> required>
    </label>

    <label>
        <?=COLLECTION?>
        <?php $checked = $form_type === 2 ? ' checked' : ''; ?>
        <input type="radio" name="type" id="r_collection" value="collection"<?=$checked?> required>
    </label>
    <?php endif; ?>

    <?php if ($edit_mode === 'advanced'): ?>
    <label>
        <?=ADDON?>
        <?php $checked = $form_type === 1 ? ' checked' : ''; ?>
        <input type="radio" name="type" id="r_addon" value="addon"<?=$checked?> required>
    </label>
    <?php endif; ?>

    <label>
        <?=OWNED?>
        <?php $checked = str_contains($form_status, 1) ? ' checked' : ''; ?>
        <input type="checkbox" name="owned" id="owned" value="1"<?=$checked?>>
    </label>

    <label>
        <?=WISHLISTED?>
        <?php $checked = str_contains($form_status, 2) ? ' checked' : ''; ?>
        <input type="checkbox" name="wishlist" id="wishlist" value="2"<?=$checked?>>
    </label>

    <label>
        <?=BACKLOGGED?>
        <?php $checked = str_contains($form_status, 3) ? ' checked' : ''; ?>
        <input type="checkbox" name="backlog" id="backlog" value="3"<?=$checked?>>
    </label>

    <?php if ($edit_mode === 'advanced'): ?>
    <label>
        <?=SAVE_NEW?>
        <input type="checkbox" name="save_new" id="save_new" value="1">
    </label>
    <?php endif; ?>

    <textarea name="info" placeholder="<?=INFO?>" rows="6" cols="90" maxlength="255"><?=clean($form_info)?></textarea>

    <button class="submit" type="submit"><?=SAVE?></button>
    
    <?php if (!empty($titledetails)): ?>
    <a class="button red" href="<?=$form_cancel_url?>"><?=CANCEL?></a>
    <?php endif; ?>
</form>