<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// ---------- AUTOCOMPLETE ---------- //

// JQuery autocomplete uses 'term'
if (!empty($_GET['term']) && strlen($_GET['term']) > 1) {
    $query = $_GET['term'];

    // Suggest title (main search) or name/edition (forms)
    if (!empty($_GET['suggest'])) {

        try {
            // Suggest title
            if ($_GET['suggest'] === 'title') {
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
                                            pl.platform_name AS platform
                                       FROM
                                            title ti
                                            LEFT JOIN title pa ON ti.parent_id = pa.title_id
                                            INNER JOIN platform pl ON ti.platform_id = pl.platform_id
                                       WHERE
                                            ti.title_name LIKE :query OR
                                            ti.title_edition LIKE :query
                                       ORDER BY ti.title_name
                                       LIMIT 10');
                $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
                $stmt->execute();

                // Array with id and value for main search autocomplete
                while($row = $stmt->fetch()) {
                    $result[] = array('id' => $row['id'], 'value' => $row['title'] . $row['parent_title'] . ' (' . $row['platform'] . ')');
                }
            }
            // Suggest name
            elseif ($_GET['suggest'] === 'name') {
                $stmt = $pdo->prepare('SELECT DISTINCT title_name AS title
                                       FROM title
                                       WHERE title_name LIKE :query
                                       ORDER BY title_name
                                       LIMIT 10');
                $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
                $stmt->execute();

                // Array with value for name autocomplete
                while($row = $stmt->fetch()) {
                    $result[] = array('value' => $row['title']);
                }
            }
            // Suggest edition
            elseif ($_GET['suggest'] === 'edition') {
                $stmt = $pdo->prepare('SELECT DISTINCT title_edition AS `edition`
                                       FROM title
                                       WHERE title_edition LIKE :query
                                       ORDER BY title_edition
                                       LIMIT 10');
                $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
                $stmt->execute();

                // Array with value for edition autocomplete
                while($row = $stmt->fetch()) {
                    $result[] = array('value' => $row['edition']);
                }
            }

            // JSON encode for jQuery autocomplete
            if (!empty($result)) {
                echo json_encode($result);
            }

        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
    
}