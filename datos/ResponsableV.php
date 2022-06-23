<?php
class ResponsableV
{
    private $rnumeroEmpleado;
    private $rlicencia;
    private $rnombre;
    private $rapellido;

    /**
     * Constructor de objeto responsable.
     */
    public function __construct()
    {
        $this->rnumeroEmpleado = null;
        $this->rlicencia = null;
        $this->rnombre = "";
        $this->rapellido =  "";
    }

    // Setters
    public function setNumeroEmpleado($rnumeroEmpleado)
    {
        return $this->rnumeroEmpleado = $rnumeroEmpleado;
    }

    public function setLicencia($rlicencia)
    {
        return $this->rlicencia = $rlicencia;
    }

    public function setNombre($rnombre)
    {
        return $this->rnombre = $rnombre;
    }

    public function setApellido($rapellido)
    {
        return $this->rapellido = $rapellido;
    }

    public function setMensajeOp($mensaje)
    {
        $this->mensajeOp = $mensaje;
    }

    // Getters

    public function getNumeroEmpleado()
    {
        return $this->rnumeroEmpleado;
    }

    public function getLicencia()
    {
        return $this->rlicencia;
    }
    public function getNombre()
    {
        return $this->rnombre;
    }

    public function getApellido()
    {
        return $this->rapellido;
    }

    public function getMensajeOp()
    {
        return $this->mensajeOp;
    }

    // Metodos 
    /**
     * Cargar datos al objeto Pasajero.
     * @param int $numEmpleado
     * @param int $numlicencia
     * @param string $nombreResponsable
     * @param string $apellidoResponsable
     * @return void
     */
    public function Cargar($numEmpleado, $numlicencia, $nombreResponsable, $apellidoResponsable)
    {
        $this->rnumeroEmpleado = $numEmpleado;
        $this->rlicencia = $numlicencia;
        $this->rnombre = $nombreResponsable;
        $this->rapellido = $apellidoResponsable;
    }

    /**
     * Busca un responsable segun su numero de empleado.
     * @param int $numEmpleado
     * @return bool
     */
    public function Buscar($numEmpleado)
    {
        $bd = new Database();
        $consultaR = "SELECT * FROM responsable WHERE rnumeroempleado = " . $numEmpleado;
        $resp = false;

        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaR)) {
                if ($row2 = $bd->Register()) {
                    $this->setNumeroEmpleado($numEmpleado);
                    $this->setNombre($row2['rnombre']);
                    $this->setApellido($row2['rapellido']);
                    $this->setLicencia($row2['rnumerolicencia']);
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
     * Lista todos los responsables presentes en la tabla responsable de la base de datos.
     * @param string $condicion
     * @return array
     */
    public function Listar($condicion = "")
    {
        $arrResponsables = null;
        $bd = new Database();
        $consultaP = "SELECT * FROM responsable ";
        if ($condicion != "") {
            $consultaP = $consultaP . ' where ' . $condicion;
        }
        $consultaP .= " order by rnumeroempleado ";
        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaP)) {
                $arrResponsables = array();
                while ($row2 = $bd->Register()) {

                    $numEmpleado = $row2['rnumeroempleado'];
                    $nombre = $row2['rnombre'];
                    $apellido = $row2['rapellido'];
                    $numLicencia = $row2['rnumerolicencia'];

                    $responsable = new ResponsableV();
                    $responsable->Cargar($numEmpleado, $numLicencia, $nombre, $apellido);
                    array_push($arrResponsables, $responsable);
                }
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $arrResponsables;
    }

    /**
     * Inserta un nuevo responsable.
     * @return bool
     */
    public function Insertar()
    {
        $bd = new Database();
        $resp = false;
        $queryInsertar = "INSERT INTO responsable(rnumeroempleado, rnumerolicencia, rnombre, rapellido) 
                    VALUES (" . $this->getNumeroEmpleado() . ",'" .
            $this->getLicencia() . "','" .
            $this->getNombre() . "','" .
            $this->getApellido() . "')";
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
     * Ejecuta los cambios en la tabla de responsable.
     * @return bool
     */
    public function Modificar()
    {
        $resp = false;
        $bd = new Database();
        $queryModifica = "UPDATE responsable 
            SET rnombre = '" . $this->getNombre() .
            "', rapellido = '" . $this->getApellido() .
            "', rnumerolicencia = '" . $this->getLicencia() .
            "' WHERE rnumeroempleado = " . $this->getNumeroEmpleado();
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
     * Elimina un responsable de la BD.
     * @return bool
     */
    public function Eliminar($condicion)
    {
        $bd = new Database();
        $resp = false;
        $queryBorrar = "DELETE FROM persona WHERE " . $condicion;
        if ($bd->Start()) {
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
        return "\n\tNumero Empleado: " . $this->getNumeroEmpleado() .
            "\n\tNumero Licencia: " . $this->getlicencia() .
            "\n\tNombre: " . $this->getNombre() .
            "\n\tApellido: " . $this->getApellido();
    }
}
