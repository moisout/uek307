<?php


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

$action = $_GET['action'];

switch ($action) {
    case 'getdata':
        echo alledaten();
        break;
    case 'getdatamitid':
        $id = $_GET['id'];
        echo 'getdatamitid: ' . $id;
        break;
    case 'errordata':
        header("HTTP/1.1 401 Unauthorized ");
        break;
    case 'deletedata':
        $id = $_GET['id'];
        echo deletecar($id);
        break;
    case 'putdata':
        $name = $_GET['name'];
        $kraftstoff = $_GET['kraftstoff'];
        $bauart = $_GET['bauart'];
        $farbe = $_GET['farbe'];

        echo newcar($name, $kraftstoff, $bauart, $farbe);
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

function deletecar($id){
    global $con;
    $con->query('DELETE FROM `autos` WHERE `autos`.`id` = ' . $id);

    return alledaten();
}