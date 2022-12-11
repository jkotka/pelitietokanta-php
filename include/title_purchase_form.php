<label id="purchase" class="toggle-label" for="purchase-toggle"><?=PURCHASE_INFO?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="purchase-toggle"<?=!empty($purchase_update) ? ' checked' : '' ?>>

<form id="purchase-form" class="hidden" action="<?=$purchase_action?>" method="post" autocomplete="off">
    <label for="price"><?=PRICE?>:</label>
    <input type="number" name="price" id="price" min="0" max="999.99" step="0.01" value="<?=str_replace('.', ',', $purchase_price)?>" required>

    <select name="paymethod" id="paymethod">
        <option value=""><?=PAYMETHOD_DROPDOWN?></option>
        <?php $paymethods = get_list('*', 'paymethod', 'paymethod_name', $pdo); foreach($paymethods as $paymethod): $selected = $purchase_paymethod === $paymethod['paymethod_id'] ? ' selected' : ''; ?>
        <option value="<?=$paymethod['paymethod_id']?>"<?=$selected?>><?=clean($paymethod['paymethod_name'])?></option>
        <?php endforeach; ?>
    </select>

    <a class="add" href="settings.php?show=paymethods"><i class="fas fa-plus-circle fa-sm"></i></a>

    <select name="store" id="store">
        <option value=""><?=STORE_DROPDOWN?></option>
        <?php $stores = get_list('*', 'store', 'store_name', $pdo); foreach($stores as $store): $selected = $purchase_store === $store['store_id'] ? ' selected' : ''; ?>
        <option value="<?=$store['store_id']?>"<?=$selected?>><?=clean($store['store_name'])?></option>
        <?php endforeach; ?>
    </select>

    <a class="add" href="settings.php?show=stores"><i class="fas fa-plus-circle fa-sm"></i></a>

    <label for="purchased"><?=PURCHASED?>:</label>
    <input type="date" name="purchased" id="purchased" min="1980-01-01" max="<?=date('Y-m-d')?>" value="<?=$purchase_date?>">

    <textarea name="info" placeholder="<?=INFO?>" rows="6" cols="90" maxlength="255"><?=clean($purchase_info)?></textarea>

    <button class="submit" type="submit"><?=SAVE?></button>

    <?php if (!empty($purchase_update)): ?>
    <a class="button red" href="<?=$thisfile?>?t=<?=$title_id?>"><?=CANCEL?></a>
    <?php endif; ?>
</form>