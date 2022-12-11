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
    STARTED => 'playstart',
    STOPPED => 'playstop',
    HOURS => 'playhours',
    BEATEN => 'beaten'
);

// Sort column and order
$column = sort_column($columns);
$order = sort_order();

// ---------- FILTER PROCESSING ---------- //

// Empty the filter
$filter = '';

// Title
$filter_title = filter_title('title', 'title_name', 'title_edition', $pdo);
$form_title = $filter_title['val'];
$filter .= $filter_title['sql'];

// Published
$filter_published = filter_multi('published', 'title_published');
$form_published = $filter_published['val'];
$filter .= $filter_published['sql'];

// Platform
$filter_platform = filter_multi('platform', 'platform_id');
$form_platform = $filter_platform['val'];
$filter .= $filter_platform['sql'];

// Mediatype
$filter_mediatype = filter_multi('mediatype', 'mediatype_id');
$form_mediatype = $filter_mediatype['val'];
$filter .= $filter_mediatype['sql'];

// From date
$filter_from = filter_date('stat_stopped', '>=', 'from');
$form_from = $filter_from['val'];
$filter .= $filter_from['sql'];

// To date
$filter_to = filter_date('stat_stopped', '<=', 'to');
$form_to = $filter_to['val'];
$filter .= $filter_to['sql'];

// Show
$form_show = 'played';
$total = PLAYED;
if (!empty($_GET['show'])) {
    if ($_GET['show'] === 'owned') {
        header("Location:library.php?sort=created&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&show=owned");
        exit;
    } elseif ($_GET['show'] === 'wishlist') {
        header("Location:library.php?sort=created&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&show=wishlist");
        exit;
    } elseif ($_GET['show'] === 'backlog') {
        header("Location:library.php?sort=created&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&show=backlog");
        exit;
    } elseif ($_GET['show'] === 'beaten') {
        $form_show = 'beaten';
        $filter .= ' AND stat_beaten = 1';
        $total = BEATEN;
    } elseif ($_GET['show'] === 'purchased') {
        header("Location:purchased.php?sort=purchased&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype&from=$form_from&to=$form_to");
        exit;
    } elseif ($_GET['show'] === 'all') {
        header("Location:library.php?sort=purchased&order=desc&records=$records_per_page&title=" . urlencode($form_title) . "&published[]=$form_published&platform[]=$form_platform&mediatype[]=$form_mediatype");
        exit;
    }
}

// Filter url for pagination and column sorting
$filter_url = "&amp;records=$records_per_page&amp;title=" . urlencode($form_title) . "&amp;published%5B%5D=$form_published&amp;platform%5B%5D=$form_platform&amp;mediatype%5B%5D=$form_mediatype&amp;from=$form_from&amp;to=$form_to&amp;show=$form_show";

// ---------- MAIN QUERY ---------- //

try {
    // Played titles - apply filter and sorting if set
    $stmt = $pdo->prepare("SELECT
                                title_id AS id,
                                CASE
                                    WHEN SUBSTRING_INDEX(title_name, ' ', 1) IN ('a', 'an', 'the') AND title_edition IS NOT NULL
                                    THEN CONCAT(SUBSTRING(title_name, INSTR(title_name, ' ') + 1 ), ' - ', title_edition, ', ', SUBSTRING_INDEX(title_name, ' ', 1))
                                    WHEN SUBSTRING_INDEX(title_name, ' ', 1) IN ('a', 'an', 'the')
                                    THEN CONCAT(SUBSTRING(title_name, INSTR(title_name, ' ') + 1 ), ', ', SUBSTRING_INDEX(title_name, ' ', 1))
                                    WHEN title_edition IS NOT NULL
                                    THEN CONCAT(title_name, ' - ', title_edition)
                                    ELSE title_name
                                END AS title,
                                title_published AS published,
                                platform_name AS platform,
                                mediatype_name AS mediatype,
                                stat_started AS playstart,
                                stat_stopped AS playstop,
                                stat_hours AS playhours,
                                stat_beaten AS beaten
                           FROM
                                stat
                                INNER JOIN title USING (title_id)
                                INNER JOIN platform USING (platform_id)
                                INNER JOIN mediatype USING (mediatype_id)
                           WHERE
                                title_type = 0
                                $filter
                           ORDER BY $column $order, title, playstop
                           LIMIT :current_page, :records_per_page");
    $stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $played_titles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Row count, total hours and days played
    $stmt = $pdo->query("SELECT
                            COUNT(*) AS rowcount,
                            SUM(stat_hours) AS totalhours,
                            datediff(MAX(stat_stopped), MIN(stat_started))+1 AS totaldays
                         FROM
                            stat
                            INNER JOIN title USING (title_id)
                         WHERE
                            title_type = 0
                            $filter");
    $played_totals = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    exit($e->getMessage());
}

// ---------- HTML ---------- //

require 'include/played_html.php';
?>