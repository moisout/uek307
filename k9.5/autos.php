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

    $con->query("INSERT INTO autos (name, kraftstoff, farbe, bauart) 
    VALUES ('Passat', 'Diesel', '#000000', 'Limousine');");
    $con->query("INSERT INTO autos (name, kraftstoff, farbe, bauart) 
    VALUES ('asdfg', 'Benzin', '#59e331', '4x4');");
    $con->query("INSERT INTO autos (name, kraftstoff, farbe, bauart) 
    VALUES ('testauto4', 'Benzin', '#d86612', 'SUV');");
}
$con->select_db(MYSQL_DB) or die('Datenbankverbindung nicht möglich');


$action = $_REQUEST['action'];

switch ($action) {
    case 'getdata':
        echo alledaten();
        break;
    case 'errordata':
        header("HTTP/1.1 401 Unauthorized ");
        break;
    case 'deletedata':
        $id = $_REQUEST['id'];
        echo deletecar($id);
        break;
    case 'putdata':
        $name = $_REQUEST['name'];
        $kraftstoff = $_REQUEST['kraftstoff'];
        $bauart = $_REQUEST['bauart'];
        $farbe = $_REQUEST['farbe'];

        echo newcar($name, $kraftstoff, $bauart, $farbe);
        break;
    case 'postdata':
        $name = $_REQUEST['name'];
        $kraftstoff = $_REQUEST['kraftstoff'];
        $bauart = $_REQUEST['bauart'];
        $farbe = $_REQUEST['farbe'];
        $id = $_REQUEST['id'];

        echo editcar($id, $name, $kraftstoff, $bauart, $farbe);
        break;
    default:
        
        break;
}

$con->close();

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

function deletecar($id){
    global $con;
    $con->query('DELETE FROM `autos` WHERE `autos`.`id` = ' . $id);

    return alledaten();
}