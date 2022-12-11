<?php
// ---------- ALUEELLISET ASETUKSET ---------- //

define('DATE_FORMAT', 'd.m.y'); // esim. d.m.y or Y-m-d (y = 2-numeroinen vuosiluku, Y = 4-numeroinen vuosiluku)
define('DATETIME_FORMAT', 'd.m.y H:i'); // esim. d.m.y H:i (= 31.12.21 18:00) tai Y-m-d h:i a (= 2021-12-31 6:00 pm)
define('DECIMAL_SEPARATOR', ',');
define('THOUSANDS_SEPARATOR', ' ');

define('CURRENCY_BEFORE', ''); // Jätä tyhjäksi jos rahayksikkö tulee summan jälkeen
define('CURRENCY_AFTER', '&nbsp;€'); // Jätä tyhjäksi jos rahayksikkö tulee ennen summaa

// ---------- YLEISET ---------- //

define('SELECT_LANG', "Valitse kieli");
define('EXPORT_DATA', "Vie tiedot");

define('YES', 'Kyllä');
define('NO', 'Ei');

define('ADD', 'Lisää');
define('EDIT', 'Muokkaa');
define('ADVANCED_EDIT', 'Lisäasetukset');
define('PARENT_ID', 'Isäntä-ID');

define('SAVE', 'Tallenna');
define('SAVE_NEW', 'Tallenna uutena');
define('DELETE', 'Poista');
define('CANCEL', 'Peruuta');

define('GAME', 'Peli');
define('GAMES', 'Pelit');
define('NO_GAMES', 'Ei pelejä');
define('ADDON', 'Lisäosa');
define('ADDONS', 'Lisäosat');
define('NO_ADDONS', 'Ei lisäosia');
define('COLLECTION', 'Kokoelma');
define('INCLUDED_IN', 'Sisältyy');

define('LIBRARY', 'Kirjasto');
define('WISHLIST', 'Toivelista');
define('WISHLISTED', 'Toivelistalla');
define('BACKLOG', 'Pelijono');
define('BACKLOGGED', 'Pelijonossa');
define('OWNED', 'Omistettu');

define('TITLE', 'Nimi');
define('EDITION', 'Painos (esim. Game of the Year Edition)');
define('TITLETYPE', 'Tyyppi');

define('PUBLISHED', 'Julkaistu');
define('INFO', 'Lisätiedot');
define('CREATED', 'Lisätty');
define('MODIFIED', 'Muokattu');

define('SETTINGS', 'Asetukset');
define('HOME', 'Etusivu');
define('GAME_DATABASE', 'Pelitietokanta');
define('SEARCH', 'Haku');

define('TOTAL', 'Yhteensä');
define('SHOW', 'Näytä');
define('SHOW_ALL', 'Näytä kaikki');
define('FILTER', 'Suodatus');

define('PLATFORM', 'Alusta');
define('PLATFORMS', 'Alustat');
define('DELETE_PLATFORM', 'Poista alusta');

define('MEDIATYPE', 'Media');
define('MEDIATYPES', 'Mediat');
define('DELETE_MEDIATYPE', 'Poista media');

define('PAYMETHOD', 'Maksutapa');
define('PAYMETHODS', 'Maksutavat');
define('DELETE_PAYMETHOD', 'Poista maksutapa');

define('STORE', 'Kauppa');
define('STORES', 'Kaupat');
define('DELETE_STORE', 'Poista kauppa');

define('PURCHASED', 'Ostettu');
define('PURCHASE_INFO', 'Ostotiedot');
define('NO_PURCHASE_INFO', 'Ei ostotietoja');
define('DELETE_PURCHASE', 'Poista ostotiedot');

define('GAMESTATS', 'Pelitilastot');
define('NO_GAMESTATS', 'Ei pelitilastoja');
define('DELETE_GAMESTAT', 'Poista pelitilasto');

define('PRICE', 'Hinta');
define('TOTAL_PRICE', 'Yhteishinta');
define('AVERAGE_PRICE', 'Keskihinta');

define('DAYS', 'Päivät');
define('DAYS_SHORT', 'pv');
define('YEARS_SHORT', 'v');
define('STARTED', 'Aloitettu');
define('STOPPED', 'Lopetettu');
define('START_DATE', 'Alkupvm');
define('END_DATE', 'Loppupvm');

define('HOURS', 'Tunnit');
define('HOURS_SHORT', 'h');
define('HOURS_PER_DAY', 'Tuntia päivässä');
define('HOURS_PER_DAY_SHORT', 'h/pv');
define('HOURS_PLAYED', 'Tuntia pelattu');
define('HOURS_PER_GAME', 'Tuntia per peli');

define('PLAYED', 'Pelattu');
define('BEATEN', 'Läpipelattu');

define('LAST_ADDED', 'Viimeksi lisätty');
define('LAST_PLAYED', 'Viimeksi pelattu');
define('LAST_PURCHASED', 'Viimeksi ostettu');

define('CHOOSE_IMAGE', 'Valitse kuva');
define('DELETE_IMAGE', 'Poista kuva');

// ---------- ILMOITUKSET ---------- //

define('MSG_FORM_INCOMPLETE', 'Puutteelliset lomaketiedot.');
define('MSG_TITLE_NOT_FOUND', 'Nimikettä ei löytynyt.');
define('MSG_SETTINGS_DB_NOTIFICATION', 'Muutoksia ei tallennettu. Mahdollisia syitä: data on jo olemassa tai yritettiin poistaa käytössä olevaa dataa.');
define('MSG_IMG_UPLOAD_ERROR', 'Kuvan täytyy olla jpg/jpeg, png, gif, bmp tai webp ja koon enintään 5MB.');
define('MSG_SAVE_SUCCESSFUL', 'Tallennus onnistui.');
define('MSG_UPDATE_SUCCESSFUL', 'Päivitys onnistui.');
define('MSG_DELETE_SUCCESSFUL', 'Poisto onnistui.');

// ---------- PUDOTUSVALIKOT ---------- //

define('PUBLISHED_DROPDOWN', 'Julkaistu:');
define('PLATFORM_DROPDOWN', 'Alusta:');
define('MEDIATYPE_DROPDOWN', 'Media:');
define('TITLETYPE_DROPDOWN', 'Tyyppi:');
define('PAYMETHOD_DROPDOWN', 'Maksutapa:');
define('STORE_DROPDOWN', 'Kauppa:');
?>