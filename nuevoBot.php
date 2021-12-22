<?php
include_once 'configuracion.php';

$URL = "https://api.telegram.org/bot$TOKENtg";

$input = file_get_contents("php://input");
$update = json_decode($input,true);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];

$fecha = date('Y-m-d H:i:s');
file_put_contents("registro_de_actualizaciones.log", $fecha.' - '.$input, FILE_APPEND);

switch($message) {
    case '/start':
        $response = 'bot iniciado';
        sendMessage($chatId, $response);
        break;
    case '/info':
        $response = 'Hola! Soy @bot';
        sendMessage($chatId, $response);
        break;
    default:
    $response = 'No te he entendido';
    sendMessage($chatId, $response);
    break;
}
function variable($message){
    include 'variables.php';
    switch ($message) {
        case "/tag":
            return $tag;
            break;
        case "/clan":
            return $name;
            break;
        case "/tipo":
            return $type;
            break;
        case "/descripcion":
            return $description;
            break;
        case "/badgeId":
            return $badgeId;
            break;
        case "/Puntos del clan":
            return $clanScore;
            break;
        case "/trofeos de guerra":
            return $clanWarTrophies;
            break;
        case "/localizacion":
            return $location;
            break;
        case "/trofeos requeridos":
            return $requiredTrophies;
            break;
        case "/donaciones":
            return $donationsPerWeek;
            break;
        case "/cofre del clan":
            return $clanChestStatus;
            break;
        case "/nivel del cofre del clan":
            return $clanChestlevel;
            break;
        case "/miembros":
            return $members;
            break;
        case "/lista de miembros":
            $miembro = array();
            foreach ($memberList as $member) {
            $miembro[] = $member["name"];
            //$miembro += $member["name"];
            };
            $listamiembros = implode("\r\n", $miembro); //\n
            return $listamiembros;
            break;
        case "/participantes en la guerra":
            $participantes = array();
            foreach ($cWarClan["participants"] as $participante) {
                $participantes[] = $participante["name"];
            }
                $listaParticipantes = implode("\r\n", $participantes);
            return $listaParticipantes;
            break;
        case "/Quien no ha jugado las guerras":
        case "/quien no ha jugado las guerras":	
        case "/quien no ha jugado la guerra":
        case "/Quien no ha jugado la guerra":
        case "/Quien no ha jugado las guerras?":
        case "/quien no ha jugado las guerras?":	
        case "/quien no ha jugado la guerra?":
        case "/Quien no ha jugado la guerra?":
            $listaParticipantes;
            $participantes = array();
            foreach ($cWarClan["participants"] as $participante ) {
                if ($participante["decksUsedToday"] < 4){
                    $participantes[] = $participante["name"].
                    " => ".strval($participante['decksUsedToday'].
                    " batallas jugadas.");
                    //$participantes[] = $participante["fame"]." ".$key." ".$participante;
                }
                //$participantes[] = $participante["name"]." ".strval($participante["decksUsedToday"]);
            }
                $listaParticipantes = implode("\r\n", $participantes);
            return $listaParticipantes;//count($participantes);//
            //return $cWarClan["participants"]["decksUsedToday"];
            break;
        default:
            return "No te he entendido";
    }
}

function sendMessage($chatId, $response) {
    $url = $GLOBALS['URL'].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response);
    file_get_contents($url);
}

if (strpos($message, "/") === 0){
    $variable = variable($message);
    sendMessage($chatId, $variable);
    
}