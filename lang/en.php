<?php
// ---------- REGIONAL SETTINGS ---------- //

define('DATE_FORMAT', 'd/m/Y'); // e.g. d.m.y or Y-m-d (y = 2 digit year, Y = 4 digit year)
define('DATETIME_FORMAT', 'd/m/y H:i'); // e.g. d.m.y H:i (= 31.12.21 18:00) or Y-m-d h:i a (= 2021-12-31 6:00 pm)
define('DECIMAL_SEPARATOR', '.');
define('THOUSANDS_SEPARATOR', ',');

define('CURRENCY_BEFORE', '£'); // Leave empty if currency symbol comes after the sum
define('CURRENCY_AFTER', ''); // Leave empty if currency symbol comes before the sum

// ---------- GENERAL ---------- //

define('SELECT_LANG', "Select language");
define('EXPORT_DATA', "Export data");

define('YES', 'Yes');
define('NO', 'No');

define('ADD', 'Add');
define('EDIT', 'Edit');
define('ADVANCED_EDIT', 'Advanced');
define('PARENT_ID', 'Parent ID');

define('SAVE', 'Save');
define('SAVE_NEW', 'Save as new');
define('DELETE', 'Delete');
define('CANCEL', 'Cancel');

define('GAME', 'Game');
define('GAMES', 'Games');
define('NO_GAMES', 'No games');
define('ADDON', 'Addon');
define('ADDONS', 'Addons');
define('NO_ADDONS', 'Ei lisäosia');
define('COLLECTION', 'Collection');
define('INCLUDED_IN', 'Included in');

define('LIBRARY', 'Library');
define('WISHLIST', 'Wishlist');
define('WISHLISTED', 'Wishlisted');
define('BACKLOG', 'Backlog');
define('BACKLOGGED', 'Backlogged');
define('OWNED', 'Owned');

define('TITLE', 'Title');
define('EDITION', 'Edition (e.g. Game of the Year Edition)');
define('TITLETYPE', 'Title type');

define('PUBLISHED', 'Published');
define('INFO', 'More info');
define('CREATED', 'Added');
define('MODIFIED', 'Modified');

define('SETTINGS', 'Settings');
define('HOME', 'Home');
define('GAME_DATABASE', 'Game database');
define('SEARCH', 'Search');

define('TOTAL', 'Total');
define('SHOW', 'Show');
define('SHOW_ALL', 'Show all');
define('FILTER', 'Filter');

define('PLATFORM', 'Platform');
define('PLATFORMS', 'Platforms');
define('DELETE_PLATFORM', 'Delete platform');

define('MEDIATYPE', 'Media type');
define('MEDIATYPES', 'Media types');
define('DELETE_MEDIATYPE', 'Delete media type');

define('PAYMETHOD', 'Pay method');
define('PAYMETHODS', 'Pay methods');
define('DELETE_PAYMETHOD', 'Delete pay method');

define('STORE', 'Store');
define('STORES', 'Stores');
define('DELETE_STORE', 'Delete store');

define('PURCHASED', 'Purchased');
define('PURCHASE_INFO', 'Purchase info');
define('NO_PURCHASE_INFO', 'No purchase info');
define('DELETE_PURCHASE', 'Delete purchase info');

define('GAMESTATS', 'Game stats');
define('NO_GAMESTATS', 'No game stats');
define('DELETE_GAMESTAT', 'Delete game stat');

define('PRICE', 'Price');
define('TOTAL_PRICE', 'Total price');
define('AVERAGE_PRICE', 'Average price');

define('DAYS', 'Days');
define('DAYS_SHORT', 'd');
define('YEARS_SHORT', 'yr');
define('STARTED', 'Started');
define('STOPPED', 'Stopped');
define('START_DATE', 'Start date');
define('END_DATE', 'End date');

define('HOURS', 'Hours');
define('HOURS_SHORT', 'h');
define('HOURS_PER_DAY', 'Hours per day');
define('HOURS_PER_DAY_SHORT', 'h/day');
define('HOURS_PLAYED', 'Hours played');
define('HOURS_PER_GAME', 'Hours per game');

define('PLAYED', 'Played');
define('BEATEN', 'Beaten');

define('LAST_ADDED', 'Last added');
define('LAST_PLAYED', 'Last played');
define('LAST_PURCHASED', 'Last purchased');

define('CHOOSE_IMAGE', 'Choose image');
define('DELETE_IMAGE', 'Delete image');

// ---------- MESSAGES ---------- //

define('MSG_FORM_INCOMPLETE', 'Incomplete form.');
define('MSG_TITLE_NOT_FOUND', 'Title was not found.');
define('MSG_SETTINGS_DB_NOTIFICATION', 'Changes were not saved. Possible reasons: data already exists or attempted to remove data that are in use.');
define('MSG_IMG_UPLOAD_ERROR', 'Image must be jpg/jpeg, png, gif, bmp or webp and 5MB or less in size.');
define('MSG_SAVE_SUCCESSFUL', 'Save was successful.');
define('MSG_UPDATE_SUCCESSFUL', 'Update was successful.');
define('MSG_DELETE_SUCCESSFUL', 'Delete was successful.');

// ---------- DROPDOWN LISTS ---------- //

define('PUBLISHED_DROPDOWN', 'Published:');
define('PLATFORM_DROPDOWN', 'Platform:');
define('MEDIATYPE_DROPDOWN', 'Media type:');
define('TITLETYPE_DROPDOWN', 'Title type:');
define('PAYMETHOD_DROPDOWN', 'Pay method:');
define('STORE_DROPDOWN', 'Store:');
?>