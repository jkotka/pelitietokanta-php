<?=template_header(clean($titledetails['title'] . ' (' . $titledetails['platform']) . ')')?>

<div class="breadcrumbs"><?=$breadcrumbs?></div>

<h1><?=clean($titledetails['title'])?></h1>

<div class="platform-tag">
    <?php foreach ($platform_tags as $platform_tag): $selected = (int)$platform_tag['id'] === $title_id ? 'selected-tag' : ''; ?>
    <a class="<?=$selected?>" href="<?=$thisfile . "?t=" . $platform_tag['id']?>"><?=clean($platform_tag['platform'] . ' (' . $platform_tag['mediatype']) . ')'?></a>
    <?php endforeach; ?>
</div>

<div class="titlecover">
    <label for="img-check"><img src="<?=get_image($images)?>" alt="<?=clean($titledetails['title'])?>"></label>
    <input class="toggle-check" type="checkbox" id="img-check">
    
    <form class="img-form" action="image.php?upload=<?=$title_id?>" method="post" enctype="multipart/form-data">
        <span>
            <label>
                <span class="change-img"><?=CHOOSE_IMAGE?></span>
                <i class="fas fa-angle-right fa-sm"></i>
                <input type="file" name="image" id="file-input" required>
            </label>
            <button class="img-submit" type="submit"><?=SAVE?></button>
        </span>
        <?php if (file_exists("img/cover/$title_id.jpg")): ?>
            |<span class="remove-img"><a href="image.php?delete=<?=$title_id?>"><?=DELETE_IMAGE?></a></span>
        <?php endif; ?>
    </form>
</div>

<table class="titledetails">
    <thead>
        <tr>
            <td><?=PUBLISHED?></td>
            <td><?=PLATFORM?></td>
            <td><?=MEDIATYPE?></td>
            <td><?=TITLETYPE?></td>
            <td><?=OWNED?></td>
            <td><?=INFO?></td>
            <td colspan="2"></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-title="<?=PUBLISHED?>" class="center"><?=$titledetails['published']?></td>
            <td data-title="<?=PLATFORM?>" class="center"><?=clean($titledetails['platform'])?></td>
            <td data-title="<?=MEDIATYPE?>" class="center"><?=clean($titledetails['mediatype'])?></td>
            <td data-title="<?=TITLETYPE?>" class="center"><?=type_text($titledetails['titletype'])?></td>
            <td data-title="<?=OWNED?>" class="center"><span><?=status_icon($titledetails['titlestatus'])?></span></td>
            <td data-title="<?=INFO?>" class="center truncate" title="<?=clean($titledetails['info'])?>"><?=clean($titledetails['info'])?></td>
            <td data-title="<?=EDIT?>" class="center min-width">
                <a href="insert-update.php?t=<?=$title_id?>"><i class="fas fa-pen"></i></a>
            </td>
            <?php if ($titledetails['titletype'] === 0 || $titledetails['titletype'] === 2): ?>
            <td data-title="<?=DELETE?>" class="center min-width">
                <a href="delete.php?t=<?=$title_id?>"><i class="fas fa-trash-alt"></i></a>
            </td>
            <?php else: ?>
            <td data-title="<?=DELETE?>" class="center min-width">
                <a href="delete.php?t=<?=$title_id?>&amp;item=<?=$title_id?>"><i class="fas fa-trash-alt"></i></a>
            </td>
            <?php endif; ?>
        </tr>
    </tbody>
</table>