<?php

class Model_compra extends BaseDatos
{
    private $idcompra;
    private $cofecha;
    private $objUsuario;
    private $mensajeOperacion;

    public function __construct()
    {
        parent::__construct();
        $this->mensajeOperacion = "";
    }

    public function setearValores($idcompra, $cofecha, $objUsuario)
    {
        $this->setIdCompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setObjUsuario($objUsuario);
    }

    public function getIdCompra()
    {
        return $this->idcompra;
    }
    public function setIdCompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }

    public function getCofecha()
    {
        return $this->cofecha;
    }
    public function setCofecha($cofecha)
    {
        $this->cofecha = $cofecha;
    }

    public function getObjUsuario()
    {
        return $this->objUsuario;
    }
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
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

        $query = "INSERT INTO compra(cofecha, idusuario) VALUES ('{$this->getCofecha()}', {$this->getObjUsuario()->getIdUsuario()})";

        if ($this->Iniciar()) {
            $id = $this->Ejecutar($query);
            if ($id) {
                $rta = true;
            } else {
                $this->setMsjOperacion($this->getError());
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $id; // ¿Estará bien esto que hice?
    }

    public function Modificar()
    {
        $rta = false;
        $query = "UPDATE compra SET cofecha='{$this->getCofecha()}',idusuario='{$this->getObjUsuario()->getIdUsuario()}'
                 WHERE idcompra ={$this->getIdCompra()}";

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

    public function Eliminar()
    {
        $rta = false;

        $query = "DELETE FROM compra WHERE idcompra = {$this->getIdCompra()}";
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

    public function Buscar($idcompra)
    {
        $query = "SELECT * FROM compra WHERE idcompra =" . $idcompra;

        $rta = false;

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                if ($row = $this->Registro()) {
                    $objUsuario = new Model_usuario();
                    $objUsuario->Buscar($row['idusuario']);

                    $this->setearValores($row['idcompra'], $row['cofecha'], $objUsuario);
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

    public function Listar($parametro = '')
    {
        $listaCompra = [];

        $query = "SELECT * FROM compra ";
        if ($parametro != '') {
            $query.= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);
        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {
                    $objCompra = new Model_compra();
                    $objCompra->Buscar($row['idcompra']);
                    $listaCompra[] = $objCompra;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $listaCompra;
    }
}
