<?php
class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa;
    private $rnumeroempleado;
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;
    private $pasajeros; // Array [Objects]
    private $responsable; // Object

    /**
     * Constructor del objeto Viaje.
     */
    public function __construct()
    {
        $this->idviaje = 0;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = 0;
        $this->idempresa = 0;
        $this->rnumeroempleado = "";
        $this->vimporte = 0;
        $this->tipoAsiento = "";
        $this->idayvuelta = "";
        $this->pasajeros = [];
        $this->responsable;
    }

    // Setters
    public function setIdViaje($id)
    {
        $this->idviaje = $id;
    }

    public function setDestino($destino)
    {
        $this->vdestino = $destino;
    }

    public function setCantMaxPasajeros($cantidad)
    {
        $this->vcantMaxPasajeros = $cantidad;
    }

    public function setIdEmpresa($id)
    {
        $this->idempresa = $id;
    }

    public function setNumeroEmpleado($numEmpleado)
    {
        $this->rnumeroempleado = $numEmpleado;
    }

    public function setImporte($importe)
    {
        $this->vimporte = $importe;
    }

    public function setTipoAsiento($tipo)
    {
        $this->tipoAsiento = $tipo;
    }

    public function setIdaYVuelta($idayvuelta)
    {
        $this->idayvuelta = $idayvuelta;
    }

    public function setPasajeros($colPasajeros)
    {
        $this->pasajeros = $colPasajeros;
    }

    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function setMensajeOp($mensaje)
    {
        $this->mensajeOp = $mensaje;
    }

    // Getters 
    public function getIdViaje()
    {
        return $this->idviaje;
    }

    public function getDestino()
    {
        return $this->vdestino;
    }

    public function getCantMaxPasajeros()
    {
        return $this->vcantmaxpasajeros;
    }

    public function getIdEmpresa()
    {
        return $this->idempresa;
    }

    public function getNumeroEmpleado()
    {
        return $this->rnumeroempleado;
    }

    public function getImporte()
    {
        return $this->vimporte;
    }

    public function getTipoAsiento()
    {
        return $this->tipoAsiento;
    }

    public function getIdaYVuelta()
    {
        return $this->idayvuelta;
    }

    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }

    public function getMensajeOp()
    {
        return $this->mensajeOp;
    }

    // Metodos
    /**
     * Cargar datos al objeto Viaje.
     * @param int $idviaje 
     * @param string $vdestino
     * @param int $vcantmaxpasajeros
     * @param int $idempresa
     * @param int $rnumeroempleado
     * @param float $vimporte
     * @param string $tipoAsiento
     * @param string $idayvuelta
     * @return void
     */
    public function Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $idempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta)
    {
        $this->idviaje = $idviaje;
        $this->vdestino = $vdestino;
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
        $this->idempresa = $idempresa;
        $this->rnumeroempleado = $rnumeroempleado;
        $this->vimporte = $vimporte;
        $this->tipoAsiento = $tipoAsiento;
        $this->idayvuelta = $idayvuelta;
        $this->EncontrarEmpleado();
    }

    /**
     * Busca una empresa segun su id.
     * @param int $id
     * @return bool
     */
    public function Buscar($id)
    {
        $bd = new Database();
        $consultaViaje = "SELECT * FROM viaje WHERE idviaje = " . $id;
        $resp = false;

        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaViaje)) {
                if ($row2 = $bd->Register()) {
                    $this->setIdViaje($id);
                    $this->setDestino($row2['vdestino']);
                    $this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                    $this->setIdEmpresa($row2['idempresa']);
                    $this->setNumeroEmpleado($row2['rnumeroempleado']);
                    $this->setImporte($row2['vimporte']);
                    $this->setTipoAsiento($row2['tipoAsiento']);
                    $this->setIdaYVuelta($row2['idayvuelta']);
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
     * Lista todas los viajes presentes en la tabla viaje de la base de datos.
     * @param string $condicion
     * @return array
     */
    public function Listar($condicion = "")
    {
        $arrViaje = null;
        $bd = new Database();
        $consultaV = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaV = $consultaV . ' where ' . $condicion;
        }
        $consultaV .= " order by idviaje ";
        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaV)) {
                $arrViaje = array();
                while ($row2 = $bd->Register()) {

                    $idviaje = $row2['idviaje'];
                    $vdestino = $row2['vdestino'];
                    $vcantmaxpasajeros = $row2['vcantmaxpasajeros'];
                    $idempresa = $row2['idempresa'];
                    $rnumeroempleado = $row2['rnumeroempleado'];
                    $vimporte = $row2['vimporte'];
                    $tipoAsiento = $row2['tipoAsiento'];
                    $idayvuelta = $row2['idayvuelta'];

                    $viaje = new Viaje();
                    $viaje->Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $idempresa, $rnumeroempleado, $vimporte, $tipoAsiento, $idayvuelta);
                    array_push($arrViaje, $viaje);
                }
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $arrViaje;
    }

    /**
     * Inserta un nuevo viaje a la BD.
     * @return bool
     */
    public function Insertar()
    {
        $bd = new Database();
        $resp = false;
        // Por si el usuario especifica o no el id. Se crea uno con AUTO_INCREMENT en caso que no se especifique.
        if ($this->getIdviaje() == null) {
            $queryInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
                    VALUES ('" . $this->getDestino() . "','" .
                $this->getCantMaxPasajeros() .  "','" .
                $this->getIdEmpresa() .  "','" .
                $this->getNumeroEmpleado() .  "','" .
                $this->getImporte() .  "','" .
                $this->getTipoAsiento() .  "','" .
                $this->getIdaYVuelta() .  "')";
        } else {
            $queryInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta)
                    VALUES ('" . $this->getDestino() . "','" .
                $this->getCantMaxPasajeros() .  "','" .
                $this->getIdEmpresa() .  "','" .
                $this->getNumeroEmpleado() .  "','" .
                $this->getImporte() .  "','" .
                $this->getTipoAsiento() .  "','" .
                $this->getIdaYVuelta() .  "')";
        }
        if ($bd->Start()) {
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
     * Ejecuta los cambios en la tabla de viajes.
     * @return bool
     */
    public function Modificar()
    {
        $resp = false;
        $bd = new Database();
        $queryModifica = "UPDATE viaje 
            SET vdestino = '" . $this->getDestino() .
            "', vcantmaxpasajeros = '" . $this->getCantMaxPasajeros() .
            "', idempresa = '" . $this->getIdEmpresa() .
            "', rnumeroempleado = '" . $this->getNumeroEmpleado() .
            "', vimporte = '" . $this->getImporte() .
            "', tipoAsiento = '" . $this->getTipoAsiento() .
            "', idayvuelta = '" . $this->getIdaYVuelta() .
            "' WHERE idviaje = " . $this->getIdViaje();
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
     * Elimina un viaje y todos sus pasajeros de la BD.
     * @return bool
     */
    public function Eliminar()
    {
        $bd = new Database();
        $resp = false;

        if ($bd->Start()) {
            // Eliminar los pasajeros del viaje primero por la constraint de la key idviaje.
            $queryBorrarP = "DELETE FROM pasajero WHERE idviaje = " . $this->getIdviaje();
            $queryBorrarV = "DELETE FROM viaje WHERE idviaje = " . $this->getIdviaje();
            if ($bd->ExecQuery($queryBorrarP) && $bd->ExecQuery($queryBorrarV)) {
                $resp =  true;
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }

        return $resp;
    }

    public function EncontrarEmpleado()
    {
        $responsable = new ResponsableV();
        $numEmpleado = $this->getNumeroEmpleado();

        $responsable->Buscar($numEmpleado);
        $this->setResponsable($responsable);
    }

    // To String
    public function colPasajerosAString()
    {
        $objPasajero = new Pasajero();

        $condicion = "idviaje = " . $this->getIdViaje();
        $colPasajeros = $objPasajero->Listar($condicion);
        $string = "\n";

        foreach ($colPasajeros as $pasajero) {
            $string .= $pasajero->__toString() . "\n\t-----------------------------\n";
        }

        return $string;
    }

    public function __toString()
    {
        return "\nViaje: " . $this->getIdViaje() .
            "\nDestino: " . $this->getDestino() .
            "\nCantidad Maxima de Pasajeros: " . $this->getCantMaxPasajeros() .
            "\nID Empresa: " . $this->getIdEmpresa() .
            "\nNumero Empleado (Responsable): " . $this->getNumeroEmpleado() .
            "\nImporte: " . $this->getImporte() .
            "\nTipo Asiento: " . $this->getTipoAsiento() .
            "\nIda y Vuelta: " . $this->getIdaYVuelta() .
            "\nResponsable: " . $this->getResponsable() .
            "\nPasajeros: " . $this->colPasajerosAString();
    }
}
