<?php
include_once '../datos/BaseDatos.php';
include_once '../datos/Empresa.php';
include_once '../datos/Viaje.php';
include_once '../datos/ResponsableV.php';
include_once '../datos/Pasajero.php';

// Funcion visual de menu
function menu()
{
    echo
    "\n+================================+\n" .
        "|         MENU PRINCIPAL         |\n" .
        "¦================================¦\n" .
        "| 1. Agregar una empresa         |\n" .
        "| 2. Modificar una empresa       |\n" .
        "| 3. Eliminar una empresa        |\n" .
        "| 4. Ver empresas                |\n" .
        "¦================================¦\n" .
        "| 5. Agregar un viaje            |\n" .
        "| 6. Modificar un viaje          |\n" .
        "| 7. Eliminar un viaje           |\n" .
        "| 8. Ver viaje                   |\n" .
        "¦================================¦\n" .
        "| 9. Salir                       |\n" .
        "+================================+\n\n";
}

// Functiones Empresa
function ingresarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("(Opcional) Id de la empresa: ");
    $nombre = readline("Nombre de la empresa: ");
    $dir = readline("Direccion de la empresa: ");

    if (!$empresa->Buscar($id)) {
        $empresa->Cargar($id, $nombre, $dir);

        $respuesta = $empresa->Insertar();
        if ($respuesta == true) {
            echo "\n\t   La empresa fue ingresada en la BD.\n" .
                "\t========================================\n";
        } else {
            echo $empresa->getMensajeOp();
        }
    } else {
        echo "\nYa existe una empresa con ese ID.\n";
    }
}

function modificarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("Ingrese el id de la empresa a modificar: ");
    $respuesta = $empresa->Buscar($id);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $nuevoid = readline("(Opcional) Id de la empresa: ");
        $nombre = readline("Nombre de la empresa: ");
        $dir = readline("Direccion de la empresa: ");
        if ($nuevoid != "") {
            $empresa->Cargar($nuevoid, $nombre, $dir);
        } else {
            $empresa->setNombre($nombre);
            $empresa->setEdireccion($dir);
        }

        $respuesta = $empresa->Modificar();
        if ($respuesta) {
            echo "\n\t   La empresa fue modificada correctamente.\n" .
                "\t==============================================\n";
        } else {
            echo "\nNo se pudo modificar la empresa.\n";
        }
    } else {
        echo "No se pudo encontrar la empresa con id = " . $id . "\n";
    }
}

function eliminarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("Ingrese el id de la empresa a eliminar: ");
    $respuesta = $empresa->Buscar($id);

    if ($respuesta) {
        $respuesta = $empresa->Eliminar();
        if ($respuesta) {
            echo "\n\t   La empresa fue eliminada de la BD.\n" .
                "\t=========================================\n";
        } else {
            echo "\nNo se pudo eliminar la empresa.\n";
        }
    } else {
        echo "No se pudo encontrar la empresa con id = " . $id . ".\n";
    }
}

function mostrarEmpresa()
{
    $empresa = new Empresa();

    $resp = readline("Mostrar todas las empresas? (s/n) ");

    if ($resp == 's') {
        $colEmpresas = $empresa->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colEmpresas as $empresa) {

            echo $empresa;
            echo "-------------------------------------------------------";
        }
    } else {
        $id = readline("Ingrese el id de la empresa: ");
        if (is_int($id)) {
            $respuesta = $empresa->Buscar($id);
            if ($respuesta) {
                echo $empresa;
            } else {
                echo "No se pudo encontrar la empresa.";
            }
        } else {
            echo "ID ingresado no es valido.\n";
        }
    }
}

//Funciones Viaje
function ingresarViaje()
{
    $viaje = new Viaje();

    $id = readline("(Opcional) Id del viaje: ");
    $destino = readline("Destino del viaje: ");
    $cantmax = readline("Cantidad maxima de pasajeros: ");
    $idempresa = readline("ID de la empresa a cargo: ");
    $nempleado = readline("Numero de empleado responsable: ");
    $importe = readline("Importe: ");
    $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
    $idayvuelta = readline("Ida y vuelta? ");

    if (!$viaje->Buscar($id)) {
        $viaje->Cargar($id, $destino, $cantmax, $idempresa, $nempleado, $importe, $tipoAsiento, $idayvuelta);

        $respuesta = $viaje->Insertar();
        if ($respuesta == true) {
            echo "\n\t   El viaje fue ingresada en la BD.\n" .
                "\t======================================\n";
        } else {
            echo $viaje->getMensajeOp();
        }
    } else {
        echo "\nYa existe un viaje con ese ID.\n";
    }
}

function modificarViaje()
{
    $viaje = new Viaje();

    $id = readline("Ingrese el id del viaje a modificar: ");
    $respuesta = $viaje->Buscar($id);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $nuevoid = readline("(Opcional) Id del viaje: ");
        $destino = readline("Destino del viaje: ");
        $cantmax = readline("Cantidad maxima de pasajeros: ");
        $idempresa = readline("ID de la empresa a cargo: ");
        $nempleado = readline("Numero de empleado responsable: ");
        $importe = readline("Importe: ");
        $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
        $idayvuelta = readline("Ida y vuelta? ");
        if ($nuevoid != "") {
            $viaje->Cargar($nuevoid, $destino, $cantmax, $idempresa, $nempleado, $importe, $tipoAsiento, $idayvuelta);
        } else {
            $viaje->setDestino($destino);
            $viaje->setCantMaxPasajeros($cantmax);
            $viaje->setIdEmpresa($idempresa);
            $viaje->setImporte($nempleado);
            $viaje->setTipoAsiento($importe);
            $viaje->setIdaYVuelta($tipoAsiento);
        }

        $respuesta = $viaje->Modificar();
        if ($respuesta) {
            echo "\n\t   El viaje fue modificado correctamente.\n" .
                "\t============================================\n";
        } else {
            echo "\nNo se pudo eliminar la empresa.\n";
        }
    } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
    }
}

function eliminarViaje()
{
    $viaje = new Viaje();

    $id = readline("Ingrese el id del viaje a eliminar: ");
    $respuesta = $viaje->Buscar($id);

    if ($respuesta) {
        $respuesta = $viaje->Eliminar();
        if ($respuesta) {
            echo "\n\t   El viaje fue eliminado de la BD.\n" .
                "\t=========================================\n";
        } else {
            echo "\nNo se pudo eliminar el viaje.\n";
        }
    } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
    }
}

function mostrarViaje()
{
    $viaje = new Viaje();

    $resp = readline("Mostrar todos los viajes? (s/n) ");

    if ($resp == 's') {
        $colViajes = $viaje->Listar("");
        foreach ($colViajes as $viaje) {

            echo $viaje;
            echo "\n-------------------------------------------------------\n";
        }
    } else {
        $id = readline("Ingrese el id del viaje: ");
        if (is_int($id)) {
            $respuesta = $viaje->Buscar($id);
            if ($respuesta) {
                echo $viaje;
            } else {
                echo "No se pudo encontrar el viaje.";
            }
        } else {
            echo "ID ingresado no es valido.\n";
        }
    }
}

// Switch de opciones para guiar al usuario con cada funcion.
function opciones()
{
    menu();
    $resp = false;

    $opcion = readline("Elija una opcion: ");
    switch ($opcion) {
        case 1:
            ingresarEmpresa();
            break;
        case 2:
            modificarEmpresa();
            break;
        case 3:
            eliminarEmpresa();
            break;
        case 4:
            mostrarEmpresa();
            break;
        case 5:
            ingresarViaje();
            break;
        case 6:
            modificarViaje();
            break;
        case 7:
            eliminarViaje();
            break;
        case 8:
            mostrarViaje();
            break;
        case 9:
            $resp = false;
            break;
        default:
            echo "Opcion incorrecta. Tiene que ser un numero entre 1 y 9.";
            time_nanosleep(2, 0);
            $resp = true;
    }

    echo "\n";
    if ($opcion != 9 && ($opcion > 0 && $opcion < 9)) {
        $resp = 's' == readline("Realizar otra operacion? (s/n) ");
    }

    return $resp;
}

// While loop para seleccionar otra opcion si el usuario lo desea.
$resp = true;
while ($resp) {
    $resp = opciones();
}

echo "===========================" .
    "\n\tSaliendo...\n" .
    "===========================";
