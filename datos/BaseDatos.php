<?php
class Database
{
    private $HOSTNAME;
    private $DATABASE;
    private $USER;
    private $PASSWORD;
    private $LINK;
    private $QUERY;
    private $RESULT;
    private $ERROR;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->HOSTNAME = "localhost"; // Localhost
        $this->DATABASE = "bdviajes";
        $this->USER = "root";
        $this->PASSWORD = "FF0734Casa35"; // Root Password / Contrasenia de usuario Root
        $this->QUERY = "";
        $this->ERROR = "";
    }

    /**
     * Devuelve mensaje de error.
     * @return string
     */
    public function getError()
    {
        return "\n" . $this->ERROR;
    }

    /**
     * Inicia la coneccion con la BD.
     * @return boolean
     */
    public function Start()
    {
        $resp = false;
        $link = mysqli_connect($this->HOSTNAME, $this->USER, $this->PASSWORD, $this->DATABASE);
        if ($link) {
            if (mysqli_select_db($link, $this->DATABASE)) {
                $this->LINK = $link;
                unset($this->QUERY);
                unset($this->ERROR);
                $resp = true;
            } else {
                $this->ERROR = mysqli_errno($link) . ": " . mysqli_error($link);
            }
        }

        return $resp;
    }

    /**
     * Ejecuta una consulta en la base de datos.
     * @param string $query
     * @return boolean
     */
    public function ExecQuery($query)
    {
        $resp = false;
        unset($this->ERROR);
        $this->QUERY = $query;
        if ($this->RESULT = mysqli_query($this->LINK, $query)) {
            $resp = true;
        } else {
            $this->ERROR = mysqli_errno($this->LINK) . ": " . mysqli_error($this->LINK);
        }

        return $resp;
    }

    /**
     * Consigue el registro en la base de datos.
     * @return array
     */
    public function Register()
    {
        $resp = null;
        if ($this->RESULT) {
            unset($this->ERROR);
            if ($temp = mysqli_fetch_assoc($this->RESULT)) {
                $resp = $temp;
            } else {
                mysqli_free_result($this->RESULT);
            }
        } else {
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        return $resp;
    }
}
