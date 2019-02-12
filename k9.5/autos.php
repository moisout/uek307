<?php

parse_str(file_get_contents("php://input"),$put_vars);
$_REQUEST = array_merge($_REQUEST, $put_vars);

define('MYSQL_HOST',"localhost"); 
define('MYSQL_USER',"root"); 
define('MYSQL_PW',""); 
define('MYSQL_DB',"mauriceoegerli");
// Verbindung zur DB herstellen
$con = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PW);
if(!$con->select_db(MYSQL_DB)){
    // DB existiert nicht, also neu erstellen
    $createdb = "CREATE DATABASE IF NOT EXISTS " . MYSQL_DB . " DEFAULT CHARACTER SET utf8";
    $con->query($createdb);

    $con->query("USE " . MYSQL_DB);

    $con->query("CREATE TABLE IF NOT EXISTS autos (
        id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        kraftstoff VARCHAR(255) NOT NULL,
        farbe VARCHAR(255) NOT NULL,
        bauart VARCHAR(255) NOT NULL,
        tank INTEGER NOT NULL DEFAULT 0
    )");
}
$con->select_db(MYSQL_DB) or die('Datenbankverbindung nicht möglich');

$action = $_REQUEST['action'];

switch ($action) {
    case 'getdata':
        echo alledaten();
        break;
    case 'deletedata':
        $errors = array();

        $id = validateNumber($_REQUEST['id']);

        if(!$id){
            $errors[] = 'id';
        }

        if(count($errors) == 0){
            echo deletecar($id);
            http_response_code(200);
        }else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'postdata':
        $errors = array();

        $name = validateString($_REQUEST['name']);
        $kraftstoff = validateString($_REQUEST['kraftstoff']);
        $bauart = validateString($_REQUEST['bauart']);
        $farbe = validateString($_REQUEST['farbe']);

        if(!$name){
            $errors[] = 'name';
        }
        if(!$kraftstoff){
            $errors[] = 'kraftstoff';
        }
        if(!$bauart){
            $errors[] = 'bauart';
        }
        if(!$farbe){
            $errors[] = 'farbe';
        }

        if(count($errors) == 0){
            echo newcar($name, $kraftstoff, $bauart, $farbe);
            http_response_code(200);
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'putdata':
        $errors = array();

        $name = validateString($_REQUEST['name']);
        $kraftstoff = validateString($_REQUEST['kraftstoff']);
        $bauart = validateString($_REQUEST['bauart']);
        $farbe = validateString($_REQUEST['farbe']);
        $id = validateNumber($_REQUEST['id']);

        if(!$name){
            $errors[] = 'name';
        }
        if(!$kraftstoff){
            $errors[] = 'kraftstoff';
        }
        if(!$bauart){
            $errors[] = 'bauart';
        }
        if(!$farbe){
            $errors[] = 'farbe';
        }
        if(!$id){
            $errors[] = 'id';
        }

        if(count($errors) == 0){
            echo editcar($id, $name, $kraftstoff, $bauart, $farbe);
            http_response_code(200);
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'tankfuellung':
        $tank = validateString($_REQUEST['tank']);
        $id = validateNumber($_REQUEST['id']);


        if(!$tank){
            $errors[] = 'tank';
        }
        if(!$id){
            $errors[] = 'id';
        }
        if(count($errors) == 0){
            echo tankfuellen($id, $tank);
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
        else{
            $date = $date_var;
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

function alledaten(){
    global $con;
    $result = $con->query( 'SELECT id, name, kraftstoff, farbe, bauart, tank FROM autos');
    $dataarray = array();
    $dataarray = $result->fetch_all(MYSQLI_ASSOC);

    return json_encode($dataarray);
}

function newcar($name, $kraftstoff, $bauart, $farbe){
    global $con;

    $query = 'INSERT INTO `autos` (`name`, `kraftstoff`, `farbe`, `bauart`) VALUES ("'.$name.'", "'.$kraftstoff.'", "'.$farbe.'", "'.$bauart.'")';
    
    $con->query($query);

    return alledaten();
}

function editcar($id, $name, $kraftstoff, $bauart, $farbe){
    global $con;

    $query = 'UPDATE `autos` SET `name` = "'.$name.'", `kraftstoff` = "' . $kraftstoff . '", `farbe` = "' . $farbe. '", `bauart` = "'.$bauart.'" WHERE `autos`.`id` = "'.$id.'"';

    $con->query($query);

    return alledaten();
}

function tankfuellen($id, $tank){
    global $con;

    $query = 'UPDATE `autos` SET `tank` = '.$tank.' WHERE `autos`.`id` = "'.$id.'"';

    $con->query($query);

    return alledaten();
}

function deletecar($id){
    global $con;
    $con->query('DELETE FROM `autos` WHERE `autos`.`id` = ' . $id);

    return alledaten();
}