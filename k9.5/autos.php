<?php

$action = $_GET['action'];

switch ($action) {
    case 'getdata':
        echo alledaten();
        break;
    case 'getdatamitid':
        $id = $_GET['id'];
        echo 'getdatamitid: ' . $id;
        break;
    case 'deletedata':
        echo 'deletedata';
        break;
    case 'updatedata':
        
        break;
    default:
        
        break;
}

function alledaten(){

    $daten = file_get_contents('components/autos.json');
    $jsonDaten = json_decode($daten);
    return json_encode($jsonDaten);
}
