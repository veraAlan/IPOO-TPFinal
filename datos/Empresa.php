<?php
class Empresa
{
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeOp;

    public function __construct()
    {
        $this->idempresa = "";
        $this->enombre = "";
        $this->edireccion = "";
    }

    // Setter
    public function setIdempresa($nuevoId)
    {
        $this->idempresa = $nuevoId;
    }

    public function setEnombre($nuevoNom)
    {
        $this->enombre = $nuevoNom;
    }

    public function setEdireccion($nuevaDir)
    {
        $this->edireccion = $nuevaDir;
    }

    public function setMensajeOp($mensaje)
    {
        $this->mensajeOp = $mensaje;
    }

    // Getters
    public function getIdempresa()
    {
        return $this->idempresa;
    }

    public function getEnombre()
    {
        return $this->enombre;
    }

    public function getEdireccion()
    {
        return $this->edireccion;
    }

    public function getMensajeOp()
    {
        return $this->mensajeOp;
    }

    // Metodos 
    /**
     * Cargar datos al objeto Empresa.
     * @param int $idE
     * @param string $nomE
     * @param string $dirE
     * @return void
     */
    public function Cargar($idE, $nomE, $dirE)
    {
        $this->setIdempresa($idE);
        $this->setEnombre($nomE);
        $this->setEdireccion($dirE);
    }

    /**
     * Busca una empresa segun su id.
     * @param int $id
     * @return bool
     */
    public function Buscar($id)
    {
        $bd = new Database();
        $consultaEmpresa = "SELECT * FROM empresa WHERE idempresa = " . $id;
        $resp = false;

        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaEmpresa)) {
                if ($row2 = $bd->Register()) {
                    $this->setIdempresa($id);
                    $this->setEnombre($row2['enombre']);
                    $this->setEdireccion($row2['edireccion']);
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
     * Lista todas las empresas presentes en la tabla empresa de la base de datos.
     * @param string $condicion
     * @return array
     */
    public function Listar($condicion = "")
    {
        $arrEmpresa = null;
        $bd = new Database();
        $consultaE = "SELECT * FROM empresa ";
        if ($condicion != "") {
            $consultaE = $consultaE . ' where ' . $condicion;
        }
        $consultaE .= " order by idempresa ";
        if ($bd->Start()) {
            if ($bd->ExecQuery($consultaE)) {
                $arrEmpresa = array();
                while ($row2 = $bd->Register()) {

                    $idempresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];

                    $empresa = new Empresa();
                    $empresa->Cargar($idempresa, $enombre, $edireccion);
                    array_push($arrEmpresa, $empresa);
                }
            } else {
                $this->setMensajeOp($bd->getError());
            }
        } else {
            $this->setMensajeOp($bd->getError());
        }
        return $arrEmpresa;
    }

    /**
     * Inserta una nueva empresa.
     * @return bool
     */
    public function Insertar()
    {
        $bd = new Database();
        $resp = false;
        if ($this->getIdempresa() == null) {
            $queryInsertar = "INSERT INTO empresa(enombre, edireccion) 
                    VALUES ('" . $this->getEnombre() . "','" . $this->getEdireccion() . "')";
        } else {
            $queryInsertar = "INSERT INTO empresa(idempresa, enombre, edireccion) 
                    VALUES (" . $this->getIdempresa() . ",'" . $this->getEnombre() . "','" . $this->getEdireccion() . "')";
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
     * Ejecuta los cambios en la tabla de empresa.
     * @return bool
     */
    public function Modificar($idAntiguo = "")
    {
        $resp = false;
        $bd = new Database();
        if ($idAntiguo == null) {
            $queryModifica = "UPDATE empresa 
            SET enombre = '" . $this->getEnombre() .
                "', edireccion = '" . $this->getEdireccion() .
                "' WHERE idempresa = " . $$this->getIdempresa();
        } else {
            $queryModifica = "UPDATE empresa 
            SET idempresa = " . $this->getIdempresa() .
                ", enombre = '" . $this->getEnombre() .
                "', edireccion = '" . $this->getEdireccion() .
                "' WHERE idempresa = " . $idAntiguo;
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
     * Elimina una empresa de la BD.
     * @return bool
     */
    public function Eliminar()
    {
        $bd = new Database();
        $resp = false;
        if ($bd->Start()) {
            $queryBorrar = "DELETE FROM empresa WHERE idempresa=" . $this->getIdempresa();
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
        return "\nId Empresa: " . $this->getIdempresa() .
            "\nNombre: " . $this->getEnombre() .
            "\nDireccion: " . $this->getEdireccion() . "\n";
    }
}
