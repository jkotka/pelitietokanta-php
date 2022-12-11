<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// Check if title id is set
if (!empty($_GET['t'])) {
    $title_id = (int)$_GET['t'];
} else {
    header('Location:index.php?e=title_not_found');
    exit();
}

// ---------- MAIN QUERIES ---------- //

try {
    // Title
    $stmt = $pdo->prepare('SELECT
                                ti.title_name AS base_title,
                                CASE
                                   WHEN ti.title_edition IS NOT NULL
                                   THEN CONCAT(ti.title_name, " - ", ti.title_edition)
                                   ELSE ti.title_name
                                END AS title,
                                ti.title_published AS published,
                                ti.platform_id AS platform_id,
                                pl.platform_name AS platform,
                                mt.mediatype_name AS mediatype,
                                ti.title_type AS titletype,
                                ti.title_status AS titlestatus,
                                ti.title_info AS info,
                                ti.parent_id,
                                pa.title_name AS parent_title
                           FROM
                                title ti
                                LEFT JOIN title pa ON ti.parent_id = pa.title_id
                                INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                                INNER JOIN mediatype mt ON ti.mediatype_id = mt.mediatype_id
                           WHERE ti.title_id = ?');
    $stmt->execute([$title_id]);
    $titledetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Items (addons or parts of collection)
    $stmt = $pdo->prepare('SELECT
                                title_id AS item_id,
                                CASE
                                   WHEN title_edition IS NOT NULL
                                   THEN CONCAT(title_name, " - ", title_edition)
                                   ELSE title_name
                                END AS title,
                                title_published AS published,
                                mediatype_name AS mediatype,
                                title_status AS itemstatus,
                                title_info AS info
                           FROM
                                title
                                INNER JOIN mediatype USING (mediatype_id)
                           WHERE parent_id = ?
                           ORDER BY
                                title_published,
                                title_name');
    $stmt->execute([$title_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Purchases
    $stmt = $pdo->prepare('SELECT
                                purchase_id,
                                purchase_price AS price,
                                paymethod_name AS paymethod,
                                store_name AS store,
                                purchase_date AS purchased,
                                purchase_info AS info
                           FROM
                                purchase
                                LEFT JOIN paymethod USING (paymethod_id)
                                LEFT JOIN store USING (store_id)
                           WHERE title_id = ?
                           ORDER BY purchase_date');
    $stmt->execute([$title_id]);
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Stats - game
    if (!empty($titledetails) && $titledetails['titletype'] === 0) {
        $stmt = $pdo->prepare('SELECT
                                    stat_id,
                                    stat_started AS playstart,
                                    stat_stopped AS playstop,
                                    datediff(stat_stopped, stat_started)+1 AS playdays,
                                    stat_hours AS playhours,
                                    stat_hours/(datediff(stat_stopped, stat_started)+1) AS hoursperday,
                                    stat_beaten AS beaten,
                                    stat_info AS info
                               FROM stat
                               WHERE title_id = ?
                               ORDER BY stat_stopped');
        $stmt->execute([$title_id]);
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Total hours
        $stmt = $pdo->prepare('SELECT
                                SUM(stat_hours)
                                FROM stat
                                WHERE title_id = ?');
        $stmt->execute([$title_id]);
        $hours_total = $stmt->fetchColumn();

    // Stats - collection
    } elseif (!empty($titledetails) && $titledetails['titletype'] === 2) {
        $stmt = $pdo->prepare('SELECT
                                    stat_id,
                                    title_id,
                                    CASE
                                        WHEN title_edition IS NOT NULL
                                        THEN CONCAT(title_name, " - ", title_edition)
                                        ELSE title_name
                                    END AS title,
                                    stat_started AS playstart,
                                    stat_stopped AS playstop,
                                    datediff(stat_stopped, stat_started)+1 AS playdays,
                                    stat_hours AS playhours,
                                    stat_hours/(datediff(stat_stopped, stat_started)+1) AS hoursperday,
                                    stat_beaten AS beaten
                               FROM stat
                               INNER JOIN title USING (title_id)
                               WHERE title_id IN (SELECT title_id FROM title WHERE parent_id = ?)
                               ORDER BY stat_stopped');
        $stmt->execute([$title_id]);
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Total hours
        $stmt = $pdo->prepare('SELECT
                                SUM(stat_hours)
                                FROM stat
                                WHERE title_id IN (SELECT title_id FROM title WHERE parent_id = ?)');
        $stmt->execute([$title_id]);
        $hours_total = $stmt->fetchColumn();
        }

    // Title total price
    $stmt = $pdo->prepare('SELECT
                                SUM(purchase_price)
                           FROM purchase
                           WHERE title_id = ?');
    $stmt->execute([$title_id]);
    $title_total = $stmt->fetchColumn();

    // Items total price
    $stmt = $pdo->prepare('SELECT
                                SUM(purchase_price)
                           FROM purchase
                           WHERE title_id IN (
                                SELECT title_id
                                FROM title
                                WHERE parent_id = ?)');
    $stmt->execute([$title_id]);
    $items_total = $stmt->fetchColumn();

    // Item prices recursively
    $children = get_children($title_id, $pdo);
    $placeholders = str_repeat('?,', count($children) - 1) . '?';
    $sql = "SELECT SUM(purchase_price) FROM purchase WHERE title_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($children);
    $items_total_recursive = $stmt->fetchColumn();

    // Platform tags - shows if title is available on other platforms/mediatypes
    // Games and collections: shown if base titles (= no edition) and types match
    // Addons: parent names must match as well, otherwise too many season passes...
    if (!empty($titledetails)) {
        $stmt = $pdo->prepare('SELECT
                                    title_id AS id,
                                    platform_name AS platform,
                                    mediatype_name AS mediatype
                               FROM
                                    title
                                    INNER JOIN platform USING (platform_id)
                                    INNER JOIN mediatype USING (mediatype_id)
                               WHERE title_name = :base_title
                               AND CASE
                                    WHEN parent_id IS NOT NULL AND title_type = 1
                                    THEN parent_id IN (
                                        SELECT pa.title_id
                                        FROM
                                            title ti
                                            INNER JOIN title pa ON ti.parent_id = pa.title_id
                                        WHERE ti.title_name = :base_title
                                        AND pa.title_name = :parent_title
                                    )
                                    ELSE TRUE
                               END
                               AND title_type = :titletype
                               ORDER BY title_created');
        $stmt->bindValue(':base_title', $titledetails['base_title'], PDO::PARAM_STR);
        $stmt->bindValue(':parent_title', $titledetails['parent_title'], PDO::PARAM_STR);
        $stmt->bindValue(':titletype', $titledetails['titletype'], PDO::PARAM_INT);
        $stmt->execute();
        $platform_tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ---------- UPDATE QUERIES ---------- //

    // Item update
    if (!empty($_GET['item'])) {
        $item_id = (int)$_GET['item'];
        $stmt = $pdo->prepare('SELECT
                                    title_name AS title,
                                    title_edition AS `edition`,
                                    title_published AS published,
                                    mediatype_id AS mediatype,
                                    title_status AS itemstatus,
                                    title_info AS info
                               FROM
                                    title
                               WHERE title_id = ?');
        $stmt->execute([$item_id]);
        $item_update = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Stat update
    elseif (!empty($_GET['stat'])) {
        $stat_id = (int)$_GET['stat'];
        $stmt = $pdo->prepare('SELECT
                                    stat_started,
                                    stat_stopped,
                                    stat_hours,
                                    stat_beaten,
                                    stat_info
                               FROM stat
                               WHERE stat_id = ?');
        $stmt->execute([$stat_id]);
        $stat_update = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Purchase update
    elseif (!empty($_GET['purchase'])) {
        $purchase_id = (int)$_GET['purchase'];
        $stmt = $pdo->prepare('SELECT
                                    purchase_price,
                                    paymethod_id,
                                    store_id,
                                    purchase_date,
                                    purchase_info
                               FROM purchase
                               WHERE purchase_id = ?');
        $stmt->execute([$purchase_id]);
        $purchase_update = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    exit($e->getMessage());
}

// ---------- ITEM FORM VARIABLES AND PROCESSING ---------- //

// Check if inserting or updating item
if (!empty($item_update)) {

    // Update - item form variables
    $item_action = "$thisfile?t=$title_id&amp;item=$item_id";
    $item_title = $item_update['title'];
    $item_edition = $item_update['edition'];
    $item_published = $item_update['published'];
    $item_mediatype = $item_update['mediatype'];
    $item_status = $item_update['itemstatus'];
    $item_info = $item_update['info'];

} else {
    // Insert - item form variables
    $item_action = "$thisfile?t=$title_id&amp;item=false";
    $item_title = $item_edition = $item_published = $item_mediatype = $item_status = $item_info = '';
}

// Item form processing
if (isset($item_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {

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

    // Mediatype (required)
    if (!empty($_POST['mediatype']) && filter_var($_POST['mediatype'], FILTER_VALIDATE_INT)) {
        $mediatype = $_POST['mediatype'];
    } else {
        $validated = false;
    }
    
    // Type -- If main title is collection (type 2), item type is set to 0 (game), otherwise to 1 (addon)
    if ($titledetails['titletype'] === 2) {
        $type = 0;
    } else {
        $type = 1;
    }
    
    // Info
    if (!empty($_POST['info'])) {
        $info = trim($_POST['info']);
    } else {
        $info = null;
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

    // Get rest from the main title
    $platform = $titledetails['platform_id'];
    $parent_id = $title_id;

    // Check if form is filled correctly
    if ($validated === true) {

        // ---------- ITEM INSERT / UPDATE ---------- //

        try {
            // Update item
            if (!empty($item_update)) {
                $stmt = $pdo->prepare('UPDATE title
                                       SET
                                            title_name = ?,
                                            title_edition = ?,
                                            title_published = ?,
                                            mediatype_id = ?,
                                            title_type = ?,
                                            title_status = ?,
                                            title_info = ?
                                       WHERE title_id = ?');
                $stmt->execute([$title, $edition, $published, $mediatype, $type, $status, $info, $item_id]);
                header("Location:$thisfile?t=$title_id&s=update_successful");
                exit;
                
            // Insert item
            } else {
                $stmt = $pdo->prepare('INSERT INTO title (
                                            title_name,
                                            title_edition,
                                            title_published,
                                            platform_id,
                                            mediatype_id,
                                            parent_id,
                                            title_type,
                                            title_status,
                                            title_info
                                       )
                                       VALUES
                                            (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$title, $edition, $published, $platform, $mediatype, $parent_id, $type, $status, $info]);
                header("Location:$thisfile?t=$title_id&s=save_successful");
                exit;
            }

        } catch (PDOException $e) {
            exit($e->getMessage());
        }

    // If form is filled incorrectly
    } else {
        header("Location:$thisfile?t=$title_id&e=form_incomplete");
        exit();
    }
}

// ---------- STAT FORM VARIABLES AND PROCESSING ---------- //

// Check if inserting or updating stat
if (!empty($stat_update)) {

    // Update - stat form variables
    $stat_action = "$thisfile?t=$title_id&amp;stat=$stat_id";
    $stat_started = $stat_update['stat_started'];
    $stat_stopped = $stat_update['stat_stopped'];
    $stat_hours = $stat_update['stat_hours'];
    $stat_beaten = $stat_update['stat_beaten'];
    $stat_info = $stat_update['stat_info'];

} else {
    // Insert - stat form variables
    $stat_action = "$thisfile?t=$title_id&amp;stat=false";
    $stat_started = $stat_stopped = $stat_hours = $stat_beaten = $stat_info = '';
}

// Stat form processing
if (isset($stat_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validated set to true (set to false if form filled incorrectly)
    $validated = true;

    // Started
    if (!empty($_POST['started'])) {
        $started = date('Y-m-d', strtotime($_POST['started']));
    } else {
        $started = null;
    }

    // Stopped (required)
    if (!empty($_POST['stopped'])) {
        $stopped = date('Y-m-d', strtotime($_POST['stopped']));
    } else {
        $validated = false;
    }

    // Hours
    if (!empty($_POST['hours']) && filter_var($_POST['hours'], FILTER_VALIDATE_INT)) {
        $hours = $_POST['hours'];
    } else {
        $hours = null;
    }

    // Beaten
    if (!isset($_POST['beaten'])) {
        $beaten = 0;
    } else {
        $beaten = 1;
    }

    // Info
    if (!empty($_POST['info'])) {
        $info = trim($_POST['info']);
    } else {
        $info = null;
    }

    // Check if form is filled correctly
    if ($validated === true) {

        // ---------- STAT INSERT / UPDATE ---------- //

        try {
            // Update stat
            if (!empty($stat_update)) {
                $stmt = $pdo->prepare('UPDATE stat
                                       SET
                                            stat_started = ?,
                                            stat_stopped = ?,
                                            stat_hours = ?,
                                            stat_beaten = ?,
                                            stat_info = ?
                                       WHERE stat_id = ?');
                $stmt->execute([$started, $stopped, $hours, $beaten, $info, $stat_id]);
                header("Location:$thisfile?t=$title_id&s=update_successful");
                exit;
            
            // Insert stat
            } else {
                $stmt = $pdo->prepare('INSERT INTO stat (
                                            title_id,
                                            stat_started,
                                            stat_stopped,
                                            stat_hours,
                                            stat_beaten,
                                            stat_info
                                       )
                                       VALUES
                                            (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$title_id, $started, $stopped, $hours, $beaten, $info]);
                header("Location:$thisfile?t=$title_id&s=save_successful");
                exit;
            }

        } catch (PDOException $e) {
            exit($e->getMessage());
        }

    // If form is filled incorrectly
    } else {
        header("Location:$thisfile?t=$title_id&e=form_incomplete");
        exit();
    }
}

// ---------- PURCHASE FORM VARIABLES AND PROCESSING ---------- //

// Check if inserting or updating purchase
if (!empty($purchase_update)) {

    // Update - purchase form variables
    $purchase_action = "$thisfile?t=$title_id&amp;purchase=$purchase_id";
    $purchase_price = $purchase_update['purchase_price'];
    $purchase_paymethod = $purchase_update['paymethod_id'];
    $purchase_store = $purchase_update['store_id'];
    $purchase_date = $purchase_update['purchase_date'];
    $purchase_info = $purchase_update['purchase_info'];

} else {
    // Insert - purchase form variables
    $purchase_action = "$thisfile?t=$title_id&amp;purchase=false";
    $purchase_price = $purchase_paymethod = $purchase_store = $purchase_date = $purchase_info = '';
}

// Purchase form processing
if (isset($purchase_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validated set to true (set to false if form filled incorrectly)
    $validated = true;

    // Price (required)
    if (!empty($_POST['price']) && filter_var($_POST['price'], FILTER_VALIDATE_FLOAT)) {
        $price = str_replace(',', '.', $_POST['price']);
    } else {
        $validated = false;
    }

    // Pay method
    if (!empty($_POST['paymethod']) && filter_var($_POST['paymethod'], FILTER_VALIDATE_INT)) {
        $paymethod = $_POST['paymethod'];
    } else {
        $paymethod = null;
    }

    // Store
    if (!empty($_POST['store']) && filter_var($_POST['store'], FILTER_VALIDATE_INT)) {
        $store = $_POST['store'];
    } else {
        $store = null;
    }

    // Purchased
    if (!empty($_POST['purchased'])) {
        $purchased = date('Y-m-d', strtotime($_POST['purchased']));
    } else {
        $purchased = null;
    }

    // Info
    if (!empty($_POST['info'])) {
        $info = trim($_POST['info']);
    } else {
        $info = null;
    }

    // Check if form is filled correctly
    if ($validated === true) {

        // ---------- PURCHASE INSERT / UPDATE ---------- //

        try {
            // Update purchase
            if (!empty($purchase_update)) {
                $stmt = $pdo->prepare('UPDATE purchase
                                       SET
                                            purchase_price = ?,
                                            paymethod_id = ?,
                                            store_id = ?,
                                            purchase_date = ?,
                                            purchase_info = ?
                                       WHERE purchase_id = ?');
                $stmt->execute([$price, $paymethod, $store, $purchased, $info, $purchase_id]);
                header("Location:$thisfile?t=$title_id&s=update_successful");
                exit;
            
            // Insert purchase
            } else {
                $stmt = $pdo->prepare('INSERT INTO purchase (
                                            title_id,
                                            purchase_price,
                                            paymethod_id,
                                            store_id,
                                            purchase_date,
                                            purchase_info
                                       )
                                       VALUES
                                            (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$title_id, $price, $paymethod, $store, $purchased, $info]);
                header("Location:$thisfile?t=$title_id&s=save_successful");
                exit;
            }

        } catch (PDOException $e) {
            exit($e->getMessage());
        }

    // If form is filled incorrectly
    } else {
        header("Location:$thisfile?t=$title_id&e=form_incomplete");
        exit();
    }
}

// ---------- TITLE DETAILS ---------- //

// Check if title is found
if (!empty($titledetails)) {

    // Set cover path and empty parent/root covers
    $images['main'] = $covers . $title_id . '.jpg';
    $images['placeholder'] = $covers . 'placeholder.jpg';
    $images['parent2'] = $images['parent1'] = $images['root'] = '';

    // Breadcrumbs - link to games and platform
    $breadcrumbs = '<a href="library.php">' . LIBRARY . '</a><i class="fas fa-angle-right fa-sm"></i><a href="library.php?platform%5B%5D=' . $titledetails['platform_id'] . '">' . clean($titledetails['platform']) . '</a>';

    // If title has parent_id (is addon/collection part)
    if (!empty($titledetails['parent_id'])) {

        // Get parent ids and titles
        $parents = get_parents($titledetails['parent_id'], $pdo);

        // Set parent & root ids (index number is row number)
        $parent2_id = !empty($parents[2]['id']) ? $parents[2]['id'] : '';
        $parent1_id = !empty($parents[1]['id']) ? $parents[1]['id'] : '';
        $root_id = $parents[0]['id'];

        // Set parent & root titles and make them links
        $parent2_link = !empty($parents[2]['title']) ? '<a href="' . $thisfile . '?t=' . $parent2_id . '">' . clean($parents[2]['title']) . '</a>' : '';
        $parent1_link = !empty($parents[1]['title']) ? '<a href="' . $thisfile . '?t=' . $parent1_id . '">' . clean($parents[1]['title']) . '</a>' : '';
        $root_link = '<i class="fas fa-angle-right fa-sm"></i><a href="' . $thisfile . '?t=' . $root_id . '">' . clean($parents[0]['title']) . '</a>';

        // Set parent & root covers (if title has no cover, it uses parent/root cover)
        $images['parent2'] = $covers . $parent2_id . '.jpg';
        $images['parent1'] = $covers . $parent1_id . '.jpg';
        $images['root'] = $covers . $root_id . '.jpg';

        // Breadcrumbs - links to parent & root titles
        $breadcrumbs .= !empty($parent1_link) ? $root_link . '<i class="fas fa-angle-right fa-sm"></i>' : $root_link;
        $breadcrumbs .= !empty($parent2_link) ? $parent1_link . '<i class="fas fa-angle-right fa-sm"></i>' : $parent1_link;
        $breadcrumbs .= $parent2_link;
    }

    // If title is found, include main page
    require 'include/title_main.php';

// If title not found, redirect
} else {
    header('Location:index.php?e=title_not_found');
    exit();
}

// ---------- HTML ---------- //

// Items
require 'include/title_items.php';

// Stats
if ($titledetails['titletype'] === 0) {
    require 'include/title_stats.php';
} elseif ($titledetails['titletype'] === 2 && !empty($stats)) {
    require 'include/collection_stats.php';
}

// Purchases
require 'include/title_purchases.php';

// ---------- FORMS ---------- //

// Item form - limit depth of child titles (addons) to 3
if (empty($parents) || count($parents) < 3) {
    require 'include/title_item_form.php';
}

// Stat form - show only for games
if ($titledetails['titletype'] === 0) {
    require 'include/title_stat_form.php';
}

// Purchase form
require 'include/title_purchase_form.php';

template_footer();
?>