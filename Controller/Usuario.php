<?php

class Usuario
{

    public function buscarUsuario($param = NULL)
    {

        $objUsuario = new Model_usuario();
        $where = " true";

        if (isset($param['idusuario'])) {
            $where .= " AND idusuario = " . $param['idusuario'];
        }
        if (isset($param['usnombre'])) {
            $where .= " AND usnombre = '" . $param['usnombre'] . "'";
        }
        if (isset($param['uspass'])) {
            $where .= " AND uspass = '" . $param['uspass'] . "'";
        }
        if (isset($param['usdeshabilitado'])) {
            $where .= " AND usdeshabilitado = " . $param['usdeshabilitado'];
        }

        $usuario = $objUsuario->Listar($where);

        return $usuario;
    }
    public function crearUsuario($datos)
    {
        if (!is_array($datos)) {
            throw new InvalidArgumentException('Se esperaba un array de datos.');
        }
        
        $objUsuario = new Model_usuario(); // Crear una instancia del modelo de usuario
    
        // Verificar si se proporcionaron datos
        if (isset($datos)) {
            // Hashear la contraseña proporcionada por el usuario
            $pass = password_hash($datos['uspass'], PASSWORD_DEFAULT);
    
            // Foto de perfil del usuario
            $fotoperfil = "";
            if (!empty($datos["edit_img"]["name"])) {
                $nombreFoto = time() . '-' . $datos["edit_img"]["name"];
                $target_dir = "../../../uploads/fotosusuario/";
                $target_file = $target_dir . basename($nombreFoto);
                $tipo = $datos['edit_img']['type'];
                $tamano = $datos['edit_img']['size'];
    
                // Verificar si el tipo y tamaño del archivo son válidos
                if ((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 20000000)) {
                    // Crear directorio si no existe
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
    
                    // Mover archivo cargado al directorio de destino
                    if (move_uploaded_file($datos["edit_img"]["tmp_name"], $target_file)) {
                        $fotoperfil = $nombreFoto;
                    } else {
                        echo "Error al subir el archivo.";
                        return; // Salir del método si hay un error de subida
                    }
                } else {
                    echo "Error: Archivo no válido. Asegúrate de subir un archivo JPEG, JPG o PNG menor a 20MB.";
                    return; // Salir del método si hay un error de tipo o tamaño
                }
            }
    
            // Establecer los valores del usuario en el modelo
            $objUsuario->setearValores(null, $datos['usnombre'], $pass, $datos['ustelefono'], $datos['usmail'], $fotoperfil, null);
            
            // Insertar en la base de datos
            $verificacion = $objUsuario->Insertar();
            
            $Rol = new Rol(); 
            $Usuario = new Usuario(); 
            
            $param_user['usnombre'] = $datos['usnombre']; // parámetro de búsqueda para el nombre de usuario verificar q no se repita
    
            foreach ($datos['roles'] as $role) {
                $param_rol['idrol'] = intval($role); // Preparar el parámetro de búsqueda para el rol
                
                // Buscar el usuario recién creado
                $objUsuario = $Usuario->buscarUsuario($param_user);
                
                if (!$objUsuario) {
                    // Manejo de error si no se encuentra el usuario
                    error_log('Usuario no encontrado: ' . $param_user['usnombre']);
                    continue;
                }
            
                // Buscar el rol correspondiente
                $objRol = $Rol->buscarRol($param_rol);
                
                if (!$objRol) {
                    // Manejo de error si no se encuentra el rol
                    error_log('Rol no encontrado: ' . $role);
                    continue;
                }
            
                // Asignar el rol al usuario
                $resultado = $Rol->insertarRolesUsuario($objUsuario, $objRol);
            
                if (!$resultado) {
                    // Manejo de error si la asignación del rol falló
                    error_log('Error al asignar el rol ID ' . $role . ' al usuario ' . $param_user['usnombre']);
                }
            }
    
            // Verificar si la inserción fue exitosa
            if ($verificacion) {
                $resultado['exito'] = true;
                echo json_encode($resultado);
            }
        }
    }
    

    /**
     * Recupera todos los usuarios para mostrarlos en el histórico
     */
    public function getAllUsersHistorico()
    {
        $all_users = $this->buscarUsuario();
        $users = [];
        foreach ($all_users as $value) {
            $users[] = [
                'id_usuario' => $value->getIdUsuario(),
                'imgperfil' => $value->getImagenPerfil(),
                'usnombre' => $value->getUserNombre(),
                'ustelefono' => $value->getUserTelefono(),
                'usmail' => $value->getUserMail(),
                'estado' => $value->getUserDeshabilitado(),
            ];
        }
        return $users;
    }

    /**
     * Recupera los datos del usuario cuando se le haga click en editar. SE USA????
     */
    public function getDataUserEdit($id_usuario)
    {
        $parametros = [];
        $parametros['idusuario'] = $id_usuario;

        $usuario = $this->buscarUsuario($parametros);
        $objRol = new Rol();

        $roles_usuario = $objRol->getAllRolesUser($id_usuario);

        $data_user = [];

        foreach ($usuario as $value) {
            $data_user[] = [
                'id_usuario' => $value->getIdUsuario(),
                'imgperfil' => $value->getImagenPerfil(),
                'usnombre' => $value->getUserNombre(),
                'ustelefono' => $value->getUserTelefono(),
                'usmail' => $value->getUserMail(),
                'roles' => $roles_usuario
            ];
        }

        return $data_user;
    }

    public function actualizarDatosPerfil($data)
    {
        $objUsuario = new Model_usuario();
        $foto = "";
    
        // Validación y carga de la foto de perfil
        if (!empty($data["edit_img"]["name"])) {
            $nombreFoto = time() . '-' . $data["edit_img"]["name"];
            $target_dir = "../../../uploads/fotosusuario/";
            $tipo = $data['edit_img']['type'];
            $tamano = $data['edit_img']['size'];
            $target_file = $target_dir . basename($nombreFoto);
    
            // Validar directorio
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
    
            // Validar tipo y tamaño del archivo
            if (!(strpos($tipo, "jpeg") !== false || strpos($tipo, "jpg") !== false || strpos($tipo, "png") !== false) || $tamano >= 20000000) {
                echo '<script>console.error("Error: Tipo de archivo no permitido o tamaño excede el límite.");</script>';
                return false; // Salir del método si hay un error
            } else {
                if (move_uploaded_file($data["edit_img"]["tmp_name"], $target_file)) {
                    $foto = $nombreFoto;
                } else {
                    echo '<script>console.error("Error al subir el archivo.");</script>';
                    return false; // Salir del método si hay un error
                }
            }
        }
    
        // Validar contraseña
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                echo '<script>console.error("Error: La contraseña debe tener al menos 6 caracteres.");</script>';
                return false; // Salir del método si hay un error
            }
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
    
        // Establecer valores en el objeto usuario
        $objUsuario->setearValores($data['idusuario'], $data['nombre'], $data['password'], $data['telefono'], $data['correo'], $foto, NULL);
    
        // Ejecutar modificación
        if (empty($data['password'])) {
            $verificacion = $objUsuario->Modificar();
        } else {
            $verificacion = $objUsuario->Modificar(1);
        }
    
        return $verificacion;
    }
    

    public function estadoUsuario($datos)
    {
        $objUsuario = new Model_usuario();
        $objUsuario->Buscar($datos['id_usuario']);
    
        if ($datos['estado'] == 1) {
            $t = NULL; // Usuario activado
        } else {
            $t = date("Y-m-d H:i:s"); // Usuario desactivado, fecha actual
        }
    
        $objUsuario->setearValores(
            $objUsuario->getIdUsuario(),
            $objUsuario->getUserNombre(),
            $objUsuario->getUserPass(),
            $objUsuario->getUserTelefono(),
            $objUsuario->getUserMail(),
            '', 
            $t
        );
    
        return $objUsuario->Modificar();
    }
    

    
}
