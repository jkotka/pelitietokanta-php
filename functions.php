<?php
// ---------- SETTINGS ---------- //

// MySQL config
require_once 'mysql_config.php';

// Cover image path
$covers = 'img/cover/';

// Thumbnail path
$thumbnails = 'img/cover/thumbnails/';

// Sanitized PHP_SELF
$thisfile = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// ---------- FUNCTIONS ---------- //

// Set language (default = en) -- set/extend cookie (30 days)
function set_lang() {
    $langs = array('fi', 'en');
    $lang = 'en';
    if (isset($_GET['lang']) && in_array($_GET['lang'], $langs)) {
        $lang = $_GET['lang'];
    } elseif (isset($_COOKIE['gdb_lang']) && in_array($_COOKIE['gdb_lang'], $langs)) {
        $lang = $_COOKIE['gdb_lang'];
    }
    setcookie('gdb_lang', $lang, ['expires' => time() + (60 * 60 * 24 * 30), 'path' => '/', 'samesite' => 'lax']);
    require "lang/$lang.php";
}
// Clean up data before output
function clean($data) {
    if (!empty($data)) {
        $data = htmlspecialchars($data, ENT_COMPAT, 'utf-8');
    }
    return $data;
}
// Type to text
function type_text($type) {
    if ($type === 0) {
        $text = GAME;
    } elseif ($type === 1) {
        $text = ADDON;
    } else {
        $text = COLLECTION;
    }
    return $text;
}
// Status to icon (PHP 8.0+)
function status_icon($status) {
    if (str_contains($status, 1)) {
        $icon = '<i class="far fa-check-circle yes" title="' . YES . '"></i>';
    } else {
        $icon = '<i class="far fa-times-circle no" title="' . NO . '"></i>';
    }
    if (str_contains($status, 2)) {
        $icon .= '&nbsp;&nbsp;<i class="far fa-heart" title="' . WISHLISTED . '"></i>';
    }
    if (str_contains($status, 3)) {
        $icon .= '&nbsp;&nbsp;<i class="far fa-list-alt" title="' . BACKLOGGED . '"></i>';
    }
    return $icon;
}
// Decimal to time
function dec_time($dec, $empty_return) {
    $time = $empty_return;
    if (!empty($dec)) {
        $hours = floor($dec);
        $mins = round(($dec-$hours)*60, 0);
        $time = str_pad($hours, 1, 0 , STR_PAD_LEFT) . ':' . str_pad($mins, 2, 0 , STR_PAD_LEFT);
    }
    return $time;
}
// Date convert
function date_cnv($date, $type, $empty_return) {
    $newdate = $empty_return;
    if (!empty($date)) {
        if ($type === 'date') {
            $newdate = date(DATE_FORMAT, strtotime($date));
        } elseif ($type === 'datetime') {
            $newdate = date(DATETIME_FORMAT, strtotime($date));
        }
    }
    return $newdate;
}
// Number format
function num_format($num, $dec, $empty_return) {
    $newnum = $empty_return;
    if (!empty($num)) {
        $newnum = number_format($num, $dec, DECIMAL_SEPARATOR, THOUSANDS_SEPARATOR);
    }
    return $newnum;
}
// Get cover image/thumbnail
function get_image($images) {
    if (file_exists($images['main'])) {
        $image = $images['main'];
    } elseif (file_exists($images['parent2'])) {
        $image = $images['parent2'];
    } elseif (file_exists($images['parent1'])) {
        $image = $images['parent1'];
    } elseif (file_exists($images['root'])) {
        $image = $images['root'];
    } else {
        $image = $images['placeholder'];
    }
    return $image . '?' . filemtime($image);
}
// Resize image and convert to jpeg
function resize_image($image, $extension, $width, $result_image) {
    list($width_orig, $height_orig) = getimagesize($image);
    $ratio = $width_orig/$height_orig;
    $height = $width/$ratio;
    $canvas = imagecreatetruecolor($width, $height);
    $create = 'imagecreatefrom' . ($extension === 'jpg' ? 'jpeg' : $extension);
    $new_image = $create($image);
    imagecopyresampled($canvas, $new_image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    imagejpeg($canvas, $result_image, 90);
}
// Message (error, success, notification)
function message() {
    $message =  '';
    if (!empty($_GET['e'])) {
        $msg = $_GET['e'];
        $type = 'error';
        $icon = '<i class="fas fa-exclamation-circle"></i>';
    } elseif (!empty($_GET['s'])) {
        $msg = $_GET['s'];
        $type = 'success';
        $icon = '<i class="fas fa-check-circle"></i>';
    } elseif (!empty($_GET['n'])) {
        $msg = $_GET['n'];
        $type = 'notification';
        $icon = '<i class="fas fa-info-circle"></i>';
    } else {
        $msg = '';
    }
    if (defined(strtoupper('msg_' . $msg))) {
        $message = '<input type="checkbox" id="message-check"><div class="message ' . $type . '"><span>' . $icon . constant(strtoupper('msg_' . $msg)) . '</span><label for="message-check"><i class="fas fa-times fa-sm"></i></label></div>';
    }
    return $message;
}

// ---------- PAGINATION ---------- //

// Records per page
function get_records() {
    if (isset($_GET['records']) && is_numeric($_GET['records'])) {
        $records_per_page = (int)$_GET['records'];
        if ($records_per_page < 20) {
            $records_per_page = 20;
        } elseif ($records_per_page > 100) {
            $records_per_page = 100;
        }
    } else {
        $records_per_page = 20;
    }
    return $records_per_page;
}
// Current page
function get_page() {
    if (!empty($_GET['page']) && is_numeric($_GET['page'])) {
        $page = (int)$_GET['page'];
    } else {
        $page = 1;
    }
    return $page;
}
// Records dropdown
function records_dropdown($records_per_page) {
    { ?>
        <select name="records">
            <option value="" hidden><?=SHOW . ": " . $records_per_page?></option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    <?php };
}

// ---------- SORT ---------- //

// Sort column
function sort_column($columns) {
    if (!empty($_GET['sort'])) {
        $sort = $_GET['sort'];
        if (in_array($sort, $columns)) {
            $column = $sort;
        } else {
            $column = 'title';
        }
    } else {
        $column = 'title';
    }
    return $column;
}
// Sort order
function sort_order() {
    if (!empty($_GET['order'])) {
        $sort_order = $_GET['order'];
        if ($sort_order === "desc") {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
    } else {
        $order = 'asc';
    }
    return $order;
}
// Sort direction and icon for sort link
function sort_link($col_name, $sort_column, $sort_order) {
    $asc_desc = 'asc';
    $sort_icon = '<i class="grey fas fa-sort fa-sm"></i>';
    if ($sort_column === $col_name && $sort_order === 'asc') {
        $asc_desc = 'desc';
        $sort_icon = '<i class="fas fa-sort-up fa-sm"></i>';
    } elseif ($sort_column === $col_name && $sort_order === 'desc') {
        $asc_desc = 'asc';
        $sort_icon = '<i class="fas fa-sort-down fa-sm"></i>';
    }
    return compact('asc_desc', 'sort_icon');
}

// ---------- FILTER ---------- //

// Filter by title and edition
function filter_title($input, $title, $edition, $pdo) {
    $val = '';
    $sql = '';
    if (!empty($_GET[$input])) {
        $val = $_GET[$input];
        $val_quote = $pdo->quote('%' . $val . '%');
        $sql = " AND ($title LIKE $val_quote OR $edition LIKE $val_quote)";
    }
    return compact('val', 'sql');
}
// Validate & filter by multiselect value(s) (only numbers, optionally separated by comma)
function validate_multi($input) {
    if (preg_match('/^([0-9]+,)*[0-9]+$/', $input)) {
        return true;
    } else {
        return false;
    }
}
function filter_multi($input, $column) {
    $val = '';
    $sql = '';
    if (!empty($_GET[$input])) {
        $val = implode(',', $_GET[$input]);
        if (validate_multi($val)) {
            $sql = " AND $column IN ($val)";
        } else {
            $sql = '';
        }
    }
    return compact('val', 'sql');
}
// Filter by date
function filter_date($column, $operator, $input) {
    $val = "";
    $sql = "";
    if (!empty($_GET[$input])) {
        $val = date("Y-m-d", strtotime($_GET[$input]));
        $sql = " AND $column $operator '$val'";
    }
    return compact("val", "sql");
}
// Filter show
function filter_show($show) {
    { ?>
        <label class="mobile-label" for="show"><?=SHOW?>:</label>
        <select id="show" name="show">
            <option value="owned"<?=$selected = $show === 'owned' ? ' selected' : '' ?>><?=OWNED?></option>
            <option value="played"<?=$selected = $show === 'played' ? ' selected' : '' ?>><?=PLAYED?></option>
            <option value="beaten"<?=$selected = $show === 'beaten' ? ' selected' : '' ?>><?=BEATEN?></option>
            <option value="purchased"<?=$selected = $show === 'purchased' ? ' selected' : '' ?>><?=PURCHASED?></option>
            <option value="wishlist"<?=$selected = $show === 'wishlist' ? ' selected' : '' ?>><?=WISHLIST?></option>
            <option value="backlog"<?=$selected = $show === 'backlog' ? ' selected' : '' ?>><?=BACKLOG?></option>
            <option value="all"<?=$selected = $show === 'all' ? ' selected' : '' ?>><?=SHOW_ALL?></option>
        </select>
    <?php };
}

// ---------- DATABASE ---------- //

// Database connection
function dbc() {
    try {
    	return new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE . ';charset=utf8', MYSQL_USER, MYSQL_PASS);
    } catch (PDOException $e) {
    	exit($e->getMessage());
    }
}
// List for dropdown menus
function get_list($column, $table, $order, $pdo) {
    $list = $pdo->query("SELECT DISTINCT $column FROM $table ORDER BY $order")->fetchAll(PDO::FETCH_ASSOC);
    return $list;
}
// Get parent ids, names (and editions) recursively (MySQL 8.0.1+)
function get_parents($id, $pdo) {
    try {
        $stmt = $pdo->prepare('WITH RECURSIVE cte (title_id, title_name, parent_id) AS (
                               SELECT
                                    title_id,
                                    CASE
                                        WHEN title_edition IS NOT NULL
                                        THEN CONCAT(title_name, " - ", title_edition)
                                        ELSE title_name
                                    END,
                                    parent_id
                               FROM title
                               WHERE title_id = ?
                               UNION ALL
                               SELECT
                                    t.title_id,
                                    CASE
                                        WHEN t.title_edition IS NOT NULL
                                        THEN CONCAT(t.title_name, " - ", t.title_edition)
                                        ELSE t.title_name
                                    END,
                                    t.parent_id
                               FROM
                                    title t
                                    INNER JOIN cte c ON t.title_id = c.parent_id
                               )
                               SELECT
                                    title_id AS id,
                                    title_name AS title
                               FROM cte
                               ORDER BY parent_id;');
        $stmt->execute([$id]);
        $parents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $parents;

    } catch (PDOException $e) {
    	exit($e->getMessage());
    }
}
// Get child ids recursively (MySQL 8.0.1+)
function get_children($id, $pdo) {
    try {
        $stmt = $pdo->prepare('WITH RECURSIVE cte (title_id) AS (
                               SELECT title_id
                               FROM title
                               WHERE parent_id = ?
                               UNION ALL
                               SELECT t.title_id
                               FROM
                                    title t
                                    INNER JOIN cte c ON t.parent_id = c.title_id
                               )
                               SELECT
                                    GROUP_CONCAT(title_id ORDER BY title_id) AS child_ids
                               FROM cte');
        $stmt->execute([$id]);
        $children = $stmt->fetchColumn();
        $child_array = explode(',', $children ?? '');
        return $child_array;

    } catch (PDOException $e) {
    	exit($e->getMessage());
    }
}

// ---------- TEMPLATE ---------- //

// Template header
function template_header($title) {
    require 'include/template_header.php';
}
// Template pagination
function template_pagination($file, $page, $column, $order, $url, $totalrows, $pagerows) {
    { ?>
        <div class="pagination">
            <?php if ($page > 1): // Links to prev page and page 1 if page is greater than 1 ?>
            <a href="<?=$file?>?page=<?=$page-1?>&amp;sort=<?=$column?>&amp;order=<?=$order?><?=$url?>"><i class="fas fa-arrow-left fa-sm"></i></a>
            <a href="<?=$file?>?page=1&amp;sort=<?=$column?>&amp;order=<?=$order?><?=$url?>">1</a>
            <?php endif; ?>

            <?php if ($pagerows < $totalrows): // Page number if there's more than 1 page ?>
            <span class="pagenumber"><?=$page?></span>
            <?php endif; ?>

            <?php if ($page * $pagerows < $totalrows): // Links to last page and next page if there are more pages to show ?>
            <a href="<?=$file?>?page=<?=ceil($totalrows/$pagerows)?>&amp;sort=<?=$column?>&amp;order=<?=$order?><?=$url?>"><?=ceil($totalrows/$pagerows)?></a>
            <a href="<?=$file?>?page=<?=$page+1?>&amp;sort=<?=$column?>&amp;order=<?=$order?><?=$url?>"><i class="fas fa-arrow-right fa-sm"></i></a>    
            <?php endif; ?>
        </div>
    <?php };
}
// Template footer
function template_footer() {
    require 'include/template_footer.php';
}
?>