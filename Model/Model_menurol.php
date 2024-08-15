<?php

class Model_menurol extends BaseDatos
{
    private $objMenu;
    private $objRol;
    private $mensajeOperacion;

    public function __construct()
    {
        parent::__construct();
        $this->mensajeOperacion = "";

    }

    public function setearValores($objMenu, $objRol) {
        $this->objMenu = $objMenu;
        $this->objRol = $objRol;
    }

    public function getObjMenu()
    {
        return $this->objMenu;
    }
    public function setObjMenu($objMenu)
    {
        $this->objMenu = $objMenu;
    }

    public function getObjRol()
    {
        return $this->objRol;
    }
    public function setObjRol($objRol)
    {
        $this->objRol = $objRol;
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
    
        $query = "INSERT INTO menurol(idmenu, idrol)
                  VALUES ('{$this->getObjMenu()->getIdMenu()}', '{$this->getObjRol()->getIdRol()}')";
    
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

        $query = "UPDATE menurol SET idmenu='{$this->getObjMenu()->getIdMenu()}',idrol='{$this->getObjRol()->getIdRol()}' WHERE idmenu = {$this->getObjMenu()->getIdMenu()}";

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

        $query = "DELETE FROM menurol WHERE idmenu = {$this->getObjMenu()->getIdMenu()} AND idrol={$this->getObjRol()->getIdRol()}";

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

    public function Buscar($id_menu)
    {
        $query = "SELECT * FROM menurol WHERE idmenu = {$id_menu}";
        $rta = false;

        if ($this->Iniciar()) {

            if ($this->Ejecutar($query)) {

                if ($row = $this->Registro()) {

                    $objMenu = new Model_menu();
                    $objMenu->Buscar($row['idmenu']);

                    $objRol = new Model_rol();
                    $objRol->Buscar($row['idrol']);

                    $this->setearValores($objMenu, $objRol);
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
        $listaMenuRol = [];
        $query = "SELECT * FROM menurol ";
        if ($parametro != '') {
            $query.= 'WHERE ' . $parametro;
        }
        $rta = $this->Ejecutar($query);
        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {

                    if ($row['idmenu'] != NULL) {
                        $objMenu = new Model_menu();
                        $objMenu->Buscar($row['idmenu']);
                    }

                    if ($row['idrol'] != NULL) {
                        $objRol = new Model_rol();
                        $objRol->Buscar($row['idrol']);
                    }

                    $objMenuRol = new Model_menurol();
                    $objMenuRol->setearValores($objMenu, $objRol);

                    $listaMenuRol[] = $objMenuRol;

                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $listaMenuRol;
    }
}