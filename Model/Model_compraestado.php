<?php

class Model_compraestado extends BaseDatos
{
    private $idcompraestado;
    private $objcompra;
    private $objcompraestadotipo;
    private $cefechaini;
    private $cefechafin;
    private $mensajeOperacion;

    public function __construct()
    {
        parent::__construct();
        $this->mensajeOperacion = "";
    }

    public function setearValores($idcompraestado, $objcompra, $objcompraestadotipo, $cefechaini, $cefechafin)
    {
        $this->setIdcompraestado($idcompraestado);
        $this->setObjcompra($objcompra);
        $this->setObjcompraestadotipo($objcompraestadotipo);
        $this->setCefechaini($cefechaini);
        $this->setCefechafin($cefechafin);
    }

    public function getIdcompraestado()
    {
        return $this->idcompraestado;
    }

    public function setIdcompraestado($idcompraestado)
    {
        $this->idcompraestado = $idcompraestado;
    }

    public function getObjcompra()
    {
        return $this->objcompra;
    }

    public function setObjcompra($objcompra)
    {
        $this->objcompra = $objcompra;
    }

    public function getObjcompraestadotipo()
    {
        return $this->objcompraestadotipo;
    }

    public function setObjcompraestadotipo($objcompraestadotipo)
    {
        $this->objcompraestadotipo = $objcompraestadotipo;
    }

    public function getCefechaini()
    {
        return $this->cefechaini;
    }

    public function setCefechaini($cefechaini)
    {
        $this->cefechaini = $cefechaini;
    }

    public function getCefechafin()
    {
        return $this->cefechafin;
    }
    public function setCefechafin($cefechafin)
    {
        $this->cefechafin = $cefechafin;
    }

    public function getmensajeoperacion()
    {
        return $this->mensajeOperacion;
    }
    public function setMsjOperacion($valor)
    {
        $this->mensajeOperacion = $valor;
    }

    public function Insertar()
    {
        $rta = false;
        // En caso de que la fecha sea NULL, se inserta NULL en la base de datos, sino se inserta la fecha
        $cefechafin = $this->getCefechafin() ? "'{$this->getCefechafin()}'" : "NULL";

        $query = "INSERT INTO compraestado(idcompra, idcompraestadotipo, cefechaini, cefechafin)
                  VALUES ('{$this->getObjcompra()->getIdCompra()}', {$this->getObjcompraestadotipo()->getIdcompraestadotipo()},'{$this->getCefechaini()}',$cefechafin)";

        // echo '<pre>';
        // var_dump($query);
        // echo '</pre>';

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                $rta = true;
            } else {
                $this->setMsjOperacion($this->getError());
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $rta;
    }

    public function Modificar()
    {
        $rta = false;
        $query = "UPDATE compraestado SET idcompraestado='{$this->getIdcompraestado()}',idcompra='{$this->getObjcompra()->getIdCompra()}',idcompraestadotipo='{$this->getObjcompraestadotipo()->getIdcompraestadotipo()}',cefechaini='{$this->getCefechaini()}',cefechafin='{$this->getCefechafin()}'
                 WHERE idcompraestado = {$this->getIdcompraestado()}";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                $rta = true;
            } else {
                $this->setMsjOperacion($this->getError());
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $rta;
    }

    /**
     * Método para eliminar un usuario de la BD
     * (Aún no se eliminaron datos en la BD, no sabemos si funciona)
     */

    public function Eliminar()
    {
        $rta = false;

        $query = "DELETE FROM compraestado WHERE idcompraestado= {$this->getIdcompraestado()}";

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                $rta = true;
            } else {
                $this->setMsjOperacion($this->getError());
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $rta;
    }

    /**
     * Recupera los datos de un usuario a través de su $idcompraestado
     * (Probar si funciona)
     * @param idcompraestado int
     */
    public function Buscar($idcompraestado)
    {

        $query = "SELECT * FROM compraestado WHERE idcompraestado = {$idcompraestado}";

        $rta = false;

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                if ($row = $this->Registro()) {

                    $objcompraestadotipo = new Model_compraestadotipo();
                    $objcompraestadotipo->Buscar($row['idcompraestadotipo']);

                    $objcompra = new Model_compra();
                    $objcompra->Buscar($row['idcompra']);

                    // $objcompraestado->Buscar($row['idcompraestado']);

                    $this->setearValores($row['idcompraestado'],  $objcompra,   $objcompraestadotipo, $row['cefechaini'], $row['cefechafin']);
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

    /**
     * Retorna un arreglo de objetos desde la base de datos de la tabla Usuario
     * @param parametro Es el parámetro que se pasa al método, que es la condición de la consulta.
     * @return An array de objetos.
     * (Probar si funciona)
     */
    public function Listar($parametro = NULL)
    {
        $listacompraestado = array();

        $query = "SELECT * FROM compraestado";
        if ($parametro != '') {
            $query .= ' WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);
        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {

                    // CREO QUE ACA SOLAMENTE LLAMANDO A BUSCAR VA A HACER TODO ESTO QUE TENGO REPETIDO ACA
                    $objcompra = new Model_compra();
                    $objcompra->Buscar($row['idcompra']);

                    $objcompraestadotipo = new Model_compraestadotipo();
                    $objcompraestadotipo->Buscar($row['idcompraestadotipo']);

                    $objcompraestado = new Model_compraestado();

                    $objcompraestado->setearValores($row['idcompraestado'], $objcompra, $objcompraestadotipo, $row['cefechaini'], $row['cefechafin']);
                    array_push($listacompraestado, $objcompraestado);
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $listacompraestado;
    }
}
