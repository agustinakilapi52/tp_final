<?php

class Model_producto extends BaseDatos
{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $urlImagen;
    private $precio;
    private $mensajeOperacion;

    public function __construct() {
        parent::__construct();
        $this->idproducto = "";
        $this->pronombre = "";
        $this->prodetalle = "";
        $this->procantstock = "";
        $this->urlImagen = "";
        $this->precio = "";
        $this->mensajeOperacion = "";
    }

    public function setearValores($idproducto, $pronombre, $prodetalle, $procantstock, $urlImagen, $precio) {
        $this->setIdProducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
        $this->setUrlImagen($urlImagen);
        $this->setPrecio($precio);
    }

    public function getIdProducto() {
        return $this->idproducto;
    }
    public function setIdProducto($idproducto) {
        $this->idproducto = $idproducto;
    }

    public function getPronombre() {
        return $this->pronombre;
    }
    public function setPronombre($pronombre) {
        $this->pronombre = $pronombre;
    }

    public function getProdetalle() {
        return $this->prodetalle;
    }
    public function setProdetalle($prodetalle) {
        $this->prodetalle = $prodetalle;
    }

    public function getProcantstock() {
        return $this->procantstock;
    }
    public function setProcantstock($procantstock) {
        $this->procantstock = $procantstock;
    }

    public function getmensajeoperacion() {
        return $this->mensajeOperacion;
    }
    public function setMsjOperacion($valor) {
        $this->mensajeOperacion = $valor;
    }

    public function getUrlImagen() {
        return $this->urlImagen;
    }
    public function setUrlImagen($urlImagen) {
        $this->urlImagen = $urlImagen;
    }

    public function getPrecio() {
        return $this->precio;
    }
    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function Insertar() {
        $rta = false;

        if ($this->getUrlImagen() == '') {
            $query = "INSERT INTO producto(pronombre, prodetalle, procantstock, precio) VALUES ('{$this->getPronombre()}','{$this->getProdetalle()}','{$this->getProcantstock()}','{$this->getPrecio()}')";

        } else {
            $query = "INSERT INTO producto(pronombre, prodetalle, procantstock, urlimg, precio) VALUES ('{$this->getPronombre()}','{$this->getProdetalle()}','{$this->getProcantstock()}','{$this->getUrlImagen()}','{$this->getPrecio()}')";

        }
        
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

    public function Modificar() {

        $rta = false;

        if ($this->getUrlImagen() != '') {
            $query = "UPDATE producto SET pronombre='{$this->getPronombre()}',prodetalle='{$this->getProdetalle()}',procantstock='{$this->getProcantstock()}',urlimg='{$this->getUrlImagen()}',precio='{$this->getPrecio()}' WHERE idproducto = {$this->getIdProducto()}";
        } else {
            $query = "UPDATE producto SET pronombre='{$this->getPronombre()}',prodetalle='{$this->getProdetalle()}',procantstock='{$this->getProcantstock()}',precio='{$this->getPrecio()}' WHERE idproducto = {$this->getIdProducto()}";
        }

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

        $query = "DELETE FROM producto WHERE idproducto = {$this->getIdProducto()}";

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

    public function Buscar($id_producto)
    {
        $query = "SELECT * FROM producto WHERE idproducto = {$id_producto}";
        $rta = false;

        if ($this->Iniciar()) {

            if ($this->Ejecutar($query)) {

                if ($row = $this->Registro()) {
                    $this->setearValores($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['urlimg'], $row['precio']);
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
        $lista_producto = [];

        $query = "SELECT * FROM producto ";

        if ($parametro != '') {
            $query.= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);

        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {
                    $objProducto = new Model_producto();
                    $objProducto->Buscar($row['idproducto']);
                    $lista_producto[] = $objProducto;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $lista_producto;
    }

}