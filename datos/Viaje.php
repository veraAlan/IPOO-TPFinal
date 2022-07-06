<?php
class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $empresa; // Object
    private $responsable; // Object
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;
    private $pasajeros; // Array [Objects]

    /**
     * Constructor del objeto Viaje.
     */
    public function __construct()
    {
        $this->idviaje;
        $this->vdestino;
        $this->vcantmaxpasajeros;
        $this->empresa;
        $this->responsable;
        $this->vimporte;
        $this->tipoAsiento;
        $this->idayvuelta;
        $this->pasajeros;
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
        $this->vcantmaxpasajeros = $cantidad;
    }

    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    public function setResponsable($empleado)
    {
        $this->responsable = $empleado;
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

    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function getResponsable()
    {
        return $this->responsable;
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
     * @param object $empresa
     * @param object $responsable
     * @param float $vimporte
     * @param string $tipoAsiento
     * @param string $idayvuelta
     * @return void
     */
    public function Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte, $tipoAsiento, $idayvuelta)
    {
        $this->idviaje = $idviaje;
        $this->vdestino = $vdestino;
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
        $this->empresa = $empresa;
        $this->responsable = $responsable;
        $this->vimporte = $vimporte;
        $this->tipoAsiento = $tipoAsiento;
        $this->idayvuelta = $idayvuelta;
    }

    /**
     * Busca una empresa segun su id.
     * @param int $id
     * @return bool
     */
    public function Buscar($id, $condicion)
    {
        $bd = new Database();
        $empresa = new Empresa();
        $responsable = new ResponsableV();
        $pasajero = new Pasajero();

        $resp = false;
        $consultaViaje = "SELECT * FROM viaje WHERE ";
        if ($condicion == null) {
            $consultaViaje .= 'idviaje = ' . $id;
        } else {
            $consultaViaje .= $condicion;
        }
        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaViaje)) {
                if ($regEnc = $bd->Register()) {
                    $this->setIdViaje($regEnc['idviaje']);
                    $this->setDestino($regEnc['vdestino']);
                    $this->setCantMaxPasajeros($regEnc['vcantmaxpasajeros']);
                    $empresa->Buscar($regEnc['idempresa']);
                    $this->setEmpresa($empresa);
                    $responsable->Buscar($regEnc['rnumeroempleado']);
                    $this->setResponsable($responsable);
                    $this->setImporte($regEnc['vimporte']);
                    $this->setTipoAsiento($regEnc['tipoAsiento']);
                    $this->setIdaYVuelta($regEnc['idayvuelta']);
                    $this->setPasajeros($pasajero->Listar(" idviaje = " . $this->getIdViaje()));
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
        $empresa = new Empresa();
        $responsable = new ResponsableV();

        $consultaV = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaV = $consultaV . ' where ' . $condicion;
        }
        $consultaV .= " order by idviaje ";
        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaV)) {
                $arrViaje = [];
                while ($regEnc = $bd->Register()) {

                    $idviaje = $regEnc['idviaje'];
                    $vdestino = $regEnc['vdestino'];
                    $vcantmaxpasajeros = $regEnc['vcantmaxpasajeros'];
                    $empresa->Buscar($regEnc['idempresa']);
                    $responsable->Buscar($regEnc['rnumeroempleado']);
                    $vimporte = $regEnc['vimporte'];
                    $tipoAsiento = $regEnc['tipoAsiento'];
                    $idayvuelta = $regEnc['idayvuelta'];
                    $viaje = new Viaje();

                    $viaje->Cargar($idviaje, $vdestino, $vcantmaxpasajeros, $empresa, $responsable, $vimporte, $tipoAsiento, $idayvuelta);

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
        $empresa = $this->getEmpresa();
        $responsable = $this->getResponsable();
        $resp = false;
        // Por si el usuario especifica o no el id. Se crea uno con AUTO_INCREMENT en caso que no se especifique.
        if ($this->getIdviaje() == null) {
            $queryInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
                    VALUES ('" . $this->getDestino() . "','" .
                $this->getCantMaxPasajeros() .  "','" .
                $empresa->getIdempresa() .  "','" .
                $responsable->getNumeroEmpleado() .  "','" .
                $this->getImporte() .  "','" .
                $this->getTipoAsiento() .  "','" .
                $this->getIdaYVuelta() .  "')";
        } else {
            $queryInsertar = "INSERT INTO viaje(idviaje, vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta)
                    VALUES ('" . $this->getIdViaje() . "','" .
                $this->getDestino() . "','" .
                $this->getCantMaxPasajeros() .  "','" .
                $empresa->getIdempresa() .  "','" .
                $responsable->getNumeroEmpleado() .  "','" .
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
        $empresa = $this->getEmpresa();
        $responsable = $this->getResponsable();
        $queryModifica = "UPDATE viaje 
            SET vdestino = '" . $this->getDestino() .
            "', vcantmaxpasajeros = '" . $this->getCantMaxPasajeros() .
            "', idempresa = '" . $empresa->getIdempresa() .
            "', rnumeroempleado = '" . $responsable->getNumeroEmpleado() .
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

    public function colPasajerosAString()
    {
        $objPasajero = new Pasajero();

        $condicion = " idviaje = " . $this->getIdViaje();
        $colPasajeros = $objPasajero->Listar($condicion);
        $string = "\n";

        foreach ($colPasajeros as $pasajero) {
            $string .= $pasajero->__toString() . "\n\t-----------------------------\n";
        }

        return $string;
    }

    // To String
    public function __toString()
    {
        return "\nViaje: " . $this->getIdViaje() .
            "\nDestino: " . $this->getDestino() .
            "\nCantidad Maxima de Pasajeros: " . $this->getCantMaxPasajeros() .
            "\nID Empresa: " . $this->getEmpresa() .
            "\nImporte: " . $this->getImporte() .
            "\nTipo Asiento: " . $this->getTipoAsiento() .
            "\nIda y Vuelta: " . $this->getIdaYVuelta() .
            "\nResponsable: " . $this->getResponsable() .
            "\nPasajeros: " . $this->colPasajerosAString();
    }
}
