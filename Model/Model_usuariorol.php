<?php

class Model_usuariorol extends BaseDatos
{
    private $objUsuario; // Objeto Usuario
    private $objRol; // Objeto Rol
    private $mensajeOperacion;

    public function __construct()
    {
        parent::__construct();
        $this->mensajeOperacion = "";
    }

    public function setearValores($objUsuario, $objRol)
    {
        $this->setObjUsuario($objUsuario);
        $this->setObjRol($objRol);
    }

    public function getObjUsuario()
    {
        return $this->objUsuario;
    }
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
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

    public function Insertar()
    {
        $rta = false;

        $query = "INSERT INTO usuariorol(idusuario, idrol)
                  VALUES ('{$this->getObjUsuario()->getIdUsuario()}','{$this->getObjRol()->getIdRol()}')";

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

    public function Eliminar()
    {
        $rta = false;

        $query = "DELETE FROM usuariorol WHERE idusuario={$this->getObjUsuario()->getIdUsuario()} AND idrol={$this->getObjRol()->getIdRol()}";

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
        $query = "UPDATE usuariorol SET idrol='{$this->getObjRol()->getIdRol()} ' WHERE idusuario = {$this->getObjUsuario()->getIdUsuario()}";

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

    public function Buscar($id_usuario)
    {

        $query = "SELECT * FROM usuariorol WHERE idusuario = {$id_usuario}";

        $rta = false;

        if ($this->Iniciar()) {
            if ($this->Ejecutar($query)) {
                if ($row = $this->Registro()) {

                    $objUsuario = new Model_usuario();
                    $objUsuario->Buscar($row['idusuario']);

                    $objRol = new Model_rol();
                    $objRol->Buscar($row['idrol']);

                    $this->setearValores($objUsuario, $objRol);

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
        $rta = false;
        $listaUsuarioRol = [];

        $query = "SELECT * FROM usuariorol ";

        if ($parametro != '') {
            $query .= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);

        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {

                    // Usuario
                    if (!empty($row['idusuario'])) {
                        $objUsuario = new Model_usuario();
                        $objUsuario->Buscar($row['idusuario']);
                    }

                    // Rol
                    if (!empty($row['idrol'])) {
                        $objRol = new Model_rol();
                        $objRol->Buscar($row['idrol']);
                    }

                    $objUsuarioRol = new Model_usuariorol();
                    $objUsuarioRol->setearValores($objUsuario, $objRol);
                    // $objUsuarioRol->Buscar($objUsuario);

                    $listaUsuarioRol[] = $objUsuarioRol;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $listaUsuarioRol;
    }
}
