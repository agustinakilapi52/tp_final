<?php 

class Rol {

    public function buscarRol($param = NULL)
    {

        $objRol = new Model_rol();
        $where = " true";

        if (isset($param['idrol'])) {
            $where .= " AND idrol = " . $param['idrol'];
        }
        if (isset($param['rodescripcion'])) {
            $where .= " AND rodescripcion = '" . $param['rodescripcion'] . "'";
        }

        $rol = $objRol->Listar($where);

        return $rol;
    }

    public function getRoles() {
        $objRol = new Model_rol();
        return $objRol->Listar();
    }

    public function getAllRolesUser($id_usuario) {
        // Este método se podría usar en Session.php cuando se está iniciando sesión
        // Para que se almacenen los grupos en una supervariable global $_SESSION
        $id_roles = [];
        $where = 'idusuario = ' . $id_usuario;
        $objUserRol = new Model_usuariorol();
        $lista_objs = $objUserRol->Listar($where);

        foreach ($lista_objs as $l) {
            $id_roles[] = $l->getObjRol()->getIdRol();
        }

        return $id_roles;
    }

    public function getAllRolesMenu($id_menu) {
        $id_roles = [];
        $where = 'idmenu = ' . $id_menu;
        $objMenuRol = new Model_menurol();
        $lista_objs = $objMenuRol->Listar($where);

        foreach ($lista_objs as $l) {
            $id_roles[] = $l->getObjRol()->getIdRol();
        }

        return $id_roles;
    }
/*pruebaaa
    public function insertarRolesUsuario($objUsuario, $objRol) {
        $roles_user = new Model_usuariorol();
        $roles_user->setearValores($objUsuario[0], $objRol[0]);
        $verificacion = $roles_user->Insertar();

        return $verificacion;
    }
*/
    public function insertarRolesUsuario($objUsuario, $objRol) {
        if (empty($objUsuario) || empty($objRol)) {
            throw new InvalidArgumentException('Usuario y Rol no pueden estar vacíos.');
        }

        $roles_user = new Model_UsuarioRol(); // Asegúrate de que el nombre del modelo sigue el formato de CamelCase
        $roles_user->setearValores($objUsuario[0], $objRol[0]);
        
        try {
            $verificacion = $roles_user->insertar(); // Método 'insertar' en minúscula por consistencia
        } catch (Exception $e) {
            // Manejo de la excepción
            error_log('Error al insertar el rol del usuario: ' . $e->getMessage());
            return false;
        }

        return $verificacion;
    }

    public function insertarRolesMenu($objMenu, $objRol) {
        if (!($objMenu instanceof Model_menu) || !($objRol instanceof Model_rol)) {
            throw new InvalidArgumentException('Se espera un objeto Model_menu y un objeto Model_rol.');
        }
    
        $roles_menu = new Model_menurol();
        $roles_menu->setearValores($objMenu, $objRol);
    
        try {
            $verificacion = $roles_menu->Insertar();
        } catch (Exception $e) {
            // Manejo de la excepción
            error_log('Error al insertar el rol del menú: ' . $e->getMessage());
            return false;
        }
    
        return $verificacion;
    }
    
    
}

?>