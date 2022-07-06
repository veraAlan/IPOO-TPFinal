<?php
include_once '../datos/BaseDatos.php';
include_once '../datos/Empresa.php';
include_once '../datos/Viaje.php';
include_once '../datos/ResponsableV.php';
include_once '../datos/Pasajero.php';

// Funcion visual de menu
function menu()
{
    echo "\n" .
        "+================================+\n" .
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
        "¦================================¦\n" .
        "| 9. Ingresar responsable        |\n" .
        "| 10. Modificar responsable      |\n" .
        "| 11. Eliminar un responsable    |\n" .
        "| 12. Ver responsables           |\n" .
        "¦================================¦\n" .
        "| 13. Ingresar pasajero          |\n" .
        "| 14. Modificar pasajero         |\n" .
        "| 15. Eliminar un pasajero       |\n" .
        "| 16. Ver pasajeros              |\n" .
        "¦================================¦\n" .
        "| 0. Salir                       |\n" .
        "+================================+\n\n";
}

// Functiones Empresa
function ingresarEmpresa()
{
    $empresa = new Empresa();

    $id = readline("(Opcional) Id de la empresa: ");
    $nombre = readline("Nombre de la empresa: ");
    $dir = readline("Direccion de la empresa: ");

    // Finds next id that is free for a new entry.
    // Does auto increment even if id of higher entry is >2 than last entry of that one.
    if ($id == null) {
        $id = 1;
        while ($empresa->Buscar($id)) {
            $id++;
        }
    }

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
        $nombre = readline("Nombre de la empresa: ");
        $dir = readline("Direccion de la empresa: ");

        $empresa->setEnombre($nombre);
        $empresa->setEdireccion($dir);
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
    $viaje = new Viaje();

    $id = readline("Ingrese el id de la empresa a eliminar: ");
    $respuesta = $empresa->Buscar($id);

    if ($respuesta) {
        if ($viaje->Buscar(null, "idempresa = " . $id)) {
            $eliminarEmpresa = readline("La empresa esta a cargo de un viaje. Quiere eliminar el viaje junto a la empresa? (s/n) ");
            if ($eliminarEmpresa == "s") {
                $viaje->Eliminar();
                $empresaEncontrada = false;
            } else {
                $empresaEncontrada = true;
            }
        } else {
            $empresaEncontrada = false;
        }

        if (!$empresaEncontrada) {
            $respuesta = $empresa->Eliminar();
            if ($respuesta) {
                echo "\n\t   La empresa fue eliminada de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar la empresa.\n";
            }
        } else {
            echo "\nNo se puede eliminar un responsable a cargo de un viaje sin eliminar el viaje.\n";
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
        if (is_numeric($id)) {
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
    $empresa = new Empresa();
    $responsable = new ResponsableV();

    $id = readline("(Opcional) Id del viaje: ");
    $destino = readline("Destino del viaje: ");
    $cantmax = readline("Cantidad maxima de pasajeros: ");
    $idempresa = readline("ID de la empresa a cargo: ");
    $nempleado = readline("Numero de empleado responsable: ");
    $importe = readline("Importe: ");
    $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
    $idayvuelta = readline("Ida y vuelta? ");

    if ($id == null) {
        $id = 1;
        while ($viaje->Buscar($id, null)) {
            $id++;
        }
    }

    if (!$viaje->Buscar($id, "")) {
        if (!$viaje->Buscar(null,  "vdestino = '" . $destino . "'")) {
            if ($empresa->Buscar($idempresa) && $responsable->Buscar($nempleado)) {
                $viaje->Cargar($id, $destino, $cantmax, $empresa, $responsable, $importe, $tipoAsiento, $idayvuelta);

                $respuesta = $viaje->Insertar();
                if ($respuesta == true) {
                    echo "\n\t   El viaje fue ingresado en la BD.\n" .
                        "\t======================================\n";
                } else {
                    echo $viaje->getMensajeOp();
                }
            } else {
                echo "\nNo existe la empresa o responsable a cargo.\n";
            }
        } else {
            echo "\nExiste un viaje al destino.\n";
        }
    } else {
        echo "\nYa existe un viaje con ese ID.\n";
    }
}

function modificarViaje()
{
    $viaje = new Viaje();
    $empresa = new Empresa();
    $responsable = new ResponsableV();
    $id = readline("Ingrese el id del viaje a modificar: ");

    $respuesta = $viaje->Buscar($id, null);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $destino = readline("Destino del viaje: ");
        $cantmax = readline("Cantidad maxima de pasajeros: ");
        $idempresa = readline("ID de la empresa a cargo: ");
        $nempleado = readline("Numero de empleado responsable: ");
        $importe = readline("Importe: ");
        $tipoAsiento = readline("Tipo de asiento (Primera clase o no, semicama o cama): ");
        $idayvuelta = readline("Ida y vuelta? ");
        if (!$viaje->Buscar(null, "vdestino = '" . $destino . "'")) {
            if ($empresa->Buscar($idempresa) && $responsable->Buscar($nempleado)) {
                $viaje->setDestino($destino);
                $viaje->setCantMaxPasajeros($cantmax);
                $viaje->setEmpresa($empresa);
                $viaje->setResponsable($responsable);
                $viaje->setImporte($importe);
                $viaje->setTipoAsiento($tipoAsiento);
                $viaje->setIdaYVuelta($idayvuelta);

                $respuesta = $viaje->Modificar();
                if ($respuesta) {
                    echo "\n\t   El viaje fue modificado correctamente.\n" .
                        "\t============================================\n";
                } else {
                    echo "\nNo se pudo crear el nuevo viaje.\n";
                }
            } else {
                echo "\nNo existe la empresa o responsable.\n";
            }
        } else {
            echo "\nExiste un viaje al destino.\n";
        }
    } else {
        echo "No se pudo encontrar el viaje con id = " . $id . ".\n";
    }
}

function eliminarViaje()
{
    $viaje = new Viaje();
    $pasajero = new Pasajero();
    $pasajeroEncontrado = false;

    $id = readline("Ingrese el id del viaje a eliminar: ");
    $respuesta = $viaje->Buscar($id, null);

    if ($respuesta) {
        if ($pasajero->Buscar(null, "idviaje = " . $id)) {
            $eliminarViaje = readline("El viaje tiene pasajeros. Quiere eliminar el viaje junto a los pasajeros? (s/n) ");
            if ($eliminarViaje == "s") {
                $pasajeroEncontrado = false;
            } else {
                $pasajeroEncontrado = true;
            }
        }

        if (!$pasajeroEncontrado) {
            $respuesta = $viaje->Eliminar();
            if ($respuesta) {
                echo "\n\t   El viaje fue eliminado de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar el viaje.\n";
            }
        } else {
            echo "\nNo se puede eliminar el viaje y mantener los pasajeros en la DB.\n";
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
        if (is_numeric($id)) {
            $respuesta = $viaje->Buscar($id, null);
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

// Funciones Creacion/Modificacion de Responsables y Pasajeros. (Extra)
// Responsables
function ingresarResponsable()
{
    $responsable = new ResponsableV();

    $id = readline("(Opcional) Numero de empleado: ");
    $numLic = readline("Numero de licencia: ");
    $nombre = readline("Nombre del responsable: ");
    $apellido = readline("Apellido del responsable: ");

    if ($id == null) {
        $id = 1;
        while ($responsable->Buscar($id)) {
            $id++;
        }
    }

    if (!$responsable->Buscar($id)) {
        $responsable->Cargar($id, $numLic, $nombre, $apellido);

        $respuesta = $responsable->Insertar();
        if ($respuesta) {
            echo "\n\t   El responsable fue ingresada en la BD.\n" .
                "\t========================================\n";
        } else {
            echo $responsable->getMensajeOp();
        }
    } else {
        echo "\nYa existe un responsable con ese ID.\n";
    }
}

function modificarResponsable()
{
    $responsable = new ResponsableV();

    $numE = readline("Ingrese el numero de empleado del responsable a modificar: ");
    $respuesta = $responsable->Buscar($numE);
    if ($respuesta) {
        echo "Ingrese los nuevos datos.\n";
        $numLic = readline("Numero de licencia: ");
        $nombre = readline("Nombre del responsable: ");
        $apellido = readline("Apellido del responsable: ");

        $responsable->setLicencia($numLic);
        $responsable->setNombre($nombre);
        $responsable->setApellido($apellido);

        $respuesta = $responsable->Modificar($numE);
        if ($respuesta) {
            echo "\n\t   El responsable fue modificada correctamente.\n" .
                "\t==============================================\n";
        } else {
            echo "\nNo se pudo modificar el responsable.\n";
        }
    } else {
        echo "No se pudo encontrar el responsable con numero de empleado: " . $numE . "\n";
    }
}

function eliminarResponsable()
{
    $responsable = new ResponsableV();
    $viaje = new Viaje();
    $eliminarViaje = "n";

    $numE = readline("Ingrese el numero de empleado del responsable a eliminar: ");
    $respuesta = $responsable->Buscar($numE);

    if ($respuesta) {
        if ($viaje->Buscar(null, "rnumeroempleado = " . $numE)) {
            $eliminarViaje = readline("El responsable esta a cargo de un viaje. Quiere eliminar el viaje junto al responsable? (s/n) ");
            if ($eliminarViaje == "s") {
                $viaje->Eliminar();
                $viajeEncontrado = false;
            } else {
                $viajeEncontrado = true;
            }
        } else {
            $viajeEncontrado = false;
        }

        if (!$viajeEncontrado) {
            $respuesta = $responsable->Eliminar();
            if ($respuesta) {
                echo "\n\t   El responsable fue eliminado de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar el responsable.\n";
            }
        } else {
            echo "\nNo se puede eliminar un responsable a cargo de un viaje sin eliminar el viaje.\n";
        }
    } else {
        echo "No se pudo encontrar el responsable con numero de empleado: " . $numE . ".\n";
    }
}

function mostrarResponsable()
{
    $responsable = new ResponsableV();

    $resp = readline("Mostrar todos los responsables? (s/n) ");

    if ($resp == 's') {
        $colResponsables = $responsable->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colResponsables as $responsable) {

            echo $responsable;
            echo "-------------------------------------------------------";
        }
    } else {
        $numE = readline("Ingrese el numero de empleado: ");
        if (is_numeric($numE)) {
            $respuesta = $responsable->Buscar($numE);
            if ($respuesta) {
                echo $responsable;
            } else {
                echo "No se pudo encontrar el responsable.";
            }
        } else {
            echo "El valor ingresado no es valido.\n";
        }
    }
}

// Pasajeros
function ingresarPasajero()
{
    $pasajero = new Pasajero();
    $viaje = new Viaje();

    $dni = readline("Documento de pasajero: ");
    $nombre = readline("Nombre: ");
    $apellido = readline("Apellido: ");
    $telefono = readline("Telefono: ");
    $idviaje = readline("Id del viaje: ");

    if ($viaje->Buscar($idviaje, null)) {
        if (!$pasajero->Buscar($dni, null)) {
            $pasajero->Cargar($nombre, $apellido, $dni, $telefono, $viaje);

            $respuesta = $pasajero->Insertar();
            if ($respuesta == true) {
                echo "\n\t   La pasajero fue ingresado en la BD.\n" .
                    "\t========================================\n";
            } else {
                echo $pasajero->getMensajeOp();
            }
        } else {
            echo "\nYa existe un pasajero con ese documento.\n";
        }
    } else {
        echo "El viaje no existe.\n";
    }
}

function modificarPasajero()
{
    $pasajero = new Pasajero();
    $viaje = new Viaje();

    $dni = readline("Ingrese el documento del pasajero a modificar: ");
    if (is_numeric($dni)) {
        $respuesta = $pasajero->Buscar($dni, null);
        if ($respuesta) {
            echo "Ingrese los nuevos datos.\n";
            $nombre = readline("Nombre: ");
            $apellido = readline("Apellido: ");
            $telefono = readline("Telefono: ");
            $idviaje = readline("Id del viaje: ");

            if ($viaje->Buscar($idviaje, null)) {
                $pasajero->setNombre($nombre);
                $pasajero->setApellido($apellido);
                $pasajero->setTelefono($telefono);
                $pasajero->setViaje($viaje);

                $respuesta = $pasajero->Modificar(null);
                if ($respuesta) {
                    echo "\n\t   El pasajero fue modificado correctamente.\n" .
                        "\t==============================================\n";
                } else {
                    echo "\nNo se pudo modificar el pasajero.\n";
                }
            } else {
                echo "El viaje no existe.\n";
                echo "\nNo se pudo modificar el pasajero.\n";
            }
        } else {
            echo "No se pudo encontrar el pasajero de documento: " . $dni . ".\n";
        }
    } else {
        echo "El documento ingresado es incorrecto.\n";
    }
}

function eliminarPasajero()
{
    $pasajero = new Pasajero();

    $dni = readline("Ingrese el documento del pasajero a eliminar: ");

    if (is_numeric($dni)) {
        $respuesta = $pasajero->Buscar($dni, null);
        if ($respuesta) {
            $respuesta = $pasajero->Eliminar();
            if ($respuesta) {
                echo "\n\t   El pasajero fue eliminado de la BD.\n" .
                    "\t=========================================\n";
            } else {
                echo "\nNo se pudo eliminar el pasajero.\n";
            }
        } else {
            echo "No se pudo encontrar el pasajero el documento: " . $dni . ".\n";
        }
    } else {
        echo "El documento ingresado es incorrecto.\n";
    }
}

function mostrarPasajero()
{
    $pasajero = new Pasajero();

    $resp = readline("Mostrar todos los pasajeros? (s/n) ");

    if ($resp == 's') {
        $colPasajeros = $pasajero->Listar("");

        echo "-------------------------------------------------------";
        foreach ($colPasajeros as $pasajero) {

            echo $pasajero;
            echo "-------------------------------------------------------";
        }
    } else {
        $dniP = readline("Ingrese documento del pasajero: ");
        if (is_numeric($dniP)) {
            $respuesta = $pasajero->Buscar($dniP, null);
            if ($respuesta) {
                echo $pasajero;
            } else {
                echo "No se pudo encontrar el pasajero.";
            }
        } else {
            echo "El documetno ingresado no es valido.\n";
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
        case 0:
            $resp = false;
            break;
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
            ingresarResponsable();
            break;
        case 10:
            modificarResponsable();
            break;
        case 11:
            eliminarResponsable();
            break;
        case 12:
            mostrarResponsable();
            break;
        case 13:
            ingresarPasajero();
            break;
        case 14:
            modificarPasajero();
            break;
        case 15:
            eliminarPasajero();
            break;
        case 16:
            mostrarPasajero();
            break;
        default:
            echo "Opcion incorrecta. Tiene que ser un numero entre 1 y 16.";
            time_nanosleep(2, 0);
            $resp = true;
    }

    echo "\n";
    if ($opcion != 0 && ($opcion > 0 && $opcion <= 16)) {
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
