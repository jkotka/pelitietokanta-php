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
    PRICE => 'price',
    PURCHASED => 'purchased'
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

// From date
$filter_from = filter_date('pu.purchase_date', '>=', 'from');
$form_from = $filter_from['val'];
$filter .= $filter_from['sql'];

// To date
$filter_to = filter_date('pu.purchase_date', '<=', 'to');
$form_to = $filter_to['val'];
$filter .= $filter_to['sql'];

// Show
$form_show = 'purchased';
if (!empty($_GET['show'])) {
    if ($_GET['show'] === 'owned') {
        header("Location:library.php?sort=created&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&titletype[]=$form_titletype&show=owned");
        exit;
    } elseif ($_GET['show'] === 'wishlist') {
        header("Location:library.php?sort=created&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&titletype[]=$form_titletype&show=wishlist");
        exit;
    } elseif ($_GET['show'] === 'backlog') {
        header("Location:library.php?sort=created&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&titletype[]=$form_titletype&show=backlog");
        exit;
    } elseif ($_GET['show'] === 'played') {
        header("Location:played.php?sort=playstop&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&from=$form_from&to=$form_to");
        exit;
    } elseif ($_GET['show'] === 'beaten') {
        header("Location:played.php?sort=playstop&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&show=beaten&from=$form_from&to=$form_to");
        exit;
    } elseif ($_GET['show'] === 'all') {
        header("Location:library.php?sort=purchased&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&titletype[]=$form_titletype");
        exit;
    }
}

// Filter url for pagination and column sorting
$filter_url = "&amp;records=$records_per_page&amp;title=". urlencode($form_title) . "&amp;published%5B%5D=$form_published&amp;platform%5B%5D=$form_platform&amp;mediatype%5B%5D=$form_mediatype&amp;titletype%5B%5D=$form_titletype&amp;from=$form_from&amp;to=$form_to";

// ---------- MAIN QUERY ---------- //

try {
    // Purchases - apply filter and sorting if set
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
                                pu.purchase_price AS price,
                                pm.paymethod_name AS paymethod,
                                st.store_name AS store,
                                pu.purchase_date AS purchased,
                                ti.title_type AS titletype,
                                ti.parent_id
                           FROM
                                title ti
                                LEFT JOIN title pa ON ti.parent_id = pa.title_id
                                INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                                INNER JOIN mediatype mt ON ti.mediatype_id = mt.mediatype_id
                                INNER JOIN purchase pu ON ti.title_id = pu.title_id
                                LEFT JOIN paymethod pm USING (paymethod_id)
                                LEFT JOIN store st USING (store_id)
                           WHERE
                                ti.title_type IN (0, 1, 2)
                                $filter
                           ORDER BY $column $order, title, purchased
                           LIMIT :current_page, :records_per_page");
    $stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $purchased_titles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Row count and sum of purchases
    $stmt = $pdo->query("SELECT
                            COUNT(*) AS rowcount,
                            SUM(pu.purchase_price) AS price
                         FROM
                            purchase pu
                            INNER JOIN title ti USING (title_id)
                         WHERE
                            ti.title_type IN (0, 1, 2)
                            $filter");
    $purchase_totals = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    exit($e->getMessage());
}

// ---------- HTML ---------- //

require 'include/purchased_html.php';
?>