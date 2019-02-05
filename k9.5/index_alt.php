<?php

echo 'test';
echo '<br>';

echo date("d.m.y");

echo '<br>';
$vorname = 'Test';
$nachname = 'Name';

echo $vorname . ' ' . $nachname;

$stunde = date("H");


if($stunde < 10){
    echo "Guten Morgen";
}elseif($stunde > 18){
    echo "Guten Abend";
}else{
    echo "Gute Nacht";
}
echo '<br>';

for ($i=0; $i < 10; $i++) { 
    echo $i;
}
$action = 'getdata';

switch ($action) {
    case 'getdata':
        echo 'getdata';
        echo '<br>';
        echo alledaten();
        break;
    case 'getdatawithid':
        
        break;
    case 'deletedata':

        break;
    case 'updatedata':
        
        break;
    default:
        
        break;
}

function alledaten(){
    $daten = array();
    $daten[0]['id'] = 1;
    $daten[0]['feld1'] = 'Hans';
    $daten[0]['feld2'] = 'Muster';
    $daten[1]['id'] = 2;
    $daten[1]['feld1'] = 'Urs';
    $daten[1]['feld2'] = 'Keller';
    return json_encode($daten);
}