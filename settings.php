<?php
// ---------- FUNCTIONS ---------- //

require_once 'functions.php';
set_lang();
$pdo = dbc();

// ---------- FORM PROCESSING ---------- //

// If any of the forms are submitted
if (isset($_GET['form']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_GET['form'];

    // Validated set to true (set to false if form filled incorrectly)
    $validated = true;

    // ---------- PLATFORM FORM ---------- //

    // Check if platform form
    if ($form === 'platform') {

        // Platform id
        $platform_id = filter_var($_POST['platform'], FILTER_VALIDATE_INT);

        // Platform name (required, min. 2 chars)
        if (!empty($_POST['platform_name'])) {
            $platform_name = trim($_POST['platform_name']);
            if (strlen($platform_name) < 2) {
                $validated = false;
            }
        } else {
            $validated = false;
        }

        // Delete platform (checkbox)
        if (!isset($_POST['delete_platform'])) {
            $delete_platform = 0;
        } else {
            $delete_platform = 1;
            if (!empty($platform_id)) {
                $validated = true;
            } else {
                $validated = false;
            }
        }

        // If form is filled correctly
        if ($validated === true) {

            // ---------- PLATFORM INSERT / UPDATE / DELETE ---------- //

            try {
                // Insert new platform
                if (empty($platform_id) && !empty($platform_name)) {
                    $stmt = $pdo->prepare('INSERT INTO platform (platform_name)
                                           VALUES (?)');
                    $stmt->execute([$platform_name]);
                    header("Location:$thisfile?show=platforms&s=save_successful");
                    exit();

                // Update platform
                } elseif (!empty($platform_id) && !empty($platform_name) && $delete_platform !== 1) {
                    $stmt = $pdo->prepare('UPDATE platform
                                           SET platform_name = ?
                                           WHERE platform_id = ?');
                    $stmt->execute([$platform_name, $platform_id]);
                    header("Location:$thisfile?show=platforms&s=update_successful");
                    exit();

                // Delete platform
                } elseif (!empty($platform_id) && $delete_platform === 1) {
                    $stmt = $pdo->prepare('DELETE FROM platform
                                           WHERE platform_id = ?');
                    $stmt->execute([$platform_id]);
                    header("Location:$thisfile?show=platforms&s=delete_successful");
                    exit();
                }

            } catch (PDOException $e) {
                header("Location:$thisfile?show=platforms&n=settings_db_notification");
                exit();
            }     
        } else {
            header("Location:$thisfile?show=platforms&e=form_incomplete");
            exit();
        }
    }

    // ---------- MEDIATYPE FORM ---------- //

    // Check if mediatype form
    elseif ($form === 'mediatype') {

        // Mediatype id
        $mediatype_id = filter_var($_POST['mediatype'], FILTER_VALIDATE_INT);

        // Mediatype name (required, min. 2 chars)
        if (!empty($_POST['mediatype_name'])) {
            $mediatype_name = trim($_POST['mediatype_name']);
            if (strlen($mediatype_name) < 2) {
                $validated = false;
            }
        } else {
            $validated = false;
        }

        // Delete mediatype (checkbox)
        if (!isset($_POST['delete_mediatype'])) {
            $delete_mediatype = 0;
        } else {
            $delete_mediatype = 1;
            if (!empty($mediatype_id)) {
                $validated = true;
            } else {
                $validated = false;
            }
        }

        // If form is filled correctly
        if ($validated === true) {

            // ---------- MEDIATYPE INSERT / UPDATE / DELETE ---------- //
        
            try {
                // Insert new mediatype
                if (empty($mediatype_id) && !empty($mediatype_name)) {
                    $stmt = $pdo->prepare('INSERT INTO mediatype (mediatype_name)
                                           VALUES (?)');
                    $stmt->execute([$mediatype_name]);
                    header("Location:$thisfile?show=mediatypes&s=save_successful");
                    exit();

                // Update mediatype
                } elseif (!empty($mediatype_id) && !empty($mediatype_name) && $delete_mediatype !== 1) {
                    $stmt = $pdo->prepare('UPDATE mediatype
                                           SET mediatype_name = ?
                                           WHERE mediatype_id = ?');
                    $stmt->execute([$mediatype_name, $mediatype_id]);
                    header("Location:$thisfile?show=mediatypes&s=update_successful");
                    exit();

                // Delete mediatype
                } elseif (!empty($mediatype_id) && $delete_mediatype === 1) {
                    $stmt = $pdo->prepare('DELETE FROM mediatype
                                           WHERE mediatype_id = ?');
                    $stmt->execute([$mediatype_id]);
                    header("Location:$thisfile?show=mediatypes&s=delete_successful");
                    exit();
                }

            } catch (PDOException $e) {
                header("Location:$thisfile?show=mediatypes&n=settings_db_notification");
                exit();
            }
        } else {
            header("Location:$thisfile?show=mediatypes&e=form_incomplete");
            exit();
        }
    }

    // ---------- PAYMETHOD FORM ---------- //

    // Check if paymethod form
    elseif ($form === 'paymethod') {

        // Paymethod id
        $paymethod_id = filter_var($_POST['paymethod'], FILTER_VALIDATE_INT);

        // Paymethod name (required, min. 2 chars)
        if (!empty($_POST['paymethod_name'])) {
            $paymethod_name = trim($_POST['paymethod_name']);
            if (strlen($paymethod_name) < 2) {
                $validated = false;
            }
        } else {
            $validated = false;
        }

        // Delete paymethod (checkbox)
        if (!isset($_POST['delete_paymethod'])) {
            $delete_paymethod = 0;
        } else {
            $delete_paymethod = 1;
            if (!empty($paymethod_id)) {
                $validated = true;
            } else {
                $validated = false;
            }
        }

        // If form is filled correctly
        if ($validated === true) {

            // ---------- PAYMETHOD INSERT / UPDATE / DELETE ---------- //
        
            try {
                // Insert new paymethod
                if (empty($paymethod_id) && !empty($paymethod_name)) {
                    $stmt = $pdo->prepare('INSERT INTO paymethod (paymethod_name)
                                           VALUES (?)');
                    $stmt->execute([$paymethod_name]);
                    header("Location:$thisfile?show=paymethods&s=save_successful");
                    exit();

                // Update paymethod
                } elseif (!empty($paymethod_id) && !empty($paymethod_name) && $delete_paymethod !== 1) {
                    $stmt = $pdo->prepare('UPDATE paymethod
                                           SET paymethod_name = ?
                                           WHERE paymethod_id = ?');
                    $stmt->execute([$paymethod_name, $paymethod_id]);
                    header("Location:$thisfile?show=paymethods&s=update_successful");
                    exit();

                // Delete paymethod
                } elseif (!empty($paymethod_id) && $delete_paymethod === 1) {
                    $stmt = $pdo->prepare('DELETE FROM paymethod
                                           WHERE paymethod_id = ?');
                    $stmt->execute([$paymethod_id]);
                    header("Location:$thisfile?show=paymethods&s=delete_successful");
                    exit();
                }

            } catch (PDOException $e) {
                header("Location:$thisfile?show=paymethods&n=settings_db_notification");
                exit();
            }
        } else {
            header("Location:$thisfile?show=paymethods&e=form_incomplete");
            exit();
        }
    }

    // ---------- STORE FORM ---------- //

    // Check if store form
    elseif ($form === 'store') {

        // Store id
        $store_id = filter_var($_POST['store'], FILTER_VALIDATE_INT);

        // Store name (required, min. 2 chars)
        if (!empty($_POST['store_name'])) {
            $store_name = trim($_POST['store_name']);
            if (strlen($store_name) < 2) {
                $validated = false;
            }
        } else {
            $validated = false;
        }

        // Delete store (checkbox)
        if (!isset($_POST['delete_store'])) {
            $delete_store = 0;
        } else {
            $delete_store = 1;
            if (!empty($store_id)) {
                $validated = true;
            } else {
                $validated = false;
            }
        }

        // If form is filled correctly
        if ($validated === true) {

            // ---------- STORE INSERT / UPDATE / DELETE ---------- //
        
            try {
                // Insert new store
                if (empty($store_id) && !empty($store_name)) {
                    $stmt = $pdo->prepare('INSERT INTO store (store_name)
                                           VALUES
                                                (?)');
                    $stmt->execute([$store_name]);
                    header("Location:$thisfile?show=stores&s=save_successful");
                    exit();

                // Update store
                } elseif (!empty($store_id) && !empty($store_name) && $delete_store !== 1) {
                    $stmt = $pdo->prepare('UPDATE store
                                           SET
                                                store_name = ?
                                           WHERE store_id = ?');
                    $stmt->execute([$store_name, $store_id]);
                    header("Location:$thisfile?show=stores&s=update_successful");
                    exit();

                // Delete store
                } elseif (!empty($store_id) && $delete_store === 1) {
                    $stmt = $pdo->prepare('DELETE FROM store
                                           WHERE store_id = ?');
                    $stmt->execute([$store_id]);
                    header("Location:$thisfile?show=stores&s=delete_successful");
                    exit();
                }

            } catch (PDOException $e) {
                header("Location:$thisfile?show=stores&n=settings_db_notification");
                exit();
            }
        } else {
            header("Location:$thisfile?show=stores&e=form_incomplete");
            exit();
        }
    }
}

// ---------- HTML ---------- //

require 'include/settings_html.php';

template_footer();
?>