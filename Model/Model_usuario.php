<?php

class Model_usuario extends BaseDatos
{
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $ustelefono;
    private $usmail;
    private $imagen_perfil;
    private $usdeshabilitado;
    private $mensajeOperacion;

    public function __construct()
    {
        parent::__construct();
        $this->idusuario = "";
        $this->usnombre = "";
        $this->uspass = "";
        $this->ustelefono = "";
        $this->usmail = "";
        $this->usdeshabilitado = "";
        $this->mensajeOperacion = "";
    }

    public function setearValores($idusuario,$usnombre, $uspass, $ustelefono, $usmail, $imagen_perfil, $usdeshabilitado)
    {
        $this->setIdUsuario($idusuario);
        $this->setUserNombre($usnombre);
        $this->setUserPass($uspass);
        $this->setUserTelefono($ustelefono);
        $this->setUserMail($usmail);
        $this->setImagenPerfil($imagen_perfil);
        $this->setUserDeshabilitado($usdeshabilitado);
    }

    public function getIdUsuario()
    {
        return $this->idusuario;
    }
    public function setIdUsuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    public function getUserNombre()
    {
        return $this->usnombre;
    }
    public function setUserNombre($usnombre)
    {
        $this->usnombre = $usnombre;
    }

    public function getUserPass()
    {
        return $this->uspass;
    }
    public function setUserPass($uspass)
    {
        $this->uspass = $uspass;
    }

    public function getUserTelefono()
    {
        return $this->ustelefono;
    }
    public function setUserTelefono($ustelefono)
    {
        $this->ustelefono = $ustelefono;
    }

    public function getUserMail()
    {
        return $this->usmail;
    }
    public function setUserMail($usmail)
    {
        $this->usmail = $usmail;
    }

    public function getImagenPerfil()
    {
        return $this->imagen_perfil;
    }
    public function setImagenPerfil($imagen_perfil)
    {
        $this->imagen_perfil = $imagen_perfil;
    }

    public function getUserDeshabilitado()
    {
        return $this->usdeshabilitado;
    }
    public function setUserDeshabilitado($usdeshabilitado)
    {
        $this->usdeshabilitado = $usdeshabilitado;
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

        if ($this->getImagenPerfil() == '') {
            $query = "INSERT INTO usuario (usnombre, uspass, ustelefono, usmail) VALUES ('{$this->getUserNombre()}','{$this->getUserPass()}','{$this->getUserTelefono()}','{$this->getUserMail()}')";
            
        } else {
            $query = "INSERT INTO usuario (usnombre, uspass, ustelefono, usmail, imagen_perfil) VALUES ('{$this->getUserNombre()}','{$this->getUserPass()}','{$this->getUserTelefono()}','{$this->getUserMail()}','{$this->getImagenPerfil()}')";
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

    public function Modificar($actualizar_pass = 0)
    {
        $rta = false;
        // En caso de que la fecha sea NULL, se inserta NULL en la base de datos, sino se inserta la fecha
        $usdeshabilitado = $this->getUserDeshabilitado() ? "'{$this->getUserDeshabilitado()}'" : "NULL";
        if ($actualizar_pass) {

            if ($this->getImagenPerfil() == '') {
                $query = "UPDATE usuario SET idusuario='{$this->getIdUsuario()}',usnombre='{$this->getUserNombre()}',uspass='{$this->getUserPass()}',ustelefono='{$this->getUserTelefono()}',usmail='{$this->getUserMail()}',usdeshabilitado=$usdeshabilitado WHERE idusuario = {$this->getIdUsuario()}";
            } else {
                $query = "UPDATE usuario SET idusuario='{$this->getIdUsuario()}',usnombre='{$this->getUserNombre()}',uspass='{$this->getUserPass()}',ustelefono='{$this->getUserTelefono()}',usmail='{$this->getUserMail()}',imagen_perfil='{$this->getImagenPerfil()}',usdeshabilitado=$usdeshabilitado WHERE idusuario = {$this->getIdUsuario()}";
            }

        } else {
            
            if ($this->getImagenPerfil() == '') {
                $query = "UPDATE usuario SET idusuario='{$this->getIdUsuario()}',usnombre='{$this->getUserNombre()}',ustelefono='{$this->getUserTelefono()}',usmail='{$this->getUserMail()}',usdeshabilitado=$usdeshabilitado WHERE idusuario = {$this->getIdUsuario()}";
            } else {
                $query = "UPDATE usuario SET idusuario='{$this->getIdUsuario()}',usnombre='{$this->getUserNombre()}',ustelefono='{$this->getUserTelefono()}',usmail='{$this->getUserMail()}',imagen_perfil='{$this->getImagenPerfil()}',usdeshabilitado=$usdeshabilitado WHERE idusuario = {$this->getIdUsuario()}";
            }

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

        $query = "DELETE FROM usuario WHERE idusuario = {$this->getIdUsuario()}";

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
        $query = "SELECT * FROM usuario WHERE idusuario = {$id_usuario}";
        $rta = false;
        if ($this->Iniciar()) {

            if ($this->Ejecutar($query)) {

                if ($row = $this->Registro()) {
                    $this->setearValores($row['idusuario'], $row['usnombre'], $row['uspass'], $row['ustelefono'], $row['usmail'], $row['imagen_perfil'], $row['usdeshabilitado']);
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
        $lista_usuarios = [];

        $query = "SELECT * FROM usuario ";

        if ($parametro != '') {
            $query.= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);

        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {
                    $objUsuario = new Model_usuario();
                    $objUsuario->Buscar($row['idusuario']);
                    $lista_usuarios[] = $objUsuario;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $lista_usuarios;
    }
}

?>
