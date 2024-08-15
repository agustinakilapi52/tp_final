<?php
class Model_compraestadotipo extends BaseDatos {
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    
    private $mensajeOperacion;


    public function __construct() {
        parent::__construct();
        $this->idcompraestadotipo = "";
        $this->cetdescripcion = "";
        $this->cetdetalle = "";
    }

    public function setearValores($idcompraestadotipo, $cetdescripcion, $cetdetalle) {
        $this->setIdCompraestadotipo($idcompraestadotipo);
        $this->setCetdescripcion($cetdescripcion);
        $this->setCetdetalle($cetdetalle);
   
    }

    public function getIdCompraestadotipo() {
        return $this->idcompraestadotipo;
    }

    public function setIdCompraestadotipo($idcompraestadotipo) {
        $this->idcompraestadotipo= $idcompraestadotipo;
    }

    public function getCetdescripcion() {
        return $this->cetdescripcion;
    }

    public function setCetdescripcion($cetdescripcion) {
        $this->cetdescripcion= $cetdescripcion;
    }
    public function getCetdetalle() {
        return $this->cetdetalle;
    }

    public function setCetdetalle($cetdetalle) {
        $this->cetdetalle= $cetdetalle;
    }

    public function getmensajeoperacion()
    {
        return $this->mensajeOperacion;
    }
    public function setMsjOperacion($valor)
    {
        $this->mensajeOperacion = $valor;
    }


    public function Insertar() {
        $rta = false;

        $query = "INSERT INTO compraestadotipo(idcompraestadotipo, cetdescripcion, cetdetalle)
                  VALUES ('" . $this->getIdCompraestadotipo() . "','" . $this->getCetdescripcion(). "','" . $this->getCetdetalle() .  "')";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                $rta = true;
            } else {
                $this->getError();
            }
        } else {
            $this->getError();
        }

        return $rta;
    }

    public function Modificar() {
        $rta = false;
        $query = "UPDATE compraestadotipo SET idcompraestadotipo='" . $this->getIdCompraestadotipo() . "',cetdescripcion='" . $this->getCetdescripcion(). "',cetdetalle='" . $this->getCetdetalle(). "'
                 WHERE idcompraestadotipo ='" . $this->$this->getIdCompraestadotipo() . "'";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                $rta = true;
            } else {
                $this->getError();
            }
        } else {
            $this->getError();
        }

        return $rta;
    }
   
    public function Eliminar() {
        $rta = false;

        $query = "DELETE FROM compraestadotipo WHERE idcompraestadotipo=" . $this->getIdCompraestadotipo();

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                $rta = true;
            } else {
                $this->getError();
            }
        } else {
            $this->getError();
        }

        return $rta;
    }

    public function Buscar($idcompraestadotipo) {

        $query = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo =" . $idcompraestadotipo;

        $rta = false;
      
        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                if ($row = $this->Registro()) {
                    $this->setearValores($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    $rta = true;
                }
            } else {
                $this->setMsjOperacion($this->getError());
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }
        return $rta;
    }

    public function Listar($parametro = '') {
        $listaProducto = [];
        
        $query = "SELECT * FROM producto ";
        if ($parametro != '') {
            $query .= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);
        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {
                    $objProducto = new Model_producto();
                    $objProducto->Buscar($row['idproducto']);
                    $listaProducto[] =  $objProducto;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $listaProducto;
    }
}