<?php

class Session
{

    public function __construct()
    {
        // Inicia la sesión
        if (!session_start()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Actualiza las variables de sesión con los valores ingresados.
     */
    public function iniciar($usnombre, $uspass)
    {
        $rta = false;
        $usuario = new Usuario();
        $rol = new Rol();
    
        // Busca al usuario en la base de datos por nombre de usuario
        $parametros = ['usnombre' => $usnombre];
        $usuarioLogueado = $usuario->buscarUsuario($parametros);
        
        // Verifica si el usuario existe
        if (!empty($usuarioLogueado)) {
            $usuarioData = $usuarioLogueado[0];
            // Verifica si el usuario no está deshabilitado
            if ($usuarioData->getUserDeshabilitado() == '0000-00-00 00:00:00' || $usuarioData->getUserDeshabilitado() == NULL) {
                // Verifica la contraseña
                if (password_verify($uspass, $usuarioData->getUserPass())) { 
                    // Almacena la información en la sesión
                    $_SESSION['idusuario'] = $usuarioData->getIdUsuario();
                    $_SESSION['usnombre'] = $usuarioData->getUserNombre();
                    $_SESSION['id_roles'] = $rol->getAllRolesUser($usuarioData->getIdUsuario());
                    $rta = true;
                } 
            }
        }
    
        return $rta;
    }
    

    /**
     * Valida si la sesión actual tiene usuario y password válidos
     * @return bool
     */
    public function validar()
    {
        $rta = false;
        if ($this->activa() && isset($_SESSION['idusuario']))
            $rta = true;
        return $rta;
    }

    /**
     * Devuelve un booleano si la sesión está activa o no
     * @return bool
     */
    public function activa()
    {
        $rta = false;

        if (session_status() == PHP_SESSION_ACTIVE) {
            $rta = true;
        }

        return $rta;
    }

    /**
     * Devuelve el usuario logueado
     */
    public function getUsuario()
    {

        if ($this->validar() && $this->activa()) {
            $usuario = new Usuario();
            $listaUsuario = $usuario->buscarUsuario($_SESSION);
            $usuarioLogueado = $listaUsuario[0];
        } else {
            $usuarioLogueado = [];
        }

        return $usuarioLogueado;
    }

    public function getUsuarioRolesLogueado()
    {
        $roles = [];
        if (isset($_SESSION['id_roles'])) {
            $roles = $_SESSION['id_roles'];
        }
        return $roles;
    }

    public function actualizarDataUsuario($datos)
    {
        $rta = false;
        if ($_SESSION['idusuario'] == $datos['idusuario']) {

            $usuario = new Usuario();
            $rol = new Rol();
            $parametros = ['idusuario' => $datos['idusuario'], 'usnombre' => $datos['nombre']];
            $usuario = $usuario->buscarUsuario($parametros);

            if ($usuario) {
                $_SESSION['idusuario'] = $datos['idusuario'];
                $_SESSION['usnombre'] = $usuario[0]->getUserNombre();
                $_SESSION['id_roles'] = $rol->getAllRolesUser($usuario[0]->getIdUsuario());
                $rta = true;
            }
        }
        return $rta;
    }

    /**
     * Destruye la sesión
     */
    public function cerrar()
    {
        $verificacion = false;
        if ($this->activa()) {
            unset($_SESSION['idusuario']);
            unset($_SESSION['usnombre']);
            session_destroy();
            $verificacion = true;
        }
        return $verificacion;
    }
}
