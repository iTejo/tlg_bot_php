<?php
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set("Europe/Madrid");
$ahora = date('00:00:00');
//$fecha = date('Y-m-d H:i:s');
// pila de info en https://t.me/TgBotDevs/192988
include_once 'configuracion.php';

$URL = "https://api.telegram.org/bot$TOKENtg";

$request = file_get_contents("php://input");
//$request = json_decode(file_get_contents("php://input"), TRUE);
// esto es para crear un log
file_put_contents("registro_de_actualizaciones.log", $fecha.' - '.$request, FILE_APPEND);
$request = json_decode($request);

/*
Esta es la funcion para enviar contenido
*/
function http_post($url, $json)
{
    //https://atareao.es/tutorial/crea-tu-propio-bot-para-telegram/como-integrar-telegram-con-wordpress/
    $ans = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    try
    {
        $data_string = json_encode($json);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $ans = json_decode(curl_exec($ch));
        if($ans->ok !== TRUE)
        {
            $ans = null;
        }
    }
    catch(Exception $e)
    {
        echo "Error: ", $e->getMessage(), "\n";
    }
    curl_close($ch);
    return $ans;
}

function variable($var){
    include 'variables.php';
    switch ($var) {
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
        }
}
/*
esta es la funcion que envia texto al bot
*/
function sendMessage($chat_id, $text)
{
    global $URL;
    $json = ['chat_id'       => $chat_id,
             'text'          => $text,
             'parse_mode'    => 'HTML'];
    return http_post($URL.'/sendMessage', $json);
}
//$text = urlencode($text);
function devolver($chat_id, $text) {
    global $URL;
    $json = ['chat_id'       => $chat_id,
             'text'          => $text,
             'parse_mode'    => 'HTML'];
    return http_post($URL.'/sendMessage', $json);
}

function logDiarioGuerras() {
    $frase = "/quien no ha jugado la guerra?";
    variable($frase);
}

if (strpos($request->message->text, "/") === 0){
    $variable = variable($request->message->text);
    devolver($request->message->chat->id, $variable);
    
}
/*
repite lo que dices
*/
//sendMessage($request->message->chat->id, $request->message->text);
//$descripcion=guerra();

// crear un cron con php para ejecutar el switch a las 10 con "/quien no ha jugado la guerra"
// https://qastack.mx/programming/18737407/how-to-create-cron-job-using-php
//https://www.npmjs.com/package/node-cron
//devolver($request->message->chat->id, $descripcion);

/*
mensaje de bienvenida
*/
/*
$message = "Hola $first_name, bienvenido al canal del clan Cofre Coronas".
sendMessage($request->message->chat->id, $message);
*/

/**
 * $request devolveria mas o menos esto:
 * {
*    {
*    "update_id":5432102,
*    "message":{
*         "message_id":124,
*         "from":{
*             "id":123345698,
*             "is_bot":false,
*             "first_name":"pepe",
*             "username":"gotera",
*             "language_code":"es"
*         },
*         "chat":{
*             "id":-987654321,
*             "id":-365821568,
*             "title":"Bot Prueba",
*             "type":"group",
*             "all_members_are_administrators":false
*         },
*         "date":1551766912,
*         "text":"texto de prueba"
*    }
* }
 */