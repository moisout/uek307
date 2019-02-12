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

    
}
$con->query("USE " . MYSQL_DB);

$con->query("CREATE TABLE IF NOT EXISTS notes (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content VARCHAR(512) NOT NULL,
    color VARCHAR(7) NOT NULL
)");
$con->select_db(MYSQL_DB) or die('Datenbankverbindung nicht möglich');


$action = $_REQUEST['action'];

switch ($action) {
    case 'getdata':
    
        echo alleDaten();
        break;
    case 'deletedata':
        $errors = array();

        $id = validateNumber($_REQUEST['id']);

        if(!$id){
            $errors[] = 'id';
        }

        if(count($errors) == 0){
            echo deleteNote($id);
            http_response_code(200);
        }else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'postdata':
        $errors = array();

        $title = validateString($_REQUEST['title']);
        $content = validateString($_REQUEST['content']);
        $color = validateString($_REQUEST['color']);

        if(!$title){
            $errors[] = 'title';
        }
        if(!$content){
            $errors[] = 'content';
        }
        if(!$color){
            $errors[] = 'color';
        }

        if(count($errors) == 0){
            echo newNote($title, $content, $color);
            http_response_code(200);
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    case 'putdata':
        $errors = array();

        $id = validateString($_REQUEST['id']);
        $title = validateString($_REQUEST['title']);
        $content = validateString($_REQUEST['content']);
        $color = validateString($_REQUEST['color']);

        if(!$id){
            $errors[] = 'id';
        }
        if(!$title){
            $errors[] = 'title';
        }
        if(!$content){
            $errors[] = 'content';
        }
        if(!$color){
            $errors[] = 'color';
        }

        if(count($errors) == 0){
            echo editNote($id, $title, $content, $color);
            http_response_code(200);
        }
        else{
            echo json_encode($errors);
            http_response_code(500);
        }
        break;
    default: 
        break;
}

$con->close();

function alleDaten(){
    global $con;
    $result = $con->query( 'SELECT id, title, content, color FROM notes');
    $dataarray = array();
    $dataarray = $result->fetch_all(MYSQLI_ASSOC);

    return json_encode($dataarray);
}

function newNote($title, $content, $color){
    global $con;

    $query = 'INSERT INTO `notes` (`title`, `content`, `color`) VALUES ("'.$title.'", "'.$content.'", "'.$color.'")';
    
    $con->query($query);

    return alledaten();
}

function editNote($id, $title, $content, $color){
    global $con;

    $query = 'UPDATE `notes` SET `title` = "'.$title.'", `content` = "' . $content . '", `color` = "'.$color.'" WHERE `notes`.`id` = "'.$id.'"';

    $con->query($query);

    return alledaten();
}

function deleteNote($id){
    global $con;
    $con->query('DELETE FROM `notes` WHERE `notes`.`id` = ' . $id);

    return alledaten();
}


function validateMail($mail){
    $check = true;
    if(isset($mail)){
        $mail = htmlspecialchars($mail);

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
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
