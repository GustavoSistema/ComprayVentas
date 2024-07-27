<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotmanController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function ($botman, $message) {
            if ($message == '1') {
                $this->startConversation($botman);
            } else {
                $botman->reply("Lo siento, no entiendo ese comando. Puedes intentar escribir '1' para ver lista de acciones.");
            }
        });

        $botman->listen();
    }

    public function startConversation($botman)
    {
        $botman->startConversation(new CertificationConversation());
    }
}

class CertificationConversation extends Conversation
{
    public function run()
    {
        $this->askServiceType();
    }

    public function askServiceType()
    {
        $this->ask("¿Qué acción te gustaría realizar? <br>
            1. Nuevo Servicio <br> 
            2. Ver Listado de Certificaciones <br>
            3. Certificaciones Pendientes <br>
            4. Expedientes", function (Answer $answer) {
            $action = strtolower($answer->getText());
            if ($action == '1') {
                $this->newServiceSteps();
            } elseif ($action == '2') {
                $this->listCertificationsSteps();
            } elseif ($action == '3') {
                $this->pendingCertificationsSteps();
            } elseif ($action == '4') {
                $this->expedientesSteps();
            } else {
                $this->say("Lo siento, no entiendo esa acción. Por favor, intenta nuevamente.");
                $this->askServiceType();
            }
        });
    }

    public function newServiceSteps()
    {
        $this->say("Para realizar un Nuevo Servicio:");
        $this->say("Paso 1. Dirígete al menú y selecciona Servicios - Nuevo Servicio. <br><br>
        Paso 2. Selecciona tu taller, si tu taller no se encuentra registrado comunícate con oficina. <br><br>
        Paso 3. Selecciona el tipo de servicio a realizar. <br><br>
        Paso 4. Completa el número de formato en el que vas a certificar. <br><br>
        Paso 5. Completa los datos y equipos del vehículo. <br><br>
        Paso 6. Selecciona o arrastra las fotos reglamentarias. <br><br>
        Paso 7. Selecciona la fecha y externo si es necesario. <br><br>
        Paso 8. Completa el proceso con el botón certificar. ¡ Hemos Terminado !");
    }

    public function listCertificationsSteps()
    {
        $this->say("Para ver el listado de Certificaciones:");
        $this->say("Paso 1. Dirígete al menú y selecciona Servicios - Lista Certificaciones <br><br>
        Paso 2. Muestra lista de todas las certificaciones ¡ Hemos Terminado !
        ");
    }

    public function pendingCertificationsSteps()
    {
        $this->say("Para ver las Certificaciones Pendientes:");
        $this->say("Paso 1. Dirígete al menú y selecciona Servicios - Certificaciones Pendientes <br><br>
        Paso 2. Muestra lista de todas las certificaciones Pendientes. <br><br>
        Opcional 1. Si deseas completar alguna certificación pendiente dirígete a la columna acciones y sobre la fila de la certificación que quieres completar el proceso dar clic en los 3 puntos y seleccionar certificar. <br><br>
        Opcional 2. Completa los datos (Combustible, Nuevo Peso Neto y N° Formato). <br><br>
        Opcional 3. Completa el proceso con el botón certificar ¡ Hemos Terminado !");
    }

    public function expedientesSteps()
    {
        $this->say("Para ver los Expedientes:");
        $this->say("Paso 1. Dirígete al menú y selecciona Expedientes - Listado Expedientes <br><br>
        Paso 2. Muestra lista de todos los expedientes. ¡ Hemos Terminado !");
    }
}
