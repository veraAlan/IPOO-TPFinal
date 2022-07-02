<?php
class Pasajero
{
    private $pnombre;
    private $papellido;
    private $rdocumento;
    private $ptelefono;
    private $viaje;

    /**
     * Constructor de objeto pasajero.
     */
    public function __construct()
    {
        $this->pnombre = "";
        $this->papellido = "";
        $this->rdocumento = "";
        $this->ptelefono = 0;
        $this->viaje = null;
    }

    // Setter
    public function setNombre($pnombre)
    {
        $this->pnombre = $pnombre;
    }

    public function setApellido($papellido)
    {
        $this->papellido = $papellido;
    }

    public function setDocumento($rdocumento)
    {
        $this->rdocumento = $rdocumento;
    }

    public function setTelefono($ptelefono)
    {
        $this->ptelefono = $ptelefono;
    }

    public function setViaje($id)
    {
        $this->viaje = $id;
    }

    public function setMensajeOp($mensaje)
    {
        $this->mensajeOp = $mensaje;
    }

    // Getter
    public function getNombre()
    {
        return $this->pnombre;
    }

    public function getApellido()
    {
        return $this->papellido;
    }

    public function getDocumento()
    {
        return $this->rdocumento;
    }

    public function getTelefono()
    {
        return $this->ptelefono;
    }

    public function getIdViaje()
    {
        return $this->viaje;
    }

    public function getMensajeOp()
    {
        return $this->mensajeOp;
    }

    // Metodos 
    /**
     * Cargar datos al objeto Pasajero.
     * @param string $nombre
     * @param string $apellido
     * @param int $documento
     * @param int $telefono
     * @param int $viaje
     * @return void
     */
    public function Cargar($nombre, $apellido, $documento, $telefono, $viaje)
    {
        $this->pnombre = $nombre;
        $this->papellido = $apellido;
        $this->rdocumento = $documento;
        $this->ptelefono = $telefono;
        $this->viaje = $viaje;
    }

    /**
     * Busca un pasajero segun su dni.
     * @param int $dni
     * @return bool
     */
    public function Buscar($dni)
    {
        $bd = new Database();
        $viaje = new Viaje();

        $consultaPasajero = "SELECT * FROM pasajero WHERE rdocumento = " . $dni;
        $resp = false;

        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaPasajero)) {
                if ($row2 = $bd->Register()) {
                    $this->setDocumento($dni);
                    $this->setNombre($row2['pnombre']);
                    $this->setApellido($row2['papellido']);
                    $this->setTelefono($row2['ptelefono']);
                    $this->setViaje($viaje->Buscar($row2['idviaje'], null));
                    $resp = true;
                }
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $resp;
    }

    /**
     * Lista todos los pasajeros presentes en la tabla pasajero de la base de datos.
     * @param string $condicion
     * @return array
     */
    public function Listar($condicion)
    {
        $arrPasajeros = null;
        $bd = new Database();
        $consultaP = "SELECT * FROM pasajero WHERE " . $condicion . ";";
        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaP)) {
                $arrPasajeros = array();
                while ($row2 = $bd->Register()) {

                    $dni = $row2['rdocumento'];
                    $nombre = $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $id = $row2['idviaje'];

                    $pasajero = new Pasajero();
                    $pasajero->Cargar($nombre, $apellido, $dni, $telefono, $id);
                    array_push($arrPasajeros, $pasajero);
                }
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $arrPasajeros;
    }

    /**
     * Inserta una nuevo pasajero a la BD.
     * @return bool
     */
    public function Insertar()
    {
        $bd = new Database();
        $viaje = $this->getIdViaje();
        $resp = false;
        if ($bd->Start()) {
            $queryInsertar = "INSERT INTO pasajero(rdocumento, pnombre, papellido, ptelefono, idviaje) 
                    VALUES ('" . $this->getDocumento() . "','" .
                $this->getNombre() . "','" .
                $this->getApellido() . "','" .
                $this->getTelefono() . "','" .
                $viaje->getIdviaje() . "')";
            if ($bd->ExecQuery($queryInsertar)) {
                $resp = true;
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $resp;
    }

    /**
     * Ejecuta los cambios en la tabla de pasajero.
     * @return bool
     */
    public function Modificar($dniAntiguo = "", $condicion = "")
    {
        $resp = false;
        $bd = new Database();
        if ($dniAntiguo == null) {
            $queryModifica = "UPDATE pasajero 
            SET pnombre = '" . $this->getNombre() .
                "', papellido = '" . $this->getApellido() .
                "', ptelefono = '" . $this->getTelefono() .
                "' WHERE rdocumento = " . $this->getDocumento();
        } else {
            $queryModifica = "UPDATE pasajero 
            SET rdocumento = " .  $this->getDocumento() .
                ", pnombre = '" . $this->getNombre() .
                "', papellido = '" . $this->getApellido() .
                "', ptelefono = '" . $this->getTelefono() .
                "' WHERE rdocumento = " . $dniAntiguo;
        }

        if ($condicion != null) {
            $queryModifica = $condicion;
        }

        if ($bd->Start()) {
            if ($bd->ExecQuery($queryModifica)) {
                $resp =  true;
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $resp;
    }

    /**
     * Elimina un pasajero de la base de datos.
     * @return bool
     */
    public function Eliminar()
    {
        $bd = new Database();
        $resp = false;
        if ($bd->Start()) {
            $queryBorrar = "DELETE FROM pasajero WHERE rdocumento = " . $this->getDocumento();
            if ($bd->ExecQuery($queryBorrar)) {
                $resp =  true;
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $resp;
    }

    // To String
    public function __toString()
    {
        return "\n\tNombre: " . $this->getNombre() .
            "\n\tApellido: " . $this->getApellido() .
            "\n\tDNI: " . $this->getDocumento() .
            "\n\tTelefono: " . $this->getTelefono() .
            "\n\tID Viaje que tomara: " . $this->getIdViaje() . "\n";
    }
}
