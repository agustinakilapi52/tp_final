<?php
class Menu
{
    public function buscarMenus($param = NULL)
    {
        $objMenu = new Model_menu();
        $where = " true";

        if (isset($param['idmenu'])) {
            $where .= " AND idmenu = " . $param['idmenu'];
        }
        if (isset($param['menombre'])) {
            $where .= " AND menombre = '" . $param['menombre'] . "'";
        }
        if (isset($param['medescripcion'])) {
            $where .= " AND medescripcion = '" . $param['medescripcion'] . "'";
        }
        if (isset($param['idpadre'])) {
            $where .= " AND idpadre = " . $param['idpadre'];
        }
        if (isset($param['medeshabilitado'])) {
            $where .= " AND medeshabilitado = " . $param['medeshabilitado'];
        }

        $menu = $objMenu->Listar($where);

        return $menu;
    }

    public function getRolesParaMenu($id_roles = [])
    {
        $objMenuRol = new Model_menurol();
        $menu = [];
        if (count($id_roles) > 0) {
            // Si el ROL 1 (ADMIN) esta en el arreglo de $id_roles, entonces se le muestran todos los menus
            if (in_array(1, $id_roles)) {
                $where = ' true GROUP BY idmenu';
                $lista_objMenu = $objMenuRol->Listar($where);
                foreach ($lista_objMenu as $l) {
                    if ($l->getObjMenu()->getMeDeshabilitado() == '0000-00-00 00:00:00' || $l->getObjMenu()->getMeDeshabilitado() == NULL) {
                        $menu[] = [
                            'idmenu' => $l->getObjMenu()->getIdMenu(),
                            'nombre' => $l->getObjMenu()->getMeNombre(),
                            'id_padre' => $l->getObjMenu()->getIdPadre(),
                            'descripcion' => $l->getObjMenu()->getMeDescripcion(),
                        ];
                    }
                }
            } else {
                foreach ($id_roles as $id) {
                    $where = 'idrol = ' . $id;
                    $lista_objMenu = $objMenuRol->Listar($where);
                    foreach ($lista_objMenu as $l) {
                        if ($l->getObjMenu()->getMeDeshabilitado() == '0000-00-00 00:00:00' || $l->getObjMenu()->getMeDeshabilitado() == NULL) {
                            $menu[] = [
                                'idmenu' => $l->getObjMenu()->getIdMenu(),
                                'nombre' => $l->getObjMenu()->getMeNombre(),
                                'id_padre' => $l->getObjMenu()->getIdPadre(),
                                'descripcion' => $l->getObjMenu()->getMeDescripcion(),
                            ];
                        }
                    }
                }
            }
        } else {
            $where = 'idrol = ' . 3;
            $lista_objMenu = $objMenuRol->Listar($where);
            foreach ($lista_objMenu as $l) {
                if ($l->getObjMenu()->getMeDeshabilitado() == '0000-00-00 00:00:00' || $l->getObjMenu()->getMeDeshabilitado() == NULL) {
                    $menu[] = [
                        'idmenu' => $l->getObjMenu()->getIdMenu(),
                        'nombre' => $l->getObjMenu()->getMeNombre(),
                        'id_padre' => $l->getObjMenu()->getIdPadre(),
                        'descripcion' => $l->getObjMenu()->getMeDescripcion(),
                    ];
                }
            }
        }

        $resultado = $this->menuArray($menu);
        return $resultado;
    }

    protected function menuArray($menu, $padre = NULL)
    {
        $resultado = [];
        $hijos = [];

        // Itera sobre el arreglo de menús y busca los hijos del menú padre
        for ($i = 0; $i < count($menu); $i++) {
            if ($menu[$i]['id_padre'] == $padre) {
                $hijos[] = $menu[$i];
            }
        }

        // Si se encontraron hijos, se vuelve a llamar a la función para buscar los hijos de los hijos
        if ($hijos) {

            // Itera sobre los hijos encontrados
            for ($i = 0; $i < count($hijos); $i++) {
                $hijo = $hijos[$i];
                $hijo['children'] = [];
                // Llama a la función recursivamente para buscar los hijos del hijo actual
                $arreglo_de_hijos = $this->menuArray($menu, $hijo['idmenu']);
                // Si se encontraron hijos, se agregan al hijo actual
                if ($arreglo_de_hijos) {
                    $hijo['children'] = $arreglo_de_hijos;
                }
                $resultado[] = $hijo;
            }
        } else {
            $resultado = false;
        }

        
        return $resultado;
    }

    public function estructuraMenu($menu) {
        $html = '';
        if (empty($menu)) {
            return $html;
        }
        foreach ($menu as $m) {
            $id_padre = $m['id_padre'];
            $tiene_padre = $id_padre != NULL ? true : false;
            $nombre = $m['nombre'];
            if ($tiene_padre) {
                $html .= '<li><a class="dropdown-item" href="' . base_url($m['descripcion']) . '">' . $nombre . '</a></li>';
            } else {
                if (isset($m['children'])) {
                    $html .= '<div class="dropdown">';
                    $html .= '<button class="btn btn-light dropdown-toggle"  style="background-color:#ffffff; border:0; P-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">' . $nombre . '</button>';
                    $html .= '<ul class="dropdown-menu" style="background-color:#ffffff; ">';
                    $html .= $this->estructuraMenu($m['children']);
                    $html .= '</ul>';
                    $html .= '</div>';
                } else {
                    $html .= '<li><a class="dropdown-item" href="' . base_url($m['descripcion']) . '">' . $nombre . '</a></li>';
                }
            }
        }
        return $html;
    }

    
 
    public function getAllMenus()
    {
        $objMenu = new Model_menu();
        $menus = $objMenu->Listar();
        return $menus;
    }

    public function getMenusHistorico()
    {
        $listaMenus = $this->buscarMenus();
        $menus = [];
        foreach ($listaMenus as $value) {

            if ($value->getIdPadre() == NULL) {
                $menus[] = [
                    'idmenu' => $value->getIdMenu(),
                    'menombre' => $value->getMeNombre(),
                    'medescripcion' => $value->getMeDescripcion(),
                    'menu_padre' => '-',
                    'estado' => $value->getMeDeshabilitado(),
                ];
            } else {

                $param['idmenu'] = $value->getIdPadre();
                $menu = $this->buscarMenus($param);

                $menus[] = [
                    'idmenu' => $value->getIdMenu(),
                    'menombre' => $value->getMeNombre(),
                    'medescripcion' => $value->getMeDescripcion(),
                    'menu_padre' => $menu[0]->getMeNombre(),
                    'estado' => $value->getMeDeshabilitado(),
                ];
            }
        }

        return $menus;
    }

public function getDataMenuEdit($id_menu)
{
        $parametros = [];
        $parametros['idmenu'] = $id_menu;

        $menues = $this->buscarMenus($parametros);
        $objRol = new Rol();

        $roles_menu = $objRol->getAllRolesMenu($id_menu);

        $data_menu = [];
        foreach ($menues as $value) {
            $data_menu[] = [
                'idmenu' => $value->getIdMenu(),
                'menombre' => $value->getMeNombre(),
                'medescripcion' => $value->getMeDescripcion(),
                'id_padre' => $value->getIdPadre(),
                'roles' => $roles_menu
            ];
        }

        return $data_menu;
    }


    public function actualizarDatosMenu($data)
    {

        $objModelMenu = new Model_menu();

        if ($data['menombre'] != '') {
            if ($data['medescripcion'] != '') {

                $objRol = new Rol();
                $objMenu = new Menu();
                $roles_menu_actual = $objRol->getAllRolesMenu($data['idmenu']);

                $param = [];
                $param['idmenu'] = $data['idmenu'];
                $obj_menu = $objMenu->buscarMenus($param);
                $objMenuRol = new Model_menurol();

                $objModelMenu->setearValores($data['idmenu'], $data['menombre'], $data['medescripcion'], $data['idpadre'], -1);

                $objModelMenu->Modificar();

                if (isset($data['roles']) && is_array($data['roles'])) {
                    $roles_eliminar = array_diff($roles_menu_actual, $data['roles']);
                    $roles_agregar = array_diff($data['roles'], $roles_menu_actual);


                    $param = [];

                    // Si existen roles para eliminar, entonces los elimina
                    if ($roles_eliminar > 0) {
                        foreach ($roles_eliminar as $r_del) {
                            $param['idrol'] = $r_del;
                            $obj_rol = $objRol->buscarRol($param);
                            $objMenuRol->setearValores($obj_menu[0], $obj_rol[0]);
                            $objMenuRol->Eliminar();
                        }
                    }

                    // Si existen roles para agregar, entonces los inserta
                    if ($roles_agregar > 0) {
                        foreach ($roles_agregar as $r_add) {
                            $param['idrol'] = $r_add;
                            $obj_rol = $objRol->buscarRol($param);
                            $objMenuRol->setearValores($obj_menu[0], $obj_rol[0]);
                            $objMenuRol->Insertar();
                        }
                    }
                }
            } else {
                $resultado['errors'][] = ['¡La ruta es obligatoria!'];
                http_response_code(400);
                echo json_encode($resultado['errors']);
            }
        } else {
            $resultado['errors'][] = ['¡El nombre del menú es obligatorio!'];
            http_response_code(400);
            echo json_encode($resultado['errors']);
        }
    }

 public function crearMenu($datos) {
        $objMenu = new Model_menu();
        
        if (isset($datos)) {
            $objMenu->setearValores(null, $datos['menombre'], $datos['medescripcion'], $datos['idpadre'], null);
    
            $verificacion = $objMenu->Insertar();
    
            if ($verificacion) {
                $Rol = new Rol();
                $Menu = new Menu();
    
                $param_menu['menombre'] = $datos['menombre'];
                $menus = $Menu->buscarMenus($param_menu);
    
                if (empty($menus)) {
                    return $this->responseError('No se encontró el menú');
                }
    
                $obj_menu = $menus[0];
    
                foreach ($datos['roles'] as $role) {
                    $param_rol['idrol'] = intval($role);
                    $roles = $Rol->buscarRol($param_rol);
    
                    if (empty($roles)) {
                        error_log('Rol no encontrado: ' . $role);
                        continue;
                    }
    
                    $obj_rol = $roles[0];
                    $resultado = $Rol->insertarRolesMenu($obj_menu, $obj_rol);
    
                    if (!$resultado) {
                        error_log('Error al asignar el rol ID ' . $role . ' al menú ' . $param_menu['menombre']);
                    }
                }
    
                $resultado = ['exito' => true];
                echo json_encode($resultado);
            } else {
                return $this->responseError('¡Error al insertar el menú!');
            }
        }
    }
    


private function responseError($message) {
    http_response_code(400);
    echo json_encode(['error' => $message]);
    exit();
}

public function estadoMenu($datos)
{
    $objMenu = new Model_menu();
    $objMenu->Buscar($datos['idmenu']); 

    // Verifica si el estado es deshabilitado
    if ($datos['estado'] == 1) {
        $t = NULL; // Menú activado, sin fecha de deshabilitación
    } else {
        $t = date("Y-m-d H:i:s"); // Menú desactivado, fecha actual
    }

    $objMenu->setearValores(
        $objMenu->getIdMenu(),
        $objMenu->getMeNombre(),
        $objMenu->getMeDescripcion(),
        $objMenu->getIdPadre(),
        '', 
        $t 
    );

    return $objMenu->Modificar(); 
}


}
