<?php

class Producto
{

    public function buscarProducto($param = null)
    {
        $objProducto = new Model_producto();
        $where = " true";

        if (isset($param['idproducto'])) {
            $where .= " AND idproducto = " . $param['idproducto'];
        }
        if (isset($param['pronombre'])) {
            $where .= " AND pronombre = '" . $param['pronombre'] . "'";
        }
        if (isset($param['prodetalle'])) {
            $where .= " AND prodetalle = '" . $param['prodetalle'] . "'";
        }
        if (isset($param['procantstock'])) {
            $where .= " AND procantstock = '" . $param['procantstock'] . "'";
        }

        $producto = $objProducto->Listar($where);

        return $producto;
    }

    public function getProductos($condition = ' true')
    {
        $objProducto = new Model_producto();
        return $objProducto->Listar($condition);
    }

public function crearProducto($datos)
{
    // Validar todos los campos requeridos antes de proceder
    if (!empty($datos['pronombre']) && !empty($datos['prodetalle']) && !empty($datos['procantstock']) && !empty($datos['precio'])) {

        $objProducto = new Model_producto();

        // Foto de Producto
        $foto = "";
        if (!empty($datos["edit_img"]["name"])) {
            $nombreFoto = time() . '-' . $datos["edit_img"]["name"];
            $target_dir = "../../../uploads/fotosproductos/";
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
                    $foto = $nombreFoto;
                } else {
                    echo "Error al subir el archivo.";
                    return; // Salir del método si hay un error de subida
                }
            } else {
                echo "Error: Archivo no válido. Asegúrate de subir un archivo JPEG, JPG o PNG menor a 20MB.";
                return; // Salir del método si hay un error de tipo o tamaño
            }
        }

        // Setear valores del producto y realizar la inserción en la base de datos
        $objProducto->setearValores(null, $datos['pronombre'], $datos['prodetalle'], $datos['procantstock'], $foto, $datos['precio']);

        $verificacion = $objProducto->Insertar();

        if ($verificacion) {
            $resultado['exito'] = true;
            echo json_encode($resultado); // Devolver resultado como JSON
        } else {
            echo "Error al insertar el producto.";
        }
    } else {
        echo "Los campos son obligatorios.";
    }
}

    
    public function getProductosHistorico()
    {
        $objProducto = new Model_producto();
        $productos = $objProducto->Listar();
        $all_productos = [];

        foreach ($productos as $value) {
            $all_productos[] = [
                'id_producto' => $value->getIdProducto(),
                'imgproducto' => $value->getUrlImagen(),
                'pronombre' => $value->getPronombre(),
                'prodetalle' => $value->getProdetalle(),
                'procantstock' => $value->getProcantstock(),
                'proprecio' => $value->getPrecio(),
            ];
        }

        return $all_productos;
    }
    
    public function eliminarProducto($id_producto)
    {
        $objProducto = new Model_producto();
        $objProducto->Buscar($id_producto);
        $objProducto->Eliminar();
    }
    
    public function actualizarProducto($data)
    {
        $objProducto = new Model_producto();
        // Foto de Producto
        $foto = "";
        if (!empty($data["edit_img"]["name"])) {
            $nombreFoto = time() . '-' . $data["edit_img"]["name"];
            $target_dir = "../../../uploads/fotosproductos/";
            $tipo = $data['edit_img']['type'];
            $tamano = $data['edit_img']['size'];
            $target_file = $target_dir . basename($nombreFoto);

            if (!file_exists($target_dir)) {
                mkdir('../../../uploads/fotosproductos/', 0777, true);
            }

            if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 20000000))) {
                echo 'error';
            } else {
                move_uploaded_file($data["edit_img"]["tmp_name"], $target_file);
                $foto = $nombreFoto;
            }
        }

        $objProducto->setearValores($data['idproducto'], $data['pronombre'], $data['prodetalle'], $data['procantstock'], $foto, $data['proprecio']);
        $objProducto->Modificar();
    }
}