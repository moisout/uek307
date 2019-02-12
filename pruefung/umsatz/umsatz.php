<?php

parse_str(file_get_contents("php://input"),$put_vars);
$_REQUEST = array_merge($_REQUEST, $put_vars);

define('MYSQL_HOST',"localhost"); 
define('MYSQL_USER',"root"); 
define('MYSQL_PW',""); 
define('MYSQL_DB',"m307_mauriceoegerli");
// Verbindung zur DB herstellen
$con = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW);
if(!$con->select_db(MYSQL_DB)){
    // DB existiert nicht, also neu erstellen
    $createdb = "CREATE DATABASE IF NOT EXISTS " . MYSQL_DB . " DEFAULT CHARACTER SET utf8";
    $con->query($createdb);

    $con->query("USE " . MYSQL_DB);

    $con->query("CREATE TABLE IF NOT EXISTS mauriceoegerli_umsatz (
        umsatz_id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        umsatz_kunde_name VARCHAR(255) NOT NULL,
        umsatz_filiale VARCHAR(255) NOT NULL DEFAULT 'St. Gallen',
        umsatz_umsatz DOUBLE NOT NULL,
        umsatz_kunde_seit DATE,
        umsatz_anzbestellungen INTEGER
    )");

    $con->query('INSERT INTO `mauriceoegerli_umsatz` (`umsatz_kunde_name`, `umsatz_filiale`, `umsatz_umsatz`, `umsatz_kunde_seit`, `umsatz_anzbestellungen`) 
    VALUES ("Max Hauser", "St. Gallen", 58900, "2002-01-01", 59)');

    $con->query('INSERT INTO `mauriceoegerli_umsatz` (`umsatz_kunde_name`, `umsatz_filiale`, `umsatz_umsatz`, `umsatz_kunde_seit`, `umsatz_anzbestellungen`) 
    VALUES ("Linda Daxer", "Bern", 25566.5, "2006-01-01", 40)');

    $con->query('INSERT INTO `mauriceoegerli_umsatz` (`umsatz_kunde_name`, `umsatz_filiale`, `umsatz_umsatz`, `umsatz_kunde_seit`, `umsatz_anzbestellungen`) 
    VALUES ("Mario Power", "Basel", 18080.5, "2009-01-01", 22)');
}

$con->select_db(MYSQL_DB) or die('Datenbankverbindung nicht möglich');

$action = $_REQUEST['action'];

switch ($action) {
    case 'getdata':
        echo alledaten();
        break;
    case 'deletedata':
        $errors = array();

        $umsatz_id = validateNumber($_REQUEST['umsatz_id']);

        if(!$umsatz_id){
            $errors[] = 'umsatz_id';
        }

        if(count($errors) == 0){
            echo deleteEntry($umsatz_id);
            http_response_code(200);
        }else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'postdata':
        $errors = array();

        $umsatz_kunde_name = validateString($_REQUEST['umsatz_kunde_name']);
        $umsatz_filiale = validateString($_REQUEST['umsatz_filiale']);
        $umsatz_umsatz = validateNumber($_REQUEST['umsatz_umsatz']);
        $umsatz_kunde_seit = validateDate($_REQUEST['umsatz_kunde_seit']);
        $umsatz_anzbestellungen = validateNumber($_REQUEST['umsatz_anzbestellungen']);

        if(!$umsatz_kunde_name){
            $errors[] = 'umsatz_kunde_name';
        }
        if(!$umsatz_filiale){
            $errors[] = 'umsatz_filiale';
        }
        if(!$umsatz_umsatz){
            $errors[] = 'umsatz_umsatz';
        }
        if(!$umsatz_kunde_seit){
            $umsatz_kunde_seit = null;
        }
        if(!$umsatz_anzbestellungen){
            $umsatz_anzbestellungen = null;
        }

        if(count($errors) == 0){
            echo newEntry($umsatz_kunde_name, $umsatz_filiale, $umsatz_umsatz, $umsatz_kunde_seit, $umsatz_anzbestellungen);
            http_response_code(200);
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'putdata':
        $errors = array();

        $umsatz_kunde_name = validateString($_REQUEST['umsatz_kunde_name']);
        $umsatz_filiale = validateString($_REQUEST['umsatz_filiale']);
        $umsatz_umsatz = validateNumber($_REQUEST['umsatz_umsatz']);
        $umsatz_kunde_seit = validateDate($_REQUEST['umsatz_kunde_seit']);
        $umsatz_anzbestellungen = validateNumber($_REQUEST['umsatz_anzbestellungen']);
        $umsatz_id = validateNumber($_REQUEST['umsatz_id']);

        if(!$umsatz_kunde_name){
            $errors[] = 'umsatz_kunde_name';
        }
        if(!$umsatz_filiale){
            $errors[] = 'umsatz_filiale';
        }
        if(!$umsatz_umsatz){
            $errors[] = 'umsatz_umsatz';
        }
        if(!$umsatz_kunde_seit){
            $umsatz_kunde_seit = null;
        }
        if(!$umsatz_anzbestellungen){
            $umsatz_anzbestellungen = null;
        }
        if(!$umsatz_id){
            $errors[] = 'umsatz_id';
        }

        if(count($errors) == 0){
            echo editEntry($umsatz_id, $umsatz_kunde_name, $umsatz_filiale, $umsatz_umsatz, $umsatz_kunde_seit, $umsatz_anzbestellungen);
            http_response_code(200);
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'getdatabyid':
        $errors = array();

        $umsatz_id = validateNumber($_REQUEST['umsatz_id']);

        if(!$umsatz_id){
            $errors[] = 'umsatz_id';
        }

        if(count($errors) == 0){
            echo getEntryById($umsatz_id);
            http_response_code(200);
        }else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    default: 
        break;
}

$con->close();


function alledaten(){
    global $con;
    $result = $con->query( 'SELECT * FROM mauriceoegerli_umsatz');
    $dataarray = array();
    $dataarray = $result->fetch_all(MYSQLI_ASSOC);

    return json_encode($dataarray);
}

function getEntryById($umsatz_id){
    global $con;
    $result = $con->query( 'SELECT * FROM mauriceoegerli_umsatz WHERE `umsatz_id` = "'.$umsatz_id .'"');
    $dataarray = array();
    $dataarray = $result->fetch_all(MYSQLI_ASSOC);

    return json_encode($dataarray);
}


function newEntry($umsatz_kunde_name, $umsatz_filiale, $umsatz_umsatz, $umsatz_kunde_seit, $umsatz_anzbestellungen){
    global $con;

    $query = 'INSERT INTO `mauriceoegerli_umsatz` (`umsatz_kunde_name`, `umsatz_filiale`, `umsatz_umsatz`, `umsatz_kunde_seit`, `umsatz_anzbestellungen`) 
    VALUES ("'.$umsatz_kunde_name.'", "'.$umsatz_filiale.'", "'.$umsatz_umsatz.'", "'.$umsatz_kunde_seit.'", "'.$umsatz_anzbestellungen.'")';
    
    $con->query($query);

    return alledaten();
}

function editEntry($umsatz_id, $umsatz_kunde_name, $umsatz_filiale, $umsatz_umsatz, $umsatz_kunde_seit, $umsatz_anzbestellungen){
    global $con;

    $query = 'UPDATE `mauriceoegerli_umsatz` SET `umsatz_kunde_name` = "'.$umsatz_kunde_name.'", `umsatz_filiale` = "' . $umsatz_filiale . '", `umsatz_umsatz` = "' . $umsatz_umsatz. '", `umsatz_kunde_seit` = "'.$umsatz_kunde_seit.'", `umsatz_anzbestellungen` = "'.$umsatz_anzbestellungen.'" WHERE `mauriceoegerli_umsatz`.`umsatz_id` = "'.$umsatz_id.'"';

    $con->query($query);

    return alledaten();
}

function deleteEntry($id){
    global $con;
    $con->query('DELETE FROM `mauriceoegerli_umsatz` WHERE `mauriceoegerli_umsatz`.`umsatz_id` = ' . $id);

    return alledaten();
}

function validateMail($mail){
    $check = true;
    if(isset($mail)){
        $mail = htmlspecialchars($mail);

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            $check = false;
        }

        if(!filter_var($mail, '/[,]/')){
            $check = false;
        }

        if(strlen($mail)>255){
            $check = false;
        }
    }
    else{
        $check = false;
    }

    if($check === true){
        return $mail;
    }
    else{
        return $check;
    }
}

function validateString($string){
    $check = true;
    if(isset($string)){
        $string = htmlspecialchars($string);

        if(strlen($string)>255){
            $check = false;
        }
    }
    else{
        $check = false;
    }

    if($check == true){
        return $string;
    }
    else{
        return $check;
    }
}

function validateNumber($number){
    $check = true;
    if(isset($number)){
        $number = htmlspecialchars($number);

        if(!is_numeric($number)){
            $check = false;
        }

        if(strlen($number)>255){
            $check = false;
        }
    }
    else{
        $check = false;
    }

    if($check == true){
        return $number;
    }
    else{
        return $check;
    }
}

function validateDate($date){
    $check = true;
    if(isset($date)){
        $date = htmlspecialchars($date);

        $date_var = DateTime::createFromFormat('Y-m-d', $date);
        $date_errors = DateTime::getLastErrors();
        if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
            $check = false;
        }
    }
    else{
        $check = false;
    }

    if($check == true){
        return $date;
    }
    else{
        return $check;
    }
}

function validateBoolean($boolean){
    $check = true;
    if(isset($boolean)){
        $boolean = htmlspecialchars($mail);

        if(!filter_var($boolean, FILTER_VALIDATE_BOOLEAN)){
            $check = false;
        }
    }
    else{
        $check = false;
    }

    if($check == true){
        return $boolean;
    }
    else{
        return $check;
    }
}
