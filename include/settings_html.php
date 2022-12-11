<?=template_header(SETTINGS)?>

<h1><i class="fas fa-cog fa-sm"></i>&nbsp;<?=SETTINGS?></h1>

<div class="lang-sel">
    <strong><?=SELECT_LANG?>:&nbsp;</strong><a href="<?=$thisfile?>?lang=fi">Suomi</a>&nbsp;|&nbsp;<a href="<?=$thisfile?>?lang=en">English</a>
</div>

<div class="export-db">
    <strong><?=EXPORT_DATA?>:&nbsp;</strong><a href="export.php?file=sql">SQL</a>&nbsp;|&nbsp;<a href="export.php?file=csv">CSV</a>
</div>

<label class="toggle-label" for="platforms-toggle"><?=PLATFORMS?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="platforms-toggle"<?=!empty($_GET['show']) && $_GET['show'] === 'platforms' ? ' checked' : '' ?>>

<form id="platforms-form" class="hidden" action="<?=$thisfile?>?form=platform&amp;show=platforms" method="post" autocomplete="off">
    <select class="settings-sel" name="platform" id="platform">
        <option value=""><?=PLATFORM_DROPDOWN?></option>
        <?php $platforms = get_list('*', 'platform', 'platform_name', $pdo); foreach($platforms as $platform): ?>
        <option value="<?=$platform['platform_id']?>"><?=clean($platform['platform_name'])?></option>
        <?php endforeach; ?>
    </select>

    <input class="required" type="text" name="platform_name" minlength="2" maxlength="50" placeholder="<?=PLATFORM?>">

    <br>

    <label for="delete-platform"><?=DELETE_PLATFORM?></label>
    <input class="del-check" type="checkbox" name="delete_platform" id="delete-platform" value="1">
    <button class="submit" type="submit"><?=SAVE?></button>
</form>

<label class="toggle-label" for="mediatypes-toggle"><?=MEDIATYPES?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="mediatypes-toggle"<?=!empty($_GET['show']) && $_GET['show'] === 'mediatypes' ? ' checked' : '' ?>>

<form id="mediatypes-form" class="hidden" action="<?=$thisfile?>?form=mediatype&amp;show=mediatypes" method="post" autocomplete="off">
    <select class="settings-sel" name="mediatype" id="mediatype">
        <option value=""><?=MEDIATYPE_DROPDOWN?></option>
        <?php $mediatypes = get_list('*', 'mediatype', 'mediatype_name', $pdo); foreach($mediatypes as $mediatype): ?>
        <option value="<?=$mediatype['mediatype_id']?>"><?=clean($mediatype['mediatype_name'])?></option>
        <?php endforeach; ?>
    </select>

    <input class="required" type="text" name="mediatype_name" minlength="2" maxlength="50" placeholder="<?=MEDIATYPE?>">

    <br>

    <label for="delete-mediatype"><?=DELETE_MEDIATYPE?></label>
    <input class="del-check" type="checkbox" name="delete_mediatype" id="delete-mediatype" value="1">
    <button class="submit" type="submit"><?=SAVE?></button>
</form>

<label class="toggle-label" for="paymethods-toggle"><?=PAYMETHODS?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="paymethods-toggle"<?=!empty($_GET['show']) && $_GET['show'] === 'paymethods' ? ' checked' : '' ?>>

<form id="paymethods-form" class="hidden" action="<?=$thisfile?>?form=paymethod&amp;show=paymethods" method="post" autocomplete="off">
    <select class="settings-sel" name="paymethod" id="paymethod">
        <option value=""><?=PAYMETHOD_DROPDOWN?></option>
        <?php $paymethods = get_list('*', 'paymethod', 'paymethod_name', $pdo); foreach($paymethods as $paymethod): ?>
        <option value="<?=$paymethod['paymethod_id']?>"><?=clean($paymethod['paymethod_name'])?></option>
        <?php endforeach; ?>
    </select>

    <input class="required" type="text" name="paymethod_name" minlength="2" maxlength="50" placeholder="<?=PAYMETHOD?>">

    <br>

    <label for="delete-paymethod"><?=DELETE_PAYMETHOD?></label>
    <input class="del-check" type="checkbox" name="delete_paymethod" id="delete-paymethod" value="1">
    <button class="submit" type="submit"><?=SAVE?></button>
</form>

<label class="toggle-label" for="stores-toggle"><?=STORES?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="stores-toggle"<?=!empty($_GET['show']) && $_GET['show'] === 'stores' ? ' checked' : '' ?>>

<form id="stores-form" class="hidden" action="<?=$thisfile?>?form=store&amp;show=stores" method="post" autocomplete="off">
    <select class="settings-sel" name="store" id="store">
        <option value=""><?=STORE_DROPDOWN?></option>
        <?php $stores = get_list('*', 'store', 'store_name', $pdo); foreach($stores as $store): ?>
        <option value="<?=$store['store_id']?>"><?=clean($store['store_name'])?></option>
        <?php endforeach; ?>
    </select>

    <input class="required" type="text" name="store_name" minlength="2" maxlength="50" placeholder="<?=STORE?>">

    <br>

    <label for="delete-store"><?=DELETE_STORE?></label>
    <input class="del-check" type="checkbox" name="delete_store" id="delete-store" value="1">
    <button class="submit" type="submit"><?=SAVE?></button>
</form>