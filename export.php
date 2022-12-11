<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();
$db_name = $pdo->query('SELECT DATABASE()')->fetchColumn();

// Format values - null if not set, quoted if not number
function format($value, $pdo) {
    if (!isset($value)) {
        $value = 'NULL';
    } elseif (!is_numeric($value) ) {
        $value = $pdo->quote($value);
    }
    return $value;
}

// ---------- SQL EXPORT ---------- //

if (isset($_GET['file']) && $_GET['file'] === 'sql') {

    // ---------- HEADERS ---------- //

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($db_name . '_' . date('Y_m_d_Hi') . '.sql'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: no-cache');

    // ---------- DATABASE & TABLES ---------- //

    echo '-- ' . GAME_DATABASE . ' ' . $db_name . ' ' . date(DATETIME_FORMAT) . "\n\n";

    echo "SET foreign_key_checks = 0;\n";
    
    echo "CREATE DATABASE IF NOT EXISTS `$db_name`;\n";
    echo "USE `$db_name`;\n\n";

    $tables = array('platform', 'mediatype', 'store', 'paymethod', 'title', 'purchase', 'stat');
    foreach ($tables as $table) {
        echo "DROP TABLE IF EXISTS `$table`;\n";
        $stmt = $pdo->query("SHOW CREATE TABLE $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $result['Create Table'] . ";\n\n";
    }

    // ---------- TABLE DATA ---------- //

    // Platform, mediatype, store & paymethod tables
    $tables = array('platform', 'mediatype', 'store', 'paymethod');

    foreach ($tables as $table) {
        $stmt = $pdo->query('SELECT * FROM `' . $table . '`
                            ORDER BY `' . $table . '_id` ASC
                            ');
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($results);
        $iter = 0;

        echo "INSERT INTO `$table` VALUES\n";

        foreach ($results as $result) {
            $iter = $iter+1;
            echo '(';
            echo format($result[$table . '_id'], $pdo) . ',';
            echo format($result[$table . '_name'], $pdo);
            echo ')';
            if ($iter < $count) {echo ",\n";} else {echo ';';}
        }
        echo "\n\n";
    }

    // Title table
    $stmt = $pdo->prepare('SELECT * FROM `title`
                        ORDER BY `title_id` ASC
                        ');
    $stmt->execute();
    $titledetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($titledetails);
    $iter = 0;

    echo "INSERT INTO `title` VALUES\n";

    foreach ($titledetails as $titledetail) {
        $iter = $iter+1;
        echo '(';
        echo format($titledetail['title_id'], $pdo) . ',';
        echo format($titledetail['title_name'], $pdo) . ',';
        echo format($titledetail['title_edition'], $pdo) . ',';
        echo format($titledetail['title_published'], $pdo) . ',';
        echo format($titledetail['platform_id'], $pdo) . ',';
        echo format($titledetail['mediatype_id'], $pdo) . ',';
        echo format($titledetail['parent_id'], $pdo) . ',';
        echo format($titledetail['title_type'], $pdo) . ',';
        echo format($titledetail['title_status'], $pdo) . ',';
        echo format($titledetail['title_info'], $pdo) . ',';
        echo format($titledetail['title_created'], $pdo) . ',';
        echo format($titledetail['title_modified'], $pdo);
        echo ')';
        if ($iter < $count) {echo ",\n";} else {echo ';';}
    }
    echo "\n\n";

    // Purchase table
    $stmt = $pdo->query('SELECT * FROM `purchase`
                        ORDER BY `purchase_id` ASC
                        ');
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($purchases);
    $iter = 0;

    echo "INSERT INTO `purchase` VALUES\n";

    foreach ($purchases as $purchase) {
        $iter = $iter+1;
        echo '(';
        echo format($purchase['purchase_id'], $pdo) . ',';
        echo format($purchase['title_id'], $pdo) . ',';
        echo format($purchase['paymethod_id'], $pdo) . ',';
        echo format($purchase['store_id'], $pdo) . ',';
        echo format($purchase['purchase_price'], $pdo) . ',';
        echo format($purchase['purchase_date'], $pdo) . ',';
        echo format($purchase['purchase_info'], $pdo) . ',';
        echo format($purchase['purchase_created'], $pdo) . ',';
        echo format($purchase['purchase_modified'], $pdo);
        echo ')';
        if ($iter < $count) {echo ",\n";} else {echo ';';}
    }
    echo "\n\n";

    // Stat table
    $stmt = $pdo->query('SELECT * FROM `stat`
                        ORDER BY `stat_id` ASC
                        ');
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = count($stats);
    $iter = 0;

    echo "INSERT INTO `stat` VALUES\n";

    foreach ($stats as $stat) {
        $iter = $iter+1;
        echo '(';
        echo format($stat['stat_id'], $pdo) . ',';
        echo format($stat['title_id'], $pdo) . ',';
        echo format($stat['stat_started'], $pdo) . ',';
        echo format($stat['stat_stopped'], $pdo) . ',';
        echo format($stat['stat_hours'], $pdo) . ',';
        echo format($stat['stat_beaten'], $pdo) . ',';
        echo format($stat['stat_info'], $pdo) . ',';
        echo format($stat['stat_created'], $pdo) . ',';
        echo format($stat['stat_modified'], $pdo);
        echo ')';
        if ($iter < $count) {echo ",\n";} else {echo ';';}
    }

    echo "\nSET foreign_key_checks = 1;";

// ---------- CSV EXPORT ---------- //

} elseif (isset($_GET['file']) && $_GET['file'] === 'csv') {

    // ---------- HEADERS ---------- //

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($db_name . '_' . date('Y_m_d_Hi') . '.csv'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: no-cache');

    // ---------- MAIN QUERY ---------- //

    $stmt = $pdo->query('SELECT
                            CONCAT_WS(" - ", title_name, title_edition) AS `name`,
                            title_published AS published,
                            platform_name AS platform,
                            mediatype_name AS media
                         FROM
                            title
                            INNER JOIN platform USING (platform_id)
                            INNER JOIN mediatype USING (mediatype_id)
                         WHERE title_type = 0
                         AND title_status IN (1, 12, 13, 123)
                         ORDER BY title_name, platform_name;');
    $titles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ---------- OUTPUT (separated by semicolon) ---------- //

    echo TITLE . ';' . PUBLISHED . ';' . PLATFORM . ';' . MEDIATYPE . "\n";
    foreach ($titles as $title) {
        echo $title['name'] . ';' . $title['published'] . ';' . $title['platform'] . ';' . $title['media'] . ";\n";
    }

} else {
    exit(NOTHING_TO_DO);
}
?>