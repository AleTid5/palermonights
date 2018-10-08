#!/usr/bin/php
<?php
include 'loademDBAL.php';
require 'DBLI.php';



$DB = new DatabaseLi("p:127.0.0.1", "root", "pD8gYKB2", "emailmarketing", $pre = '');
$DB->connect();

// $noBody =$em->getRepository('CdiUser\Entity\User')->findOneBy(array("id" => 1));
//leerArchivo("USED_fb_info_Jazmin_Jurevich_(Jaz.Jurevich)");
//Funcion a realizar por cada Archivo
$dir = "/var/www/web-palermonights/App/public/list";
$dr = opendir($dir);
$dirCount = 0;

var_dump($dr);

// recorremos todos los elementos de la carpeta
while (($archivo = readdir($dr)) !== false) {
// comprobamos que sean archivos y no otras carpetas
    if (!is_dir($archivo)) {
        leerArchivo($archivo, $DB);
    }
    $dirCount++;
    echo "###\n";
    echo "DirCount:" . $dirCount . "\n";
    echo "###\n\n";
}

closedir($dr);
$DB->close();

function leerArchivo($archivo, $DB) {
    $now = new \Datetime("now");

    $indices = array(
        0 => "id",
        1 => "name",
        2 => "lastname",
        3 => "fullname",
        4 => "facebook_id",
        5 => "facebook_url",
        6 => "facebook_user",
        7 => "facebook_email",
        8 => "facebook_country",
        9 => "facebook_province",
        10 => "facebook_city",
        11 => "facebook_neighborhood",
        12 => "facebook_location_name",
        13 => "facebook_location_id",
        14 => "facebook_hometown_name",
        15 => "facebook_hometown_id",
        16 => "birthdate",
        17 => "birthday_num",
        18 => "birthday_text",
        19 => "age",
        20 => "email"
    );

    if (($gestor = fopen("/var/www/web-palermonights/App/public/list/" . $archivo, "r")) !== FALSE) {
        $fila = 0;
        while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {
            if ($fila > 0) {


                foreach ($datos as $index => $val) {

                    $utf8 = mb_detect_encoding($val, 'UTF-8', true);
                    if ($utf8) {
                        $contact[$indices[$index]] = $val;
                    } else {
                        $contact[$indices[$index]] = utf8_encode($val);
                    }
                }

                if ($fila == 1) {
                    $friendName = $contact['fullname'];
                    $friendUserName = $contact['facebook_user'];
                }

                //var_dump($contact);
                $contact['facebook_friend_fullname'] = $friendName;
                $contact['facebook_friend_username'] = $friendUserName;
                $contact['created_at'] = $now;
                $contact['origin'] = "2015";
                unset($contact['id']);
                $DB->query_insert_ignore("contacts_new", $contact);
                $DB->query_insert_ignore("contacts", $contact);
                
                  if ($fila == 1) {
                      //  $DB->query_insert_ignore("contacts_login", $contact);
                  }
            }
            $fila++;
        }
        fclose($gestor);
    }
}

?>
