<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// ---------- QUERIES ---------- //

try {
    // Last added
    $stmt = $pdo->prepare('SELECT
                              ti.title_id AS id,
                              CASE
                                   WHEN ti.title_edition IS NOT NULL
                                   THEN CONCAT(ti.title_name, " - ", ti.title_edition)
                                   ELSE ti.title_name
                              END AS title,
                              CASE
                                   WHEN pa.title_edition IS NOT NULL
                                   THEN CONCAT(" (", pa.title_name, " - ", pa.title_edition, ")")
                                   ELSE CONCAT(" (", pa.title_name, ")")
                              END AS parent_title,
                              pl.platform_name AS platform,
                              ti.parent_id
                           FROM
                              title ti
                              LEFT JOIN title pa ON ti.parent_id = pa.title_id
                              INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                           ORDER BY ti.title_created DESC
                           LIMIT 5');
    $stmt->execute();
    $last_added = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Last played
    $stmt = $pdo->prepare('SELECT
                              title_id AS id,
                              CASE
                                   WHEN title_edition IS NOT NULL
                                   THEN CONCAT(title_name, " - ", title_edition)
                                   ELSE title_name
                              END AS title,
                              platform_name AS platform,
                              parent_id
                           FROM
                              stat
                              INNER JOIN title USING (title_id)
                              INNER JOIN platform USING (platform_id)
                           WHERE title_type = 0
                           ORDER BY stat_stopped DESC, title_name
                           LIMIT 5');
    $stmt->execute();
    $last_played = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Last purchased
    $stmt = $pdo->prepare('SELECT
                              ti.title_id AS id,
                              CASE
                                   WHEN ti.title_edition IS NOT NULL
                                   THEN CONCAT(ti.title_name, " - ", ti.title_edition)
                                   ELSE ti.title_name
                              END AS title,
                              CASE
                                   WHEN pa.title_edition IS NOT NULL
                                   THEN CONCAT(" (", pa.title_name, " - ", pa.title_edition, ")")
                                   ELSE CONCAT(" (", pa.title_name, ")")
                              END AS parent_title,
                              pl.platform_name AS platform,
                              ti.parent_id
                           FROM
                              title ti
                              LEFT JOIN title pa ON ti.parent_id = pa.title_id
                              INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                              INNER JOIN purchase pu ON ti.title_id = pu.title_id
                           ORDER BY purchase_date DESC, ti.title_name
                           LIMIT 5');
    $stmt->execute();
    $last_purchased = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Owned games
    $stmt = $pdo->query('SELECT COUNT(*) FROM title WHERE title_type = 0 AND title_status IN (1, 12, 13)');
    $games_total = $stmt->fetchColumn();

    // Beaten games
    $stmt = $pdo->query('SELECT COUNT(*)
                         FROM
                              stat
                              INNER JOIN title USING (title_id)
                         WHERE title_type = 0
                         AND stat_beaten = 1');
    $beaten_total = $stmt->fetchColumn();

    // Played games
    $stmt = $pdo->query('SELECT COUNT(*)
                         FROM
                              stat
                              INNER JOIN title USING (title_id)
                         WHERE title_type = 0');
    $played_total = $stmt->fetchColumn();

    // Played hours
    $stmt = $pdo->query('SELECT SUM(stat_hours) FROM stat');
    $played_hours = $stmt->fetchColumn();

    // Played days
    $stmt = $pdo->query('SELECT datediff(MAX(stat_stopped), MIN(stat_started))+1 FROM stat');
    $played_days = $stmt->fetchColumn();

    // Number of purchases
    $stmt = $pdo->query('SELECT COUNT(*) FROM purchase');
    $purchases = $stmt->fetchColumn();

    // Sum of purchases
    $stmt = $pdo->query('SELECT SUM(purchase_price) FROM purchase');
    $sum_total = $stmt->fetchColumn();

    // Wishlisted titles
    $stmt = $pdo->query('SELECT COUNT(*) FROM title WHERE title_status IN (2, 12, 23, 123)');
    $wishlisted = $stmt->fetchColumn();

    // Wishlisted titles
    $stmt = $pdo->query('SELECT COUNT(*) FROM title WHERE title_status IN (3, 13, 23, 123)');
    $backlogged = $stmt->fetchColumn();

} catch (PDOException $e) {
    exit($e->getMessage());
}

// ---------- HTML ---------- //

require 'include/index_html.php';
?>