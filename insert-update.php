<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// ---------- UPDATE QUERY ---------- //

// Title update
if (!empty($_GET['t'])) {
    try {
        $title_id = (int)$_GET['t'];
        $stmt = $pdo->prepare('SELECT 
                                    title_name AS title,
                                    title_edition AS `edition`,
                                    title_published AS published,
                                    platform_id AS platform,
                                    mediatype_id AS mediatype,
                                    title_type AS titletype,
                                    title_status AS titlestatus,
                                    title_info AS info,
                                    parent_id
                               FROM title
                               WHERE title_id = ?
                               ORDER BY title_name');
        $stmt->execute([$title_id]);
        $titledetails = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

// ---------- FORM VARIABLES AND PROCESSING ---------- //

// Edit mode
$edit_mode = !empty($_GET['mode']) ? $_GET['mode'] : '';

// Check if inserting or updating
if (!empty($titledetails)) {

    // Update - form variables
    $header = '<i class="fas fa-pen fa-sm"></i>&nbsp;' . EDIT;
    $page_title = EDIT;
    $form_cancel_url = "title.php?t=$title_id";
    $form_action = "$thisfile?t=$title_id";
    $form_title = $titledetails['title'];
    $form_edition = $titledetails['edition'];
    $form_platform = $titledetails['platform'];
    $form_parent_id = $titledetails['parent_id'];
    $form_mediatype = $titledetails['mediatype'];
    $form_type = $titledetails['titletype'];
    $form_published = $titledetails['published'];
    $form_status = $titledetails['titlestatus'];
    $form_info = $titledetails['info'];

} else {
    // Insert - form variables
    $header = '<i class="fas fa-plus-circle fa-sm"></i>&nbsp;' . ADD;
    $page_title = ADD;
    $form_cancel_url = 'index.php';
    $form_action = $thisfile;
    $form_parent_id = null;
    $form_title = $form_edition = $form_platform = $form_mediatype = $form_type = $form_published = $form_status = $form_info = '';
}

// Form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validated set to true (set to false if form filled incorrectly)
    $validated = true;

    // Title (required, min. 2 chars)
    if (!empty($_POST['title'])) {
        $title = trim($_POST['title']);
        if (strlen($title) < 2) {
            $validated = false;
        }
    } else {
        $validated = false;
    }

    // Edition
    if (!empty($_POST['edition'])) {
        $edition = trim($_POST['edition']);
    } else {
        $edition = null;
    }

    // Published (required)
    if (!empty($_POST['published']) && filter_var($_POST['published'], FILTER_VALIDATE_INT)) {
        $published = $_POST['published'];
    } else {
        $validated = false;
    }

    // Platform (required)
    if (!empty($_POST['platform']) && filter_var($_POST['platform'], FILTER_VALIDATE_INT)) {
        $platform = $_POST['platform'];
    // If title has parent, platform is set automatically (form option disabled)
    } elseif (!empty($form_parent_id)) {
        $platform = $form_platform;
    } else {
        $validated = false;
    }

    // Mediatype (required)
    if (!empty($_POST['mediatype']) && filter_var($_POST['mediatype'], FILTER_VALIDATE_INT)) {
        $mediatype = $_POST['mediatype'];
    } else {
        $validated = false;
    }

    // Parent id (advanced mode) (0 is allowed)
    if (isset($_POST['parent_id']) && (filter_var($_POST['parent_id'], FILTER_VALIDATE_INT) === 0 || filter_var($_POST['parent_id'], FILTER_VALIDATE_INT))) {
        $parent_id = $_POST['parent_id'];
        // 0 is set to null
        if ((int)$_POST['parent_id'] === 0) {
            $parent_id = null;
        }
        // Title can't be its own parent, parent id is set to original
        if ((int)$_POST['parent_id'] === $title_id) {
            $parent_id = $form_parent_id;
        }
    // If no value, parent id is set to original
    } else {
        $parent_id = $form_parent_id;
    }

    // Type (required, 0 = game, 1 = addon, 2 = collection)
    // Type is also set for child titles (items) if parent type is changed
    if (!empty($_POST['type']) && $_POST['type'] === 'game') {
        $type = 0;
        $item_type = 1;
    } elseif (!empty($_POST['type']) && $_POST['type'] === 'collection') {
        $type = 2;
        $item_type = 0;
    // Addon (advanced mode), both type and item type are set to 1
    } elseif (!empty($_POST['type']) && $_POST['type'] === 'addon') {
        $type = 1;
        $item_type = 1;
    // If title has parent (is game/addon = item type is 1), types are set automatically (form option disabled)
    } elseif (!empty($form_parent_id)) {
        $type = $form_type;
        $item_type = 1;
    } else {
        $validated = false;
    }

    // Owned
    if (!isset($_POST['owned'])) {
        $status = 0;
    } else {
        $status = 1;
    }

    // Wishlist
    if (isset($_POST['wishlist'])) {
        $status .= 2;
    }

    // Backlog
    if (isset($_POST['backlog'])) {
        $status .= 3;
    }

    // Info
    if (!empty($_POST['info'])) {
        $info = trim($_POST['info']);
    } else {
        $info = null;
    }

    // Save as new (advanced mode)
    if (isset($_POST['save_new'])) {
        $save_new = 1;
    } else {
        $save_new = 0;
    }

    // Check if form is filled correctly
    if ($validated === true) {

        // ---------- INSERT / UPDATE ---------- //

        try {
            // Update title
            if (!empty($titledetails) && $save_new !== 1) {
                $stmt = $pdo->prepare('UPDATE title
                                       SET     
                                            title_name = ?,
                                            title_edition = ?,
                                            platform_id = ?,
                                            mediatype_id = ?,
                                            parent_id = ?,
                                            title_published = ?,
                                            title_type = ?,
                                            title_status = ?,
                                            title_info = ?
                                       WHERE title_id = ?');
                $stmt->execute([$title, $edition, $platform, $mediatype, $parent_id, $published, $type, $status, $info, $title_id]);

                // Update child title's type in case it has changed in main title
                $stmt = $pdo->prepare('UPDATE title
                                       SET title_type = ?
                                       WHERE parent_id = ?');
                $stmt->execute([$item_type, $title_id]);

                // Update all child title platforms recursively in case it was changed in main title
                $children = get_children($title_id, $pdo);
                $placeholders = str_repeat('?,', count($children) - 1) . '?';
                array_unshift($children, $platform);

                $sql = "UPDATE title SET platform_id = ? WHERE title_id IN ($placeholders)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($children);
                header("Location:title.php?t=$title_id&s=update_successful");
                exit;

            // Insert title
            } else {
                $stmt = $pdo->prepare('INSERT INTO title (
                                            title_name,
                                            title_edition,
                                            platform_id,
                                            mediatype_id,
                                            parent_id,
                                            title_published,
                                            title_type,
                                            title_status,
                                            title_info
                                       )
                                       VALUES
                                            (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$title, $edition, $platform, $mediatype, $parent_id, $published, $type, $status, $info]);
                $redirect_id = $pdo->lastInsertId();
                header("Location:title.php?t=$redirect_id&s=save_successful");
                exit;
            }
        
        } catch (PDOException $e) {
            exit($e->getMessage());
        }

    // If form is filled incorrectly
    } else {
        header("Location:$thisfile?e=form_incomplete");
        exit();
    }
}

// ---------- HTML ---------- //

require 'include/insert-update_form.php';

template_footer();
?>