<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// ---------- PAGINATION ---------- //

// Get records per page
$records_per_page = get_records();

// Get page number
$page = get_page();

// Row count starting point
$row_count = $records_per_page * $page - $records_per_page + 1;

// ---------- SORTING ---------- //

// Sort column names
$columns = array(
    TITLE => 'title',
    PUBLISHED => 'published',
    PLATFORM => 'platform',
    MEDIATYPE => 'mediatype',
    TITLETYPE => 'titletype',
    OWNED => 'owned',
    CREATED => 'created',
    MODIFIED => 'modified'
);

// Sort column and order
$column = sort_column($columns);
$order = sort_order();

// ---------- FILTER PROCESSING ---------- //

// Empty the filter
$filter = '';

// Title
$filter_title = filter_title('title', 'ti.title_name', 'ti.title_edition', $pdo);
$form_title = $filter_title['val'];
$filter .= $filter_title['sql'];

// Published
$filter_published = filter_multi('published', 'ti.title_published');
$form_published = $filter_published['val'];
$filter .= $filter_published['sql'];

// Platform
$filter_platform = filter_multi('platform', 'ti.platform_id');
$form_platform = $filter_platform['val'];
$filter .= $filter_platform['sql'];

// Mediatype
$filter_mediatype = filter_multi('mediatype', 'ti.mediatype_id');
$form_mediatype = $filter_mediatype['val'];
$filter .= $filter_mediatype['sql'];

// Titletype
$filter_titletype = filter_multi('titletype', 'ti.title_type');
$form_titletype = $filter_titletype['val'];
$filter .= $filter_titletype['sql'];

// Show
$form_show = 'all';
$header = '<i class="fas fa-dice-d6 fa-sm"></i>&nbsp;' . LIBRARY;
$page_title = LIBRARY;
$total = TOTAL;
if (!empty($_GET['show'])) {
    if ($_GET['show'] === 'owned') {
        $form_show = 'owned';
        $filter .= ' AND ti.title_status IN (1, 12, 13, 123)';
        $total = OWNED;
    } elseif ($_GET['show'] === 'wishlist') {
        $form_show = 'wishlist';
        $filter .= ' AND ti.title_status IN (2, 12, 23, 123)';
        $header = '<i class="far fa-heart fa-sm"></i>&nbsp;' . WISHLIST;
        $page_title = WISHLIST;
        $total = WISHLISTED;
    } elseif ($_GET['show'] === 'backlog') {
        $form_show = 'backlog';
        $filter .= ' AND ti.title_status IN (3, 13, 23, 123)';
        $header = '<i class="far fa-list-alt fa-sm"></i>&nbsp;' . BACKLOG;
        $page_title = BACKLOG;
        $total = BACKLOGGED;
    } elseif ($_GET['show'] === 'played') {
        header("Location:played.php?sort=playstop&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype");
        exit;
    } elseif ($_GET['show'] === 'beaten') {
        header("Location:played.php?sort=playstop&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&show=beaten");
        exit;
    } elseif ($_GET['show'] === 'purchased') {
        header("Location:purchased.php?sort=purchased&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&titletype[]=$form_titletype");
        exit;
    }
}

// Filter url for pagination and column sorting
$filter_url = "&amp;records=$records_per_page&amp;title=" . urlencode($form_title) . "&amp;published%5B%5D=$form_published&amp;platform%5B%5D=$form_platform&amp;mediatype%5B%5D=$form_mediatype&amp;titletype%5B%5D=$form_titletype&amp;show=$form_show";

// ---------- MAIN QUERY ---------- //

try {
    // Library - apply filter and sorting if set
    $stmt = $pdo->prepare("SELECT
                                ti.title_id AS id,
                                CASE
                                    WHEN SUBSTRING_INDEX(ti.title_name, ' ', 1) IN ('a', 'an', 'the') AND ti.title_edition IS NOT NULL
                                    THEN CONCAT(SUBSTRING(ti.title_name, INSTR(ti.title_name, ' ') + 1 ), ' - ', ti.title_edition, ', ', SUBSTRING_INDEX(ti.title_name, ' ', 1))
                                    WHEN SUBSTRING_INDEX(ti.title_name, ' ', 1) IN ('a', 'an', 'the')
                                    THEN CONCAT(SUBSTRING(ti.title_name, INSTR(ti.title_name, ' ') + 1 ), ', ', SUBSTRING_INDEX(ti.title_name, ' ', 1))
                                    WHEN ti.title_edition IS NOT NULL
                                    THEN CONCAT(ti.title_name, ' - ', ti.title_edition)
                                    ELSE ti.title_name
                                END AS title,
                                ti.title_published AS published,
                                pl.platform_name AS platform,
                                mt.mediatype_name AS mediatype,
                                ti.title_type AS titletype,
                                ti.title_status AS owned,
                                ti.title_created AS created,
                                ti.title_modified AS modified,
                                ti.parent_id
                           FROM
                                title ti
                                LEFT JOIN title pa ON ti.parent_id = pa.title_id
                                INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                                INNER JOIN mediatype mt ON ti.mediatype_id = mt.mediatype_id
                           WHERE
                                1 = 1
                                $filter
                           ORDER BY $column $order, title
                           LIMIT :current_page, :records_per_page");
    $stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $library = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Row count
    $stmt = $pdo->query("SELECT COUNT(*) FROM title ti WHERE 1 = 1 $filter");
    $library_rows = $stmt->fetchColumn();

} catch (PDOException $e) {
    exit($e->getMessage());
}

// ---------- HTML ---------- //

require 'include/library_html.php';
?>