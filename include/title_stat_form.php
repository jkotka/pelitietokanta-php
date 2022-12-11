<label id="stat" class="toggle-label" for="stat-toggle"><?=GAMESTATS?><i class="fas fa-caret-down fa-sm"></i></label>
<input class="toggle-check" type="checkbox" id="stat-toggle"<?=!empty($stat_update) ? ' checked' : '' ?>>

<form id="stat-form" class="hidden" action="<?=$stat_action?>" method="post" autocomplete="off">    
    <label for="started"><?=STARTED?>:</label>
    <input type="date" name="started" id="started" min="1980-01-01" max="<?=date('Y-m-d')?>" value="<?=$stat_started?>">

    <label for="stopped"><?=STOPPED?>:</label>
    <input type="date" name="stopped" id="stopped" min="1980-01-01" max="<?=date('Y-m-d')?>" value="<?=$stat_stopped?>" required>

    <label for="hours"><?=HOURS?>:</label>
    <input type="number" name="hours" id="hours" min="0" max="9999" value="<?=$stat_hours?>">

    <label>
        <?=BEATEN?>
        <?php $checked = $stat_beaten === 1 ? ' checked' : ''; ?>
        <input type="checkbox" name="beaten" id="beaten" value="1"<?=$checked?>>
    </label>

    <textarea name="info" placeholder="<?=INFO?>" rows="6" cols="90" maxlength="255"><?=clean($stat_info)?></textarea>

    <button class="submit" type="submit"><?=SAVE?></button>

    <?php if (!empty($stat_update)): ?>
    <a class="button red" href="<?=$thisfile?>?t=<?=$title_id?>"><?=CANCEL?></a>
    <?php endif; ?>
</form>