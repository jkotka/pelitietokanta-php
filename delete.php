<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// ---------- TITLE DELETE ---------- //

// Check if title id is set
if (!empty($_GET['t']) && empty($_GET['item'])) {
    $title_id = (int)$_GET['t'];
    try {
        // Title
        $stmt = $pdo->prepare('SELECT 
                                    CASE
                                        WHEN title_edition IS NOT NULL
                                        THEN CONCAT(title_name, " - ", title_edition)
                                        ELSE title_name
                                    END AS title,
                                    title_published AS published,
                                    platform_name AS platform,
                                    mediatype_name AS mediatype,
                                    title_type AS titletype,
                                    title_status AS owned,
                                    title_info AS info,
                                    parent_id
                               FROM
                                    title
                                    INNER JOIN platform USING (platform_id)
                                    INNER JOIN mediatype USING (mediatype_id)
                               WHERE title_id = ?');
        $stmt->execute([$title_id]);
        $titledetails = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit($e->getMessage());
    }

    // Confirm title delete
    if (isset($_GET['confirm'])) {

        // If confirmed, delete title (cascades to child titles, stats and purchases)
        if ($_GET['confirm'] === 'yes') {
            try {
                $stmt = $pdo->prepare('DELETE FROM title WHERE title_id = ?');
                $stmt->execute([$title_id]);

                // Delete cover & thumbnail
                $cover = $covers . $title_id . '.jpg';
                $thumbnail = $thumbnails . $title_id . '_thumb.jpg';
                if (file_exists($cover)) {
                    unlink($cover);
                }
                if (file_exists($thumbnail)) {
                    unlink($thumbnail);
                }

            } catch (PDOException $e) {
                exit($e->getMessage());
            }

            // Redirect to parent title if exists, otherwise to index.php
            if (!empty($titledetails['parent_id'])) {
                header('Location:title.php?t=' . $titledetails['parent_id'] . '&s=delete_successful');
                exit;
            } else {
                header('Location:index.php?s=delete_successful');
                exit;
            }

        // If cancelled, redirect back to title page
        } else {
            header("Location:title.php?t=$title_id");
            exit;
        }
    }

// ---------- ITEM DELETE ---------- //

} elseif (!empty($_GET['item'])) {
    $title_id = (int)$_GET['t'];
    $item_id = (int)$_GET['item'];
    try {
        // Item
        $stmt = $pdo->prepare('SELECT
                                    ti.title_name AS title,
                                    ti.title_edition AS `edition`,
                                    ti.title_published AS published,
                                    pl.platform_name AS platform,
                                    mt.mediatype_name AS mediatype,
                                    ti.title_type AS itemtype,
                                    ti.title_status AS owned,
                                    ti.parent_id,
                                    CASE
                                        WHEN pa.title_edition IS NOT NULL
                                        THEN CONCAT(pa.title_name, " - ", pa.title_edition)
                                        ELSE pa.title_name
                                    END AS parent_title
                               FROM
                                    title ti
                                    INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                                    INNER JOIN mediatype mt ON ti.mediatype_id = mt.mediatype_id
                                    INNER JOIN title pa ON ti.parent_id = pa.title_id
                               WHERE ti.title_id = ?');
        $stmt->execute([$item_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit($e->getMessage());
    }

    // Confirm item (addon/collection part) delete
    if (isset($_GET['confirm'])) {

        // If confirmed, delete item
        if ($_GET['confirm'] === 'yes') {
            try {
                $stmt = $pdo->prepare('DELETE FROM title WHERE title_id = ?');
                $stmt->execute([$item_id]);

                // Delete cover & thumbnail
                $cover = $covers . $item_id . '.jpg';
                $thumbnail = $thumbnails . $item_id . '_thumb.jpg';
                if (file_exists($cover)) {
                    unlink($cover);
                }
                if (file_exists($thumbnail)) {
                    unlink($thumbnail);
                }

            } catch (PDOException $e) {
                exit($e->getMessage());
            }

            // Redirect to item's parent page
            header('Location:title.php?t=' . $item['parent_id'] . '&s=delete_successful');
            exit;

        // If cancelled, redirect back to title page
        } else {
            header("Location:title.php?t=$title_id");
            exit;
        }
    }

// ---------- STAT DELETE ---------- //

} elseif (!empty($_GET['stat'])) {
    $stat_id = (int)$_GET['stat'];
    try {
        // Stat
        $stmt = $pdo->prepare('SELECT
                                    title_id,
                                    CASE
                                        WHEN title_edition IS NOT NULL
                                        THEN CONCAT(title_name, " - ", title_edition)
                                        ELSE title_name
                                    END AS title,
                                    platform_name AS platform,
                                    mediatype_name AS mediatype,
                                    stat_started AS playstart,
                                    stat_stopped AS playstop,
                                    datediff(stat_stopped, stat_started)+1 AS playdays,
                                    stat_hours AS playhours,
                                    stat_hours/(datediff(stat_stopped, stat_started)+1) AS hoursperday,
                                    stat_beaten AS beaten
                               FROM
                                    stat
                                    INNER JOIN title USING (title_id)
                                    INNER JOIN platform USING (platform_id)
                                    INNER JOIN mediatype USING (mediatype_id)
                               WHERE stat_id = ?');
        $stmt->execute([$stat_id]);
        $stat = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit($e->getMessage());
    }

    // Confirm stat delete
    if (isset($_GET['confirm'])) {

        // If confirmed, delete stat
        if ($_GET['confirm'] === 'yes') {
            try {
                $stmt = $pdo->prepare('DELETE FROM stat WHERE stat_id = ?');
                $stmt->execute([$stat_id]);

            } catch (PDOException $e) {
                exit($e->getMessage());
            }

            // Redirect to title page
            header('Location:title.php?t=' . $stat['title_id'] . '&s=delete_successful');
            exit;

        // If cancelled, redirect to title page
        } else {
            header('Location:title.php?t=' . $stat['title_id']);
            exit;
        }
    }

// ---------- PURCHASE DELETE ---------- //

} elseif (!empty($_GET['purchase'])) {
    $purchase_id = (int)$_GET['purchase'];
    try {
        // Purchase
        $stmt = $pdo->prepare('SELECT
                                    title_id,
                                    CASE
                                        WHEN title_edition IS NOT NULL
                                        THEN CONCAT(title_name, " - ", title_edition)
                                        ELSE title_name
                                    END AS title,
                                    platform_name AS platform,
                                    mediatype_name AS mediatype,
                                    purchase_price AS price,
                                    paymethod_name AS paymethod,
                                    store_name AS store,
                                    purchase_date AS purchased
                               FROM
                                    purchase
                                    INNER JOIN title USING (title_id)
                                    INNER JOIN platform USING (platform_id)
                                    INNER JOIN mediatype USING (mediatype_id)
                                    LEFT JOIN paymethod USING (paymethod_id)
                                    LEFT JOIN store USING (store_id)
                               WHERE purchase_id = ?');
        $stmt->execute([$purchase_id]);
        $purchase = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        exit($e->getMessage());
    }

    // Confirm purchase delete
    if (isset($_GET['confirm'])) {

        // If confirmed, delete purchase
        if ($_GET['confirm'] === 'yes') {
            try {
                $stmt = $pdo->prepare('DELETE FROM purchase WHERE purchase_id = ?');
                $stmt->execute([$purchase_id]);

            } catch (PDOException $e) {
                exit($e->getMessage());
            }

            // Redirect to title page
            header('Location:title.php?t=' . $purchase['title_id'] . '&s=delete_successful');
            exit;

        // If cancelled, redirect to title page
        } else {
            header('Location:title.php?t=' . $purchase['title_id']);
            exit;
        }
    }

}

// ---------- HTML ---------- //

// Title delete
if (!empty($titledetails)) {
    require 'include/delete_title.php';

// Item delete
} elseif (!empty($item)) {
    require 'include/delete_item.php';

// Stat delete
} elseif (!empty($stat)) {
    require 'include/delete_stat.php';

// Purchase delete
} elseif (!empty($purchase)) {
    require 'include/delete_purchase.php';

// If nothing to delete, redirect to index.php
} else {
    header('Location:index.php');
    exit;
}

template_footer();
?>