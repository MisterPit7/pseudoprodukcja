<?
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $wpdb;

$charset_collate = $wpdb->get_charset_collate();



require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/* ==============================
   Tabela: Zmarli
============================== */
$table_name = $wpdb->prefix . 'zmarli';
$tmp_table_name = $wpdb->prefix . 'users';

$sql = "CREATE TABLE $table_name (
    ID int(11) NOT NULL AUTO_INCREMENT,
    Imie varchar(30) NOT NULL,
    Nazwisko varchar(100) NOT NULL,
    Profilowe MEDIUMBLOB,
    Data_urodzenia date NOT NULL,
    Data_smierci date NOT NULL,
    Opis varchar(200),
    Geolokalizacja varchar(200),
    Numer_grobu varchar(50),
    Is_payed boolean DEFAULT 0,
    ID_Klienta bigint(11) UNSIGNED NOT NULL,
    Data_wygasniecia date NOT NULL,
    Szerokosc_geograficzna varchar(20) DEFAULT '52.4396183',
    Wysokosc_geograficzna varchar(20) DEFAULT '16.8703879',
    PRIMARY KEY (ID),
) $charset_collate ENGINE=InnoDB;";

dbDelta($sql);


/* ==============================
   Tabela: Zdjecia
============================== */
$table_name = $wpdb->prefix . 'zdjecia';
$tmp_table_name = $wpdb->prefix . 'zmarli';

$sql = "CREATE TABLE $table_name (
    ID int(11) NOT NULL AUTO_INCREMENT,
    Zdjecie MEDIUMBLOB,
    ID_Zmarlego int(11),
    PRIMARY KEY (ID),
    CONSTRAINT FK_ID_Zmarlego FOREIGN KEY (ID_Zmarlego) REFERENCES $tmp_table_name(ID) ON DELETE CASCADE
) $charset_collate ENGINE=InnoDB;";

dbDelta($sql);


/* ==============================
   Tabela: Komentarze
============================== */
$table_name = $wpdb->prefix . 'komentarze';
$tmp_zmarli = $wpdb->prefix . 'zmarli';
$tmp_klienci = $wpdb->prefix . 'users';

$sql = "CREATE TABLE $table_name (
    ID int(11) NOT NULL AUTO_INCREMENT,
    ID_Klienta BIGINT(11) UNSIGNED NOT NULL,
    ID_Zmarlego int(11),
    Tekst text NOT NULL,
    Is_accepted boolean DEFAULT 0,
    PRIMARY KEY (ID),
    CONSTRAINT FK_KOM_KLIENT FOREIGN KEY (ID_Klienta) REFERENCES $tmp_klienci(ID) ON DELETE CASCADE,
    CONSTRAINT FK_KOM_ZMARLY FOREIGN KEY (ID_Zmarlego) REFERENCES $tmp_zmarli(ID) ON DELETE CASCADE
) $charset_collate ENGINE=InnoDB;";

dbDelta($sql);


