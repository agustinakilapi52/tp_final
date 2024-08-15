<?php
class Model_compraitem extends BaseDatos
{
    private $idcompraitem;
    private $objProducto; //idproducto
    private $objCompra; //idcompra
    private $cicantidad;
    private $mensajeOperacion;


    public function __construct()
    {
        parent::__construct();
    }

    public function setearValores($idcompraitem, $objProducto, $objCompra, $cicantidad)
    {
        $this->setIdcompraitem($idcompraitem);
        $this->setObjProducto($objProducto);
        $this->setObjCompra($objCompra);
        $this->setcicantidad($cicantidad);
    }

    public function getIdCompraitem()
    {
        return $this->idcompraitem;
    }

    public function setIdCompraitem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }

    public function getObjProducto()
    {
        return $this->objProducto;
    }

    public function setObjProducto($objProducto)
    {
        $this->objProducto = $objProducto;
    }

    public function getObjCompra()
    {
        return $this->objCompra;
    }

    public function setObjCompra($objCompra)
    {
        $this->objCompra = $objCompra;
    }

    public function getCicantidad()
    {
        return $this->cicantidad;
    }

    public function setCicantidad($cicantidad)
    {
        $this->cicantidad = $cicantidad;
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

        $query = "INSERT INTO compraitem(idproducto, idcompra, cicantidad)
                  VALUES ({$this->getObjProducto()->getIdProducto()},{$this->getObjCompra()->getIdCompra()},{$this->getCicantidad()})";

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
        $query = "UPDATE compraitem SET idcompraitem={$this->getIdCompraitem()},idproducto={$this->getObjProducto()->getIdProducto()},idcompra={$this->getObjCompra()->getIdCompra()},cicantidad={$this->getCicantidad()} WHERE idcompraitem ={$this->getIdCompraitem()}";

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

        $query = "DELETE FROM compraitem WHERE idcompraitem= {$this->getIdCompraitem()}";

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

    public function Buscar($idcompraitem)
    {

        $query = "SELECT * FROM compraitem WHERE idcompraitem =" . $idcompraitem;

        $rta = false;

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                if ($row = $this->Registro()) {
                    $objProducto = new Model_producto();
                    $objProducto->Buscar($row['idproducto']);
                    $objCompra = new Model_compra();
                    $objCompra->Buscar($row['idcompra']);

                    $this->setearValores($row['idcompraitem'],$objProducto, $objCompra, $row['cicantidad']);
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
        $listaCompraitem = [];

        $query = "SELECT * FROM compraitem ";
        if ($parametro != '') {
            $query .= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);
        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {
                    $objCompraitem = new Model_compraitem();

                    $objCompraitem->Buscar($row['idcompraitem']);
                    $listaCompraitem[] =  $objCompraitem;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $listaCompraitem;
    }
}
