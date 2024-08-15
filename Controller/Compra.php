<?php

class Compra
{

    public function buscarCompra($param = NULL)
    {
        $objCompra = new Model_compra();
        $where = " true";

        if (isset($param['idcompra'])) {
            $where .= " AND idcompra = " . $param['idcompra'];
        }
        if (isset($param['idusuario'])) {
            $where .= " AND idusuario = " . $param['idusuario'];
        }

        $compra = $objCompra->Listar($where);
        return $compra;
    }

    /**
     * Inicia la compra de un producto con su estado en iniciado
     */
    public function iniciarCompra($datos)
    {

        $obj_usuario = new Model_usuario();
        $obj_compra = new Model_compra();
        $obj_compraestado = new Model_compraestado();
        $obj_compraestadotipo = new Model_compraestadotipo();
        $obj_usuario->Buscar($datos['idusuario']);

        // La fecha donde se inicia la compra
        $t = time();
        $t = (date("Y-m-d H:i:s", $t));

        $obj_producto = new Model_producto();
        $obj_producto->Buscar($datos['id_producto']);

        // Antes de insertar, tengo que verificar que el producto solicitado no supere el stock
        if ($obj_producto->getProcantstock() >= 1) {
            $id_compra = $this->buscarCompraCarritoUsuario($datos['idusuario']);

            // Si la compra no existe, entonces la creamos
            if ($id_compra == '') {
                $t = time();
                $t = (date("Y-m-d H:i:s", $t));
                $obj_compra->setearValores(null, $t, $obj_usuario);
                $id_compra = $obj_compra->Insertar(); // Obtengo el #ID de la compra después de insertar

                $obj_compra->Buscar($id_compra);
                $obj_compraestadotipo->Buscar(1);
                $obj_compraestado->setearValores(null, $obj_compra, $obj_compraestadotipo, $t, null);
                $obj_compraestado->Insertar();
            } else { // Caso contrario, busco la compra existente
                $obj_compra->Buscar($id_compra);
            }

            $obj_compraitem = new Model_compraitem();

            // En caso de existir ese producto ya en el carrito, incrementamos su cantidad solamente, no se agrega de nuevo
            $param_compraitems = [];
            $param_compraitems['idproducto'] = $obj_producto->getIdProducto();
            $param_compraitems['idcompra'] = $id_compra;
            $compraitem = $this->buscarCompraItem($param_compraitems);

            // Modifico el stock del producto cuando se realiza una compra
            $cantidad = $obj_producto->getProcantstock() - 1;
            $obj_producto->setearValores($obj_producto->getIdProducto(), $obj_producto->getPronombre(), $obj_producto->getProdetalle(), $cantidad, $obj_producto->getUrlImagen(), $obj_producto->getPrecio());
            $obj_producto->Modificar();
            $obj_producto->Buscar($datos['id_producto']);

            if ($compraitem) { // Si ya existe una compraitem
                $new_cantidad = $compraitem[0]->getCicantidad() + 1;
                $obj_compraitem->setearValores($compraitem[0]->getIdCompraitem(), $compraitem[0]->getObjProducto(), $compraitem[0]->getObjCompra(), $new_cantidad);
                $obj_compraitem->Modificar();
            } else { // En caso de no existir esa compra, agrega uno nuevo.
                $obj_compraitem->setearValores(null, $obj_producto, $obj_compra, 1);
                $obj_compraitem->Insertar();
            }

            $resultado['exito'] = true;
        } else {
            $resultado['exito'] = false;
            $resultado['errors'][] = ['¡La cantidad supera el stock!'];
            http_response_code(400);
            echo json_encode($resultado['errors']);
        }
        return $resultado;
    }



    /**
     * Buscar estados de compra
     */
    public function buscarCompraEstado($param = NULL)
    {
        $obj_compraestado = new Model_compraestado();
        $where = " true";

        if (isset($param['idcompraestado'])) {
            $where .= " AND idcompraestado = " . $param['idcompraestado'];
        }
        if (isset($param['idcompra'])) {
            $where .= " AND idcompra = " . $param['idcompra'];
        }
        if (isset($param['idcompraestadotipo'])) {
            $where .= " AND idcompraestadotipo = " . $param['idcompraestadotipo'];
        }
        $compra_estado = $obj_compraestado->Listar($where);
        return $compra_estado;
    }

    /**
     * Busco el item de compra por id_compra y id_producto
     */
    public function buscarCompraItem($param = NULL)
    {
        $obj_compraitem = new Model_compraitem();
        $where = " true";

        if (isset($param['idcompraitem'])) {
            $where .= " AND idcompraitem = " . $param['idcompraitem'];
        }
        if (isset($param['idproducto'])) {
            $where .= " AND idproducto = " . $param['idproducto'];
        }
        if (isset($param['idcompra'])) {
            $where .= " AND idcompra = " . $param['idcompra'];
        }
        $compra_items = $obj_compraitem->Listar($where);
        return $compra_items;
    }


    /**
     * Busco el carrito de compras de un usuario específico
     */
    public function buscarCompraCarritoUsuario($id_usuario)
    {
        $where = " idusuario =" . $id_usuario;

        $where .= " AND idcompra IN (SELECT idcompra FROM compraestado WHERE idcompraestadotipo = 1 AND cefechafin IS NULL)";

        $obj_compra = new Model_compra();
        $compras = $obj_compra->Listar($where);

        if ($compras != null) {
            return $compras[0]->getIdCompra();
        } else {
            return '';
        }
    }

    /**
     * Busco todos los productos del carrito de un usuario
     */
    public function getItemsCarrito($id_usuario)
    {
        $array_productos = [];
        $listaCompraItems = [];
        $id_compra = $this->buscarCompraCarritoUsuario($id_usuario);
        if ($id_compra != '') {
            $param['idcompra'] = $id_compra;
            $listaCompraItems = $this->buscarCompraItem($param);
        }

        if (count($listaCompraItems) > 0) {
            foreach ($listaCompraItems as $value) {
                $array_productos[] = [
                    'id_compra' => $value->getObjCompra()->getIdCompra(),
                    'id_compraitem' => $value->getIdCompraitem(),
                    'id_producto' => $value->getObjProducto()->getIdProducto(),
                    'img_producto' => $value->getObjProducto()->getUrlImagen(),
                    'pronombre' => $value->getObjProducto()->getPronombre(),
                    'pronombre' => $value->getObjProducto()->getPronombre(),
                    'proprecio' => $value->getObjProducto()->getPrecio(),
                    'prodetalle' => $value->getObjProducto()->getProdetalle(),
                    'cantidad' => $value->getCicantidad(),
                ];
            }
        }

        return $array_productos;
    }

    /**
     * Modificar la cantidad de productos en el carrito
     */
    public function modificarCantProductosCarrito($datos)
    {
        $param = [];
        $objCompraItem = new Model_compraitem();
        $objProducto = new Model_producto();

        $param['idcompraitem'] = $datos['id_compra_item'];
        $compraitem = $this->buscarCompraItem($param);
        $objProducto->Buscar($datos['id_producto']);
        $cantidad = $objProducto->getProcantstock();

        if ($datos['tipo'] == 1) { // Aumenta un producto
            // Antes de modificar la cantidad, tengo que verificar que el producto solicitado no supere el stock
            if ($objProducto->getProcantstock() >= 1) {
                echo '<pre>';
                var_dump($datos);
                echo '</pre>';
                $cantidad = $objProducto->getProcantstock() - 1;
                $objCompraItem->setearValores($compraitem[0]->getIdCompraitem(), $compraitem[0]->getObjProducto(), $compraitem[0]->getObjCompra(), $datos['cantidad']);

                $objProducto->setearValores($objProducto->getIdProducto(), $objProducto->getPronombre(), $objProducto->getProdetalle(), $cantidad, $objProducto->getUrlImagen(), $objProducto->getPrecio());
                $objProducto->Modificar();
                $objCompraItem->Modificar();

                // Si la cantidad llega a 0 del carrito, elimina ese producto del carrito
                // if ($datos['cantidad'] == 0) {
                //     $objCompraItem->Eliminar();
                // } else {
                //     $objCompraItem->Modificar();
                // }

                // $listaCompraItems = $this->traerProductosAlCarrito($datos['id_usuario']);

                // if (count($listaCompraItems) == 0) {
                //     $this->eliminarCompraCarrito($datos['id_usuario']);
                // }
            } else {
                $resultado['errors'][] = ['¡La cantidad supera el stock!'];
                http_response_code(400);
                echo json_encode($resultado['errors']);
            }
        } else if ($datos['tipo'] == 0) { // Resta un producto
            if ($objProducto->getProcantstock() >= 0) {
                $cantidad = $objProducto->getProcantstock() + 1;
                $objCompraItem->setearValores($compraitem[0]->getIdCompraitem(), $compraitem[0]->getObjProducto(), $compraitem[0]->getObjCompra(), $datos['cantidad']);
                $objProducto->setearValores($objProducto->getIdProducto(), $objProducto->getPronombre(), $objProducto->getProdetalle(), $cantidad, $objProducto->getUrlImagen(), $objProducto->getPrecio());
                $objProducto->Modificar();
                // Si la cantidad llega a 0 del carrito, elimina ese producto del carrito

                if ($datos['cantidad'] == 0) {
                    $objCompraItem->Eliminar();
                } else {
                    $objCompraItem->Modificar();
                }

                $listaCompraItems = $this->traerProductosAlCarrito($datos['id_usuario']);

                if (count($listaCompraItems) == 0) {
                    $this->eliminarCompraCarrito($datos['id_usuario']);
                }
            }
        } else if ($datos['tipo'] == 2) { // Elimina un producto
            $cantidad = $objProducto->getProcantstock() + $datos['cantidad'];
            $objProducto->setearValores($objProducto->getIdProducto(), $objProducto->getPronombre(), $objProducto->getProdetalle(), $cantidad, $objProducto->getUrlImagen(), $objProducto->getPrecio());
            $objCompraItem->Eliminar();
        } else {
            $resultado['errors'][] = ['¡Error al modificar la cantidad!'];
            http_response_code(400);
            echo json_encode($resultado['errors']);
        }

        return $objCompraItem;
    }

    /**
     * Trae los productos al carrito con el id_usuario pasado por parámetro
     */
    public function traerProductosAlCarrito($id_usuario)
    {
        $listaCompraItems = [];
        $id_compra = $this->buscarCompraCarritoUsuario($id_usuario);
        if ($id_compra != '') {
            $param['idcompra'] = $id_compra;
            $listaCompraItems = $this->buscarCompraItem($param);
        }
        return $listaCompraItems;
    }

    private function eliminarCompraCarrito($id_usuario)
    {
        $id_compra = $this->buscarCompraCarritoUsuario($id_usuario);

        $param['idcompra'] = $id_compra;
        $objCompraEstado = $this->buscarCompraEstado($param);

        $id_compraestado = $objCompraEstado[0]->getIdcompraestado();
        $obj_compraestado = new Model_compraestado();
        $obj_compraestado->Buscar($id_compraestado);
        $obj_compraestado->Eliminar();

        $objCompra = new Model_compra();
        $objCompra->Buscar($id_compra);
        $objCompra->Eliminar();
    }

    /**
     * Finaliza la compra cuando se da 'submit' al botón de finalizar compra en el carrito.
     */
    public function finalizarCompra($id_compra)
    {
        $parametros = [];
        $rta = [];
        $parametros['idcompra'] = $id_compra;
        $parametros['idcompraestadotipo'] = 1;

        $compra_estado = $this->buscarCompraEstado($parametros);

        foreach ($compra_estado as $value) {
            if ($value->getObjcompraestadotipo()->getIdCompraestadotipo() == 1) {
                // #1 Este registro con este ID de la compraestado va a setearse su 'cefechafin'
                $id_compraestado = $value->getIdcompraestado();
                // #2 Capturo el ID de la compra, con eso busco el usuario para poder tener su E-Mail
                $idcompra_realizada = $value->getObjcompra()->getIdCompra();
            }
        }

        $compra_realizada = $this->buscarCompra($idcompra_realizada);

        if (count($compra_realizada) > 0) {
            // El ID del usuario quien realizó la compra
            $id_usuario = $compra_realizada[0]->getObjUsuario()->getIdUsuario();
            $parametros = [];
            $parametros['idusuario'] = $id_usuario;

            $objUsuario = new Usuario();
            $usuario = $objUsuario->buscarUsuario($parametros);

            // #3 Finaliza la compra con su estado 1 agregando su fecha fin
            $this->finalizarCompraEstado($id_compraestado);

            // #4 Creo otro registro en 'compraestado' con el tipo de estado en 2
            $this->crearRegistroCompraEstado($idcompra_realizada, 2);

            enviarCorreo($usuario[0]->getUserMail(), 'Aceptado');

            $rta = $idcompra_realizada;
        } else {
            $rta['errors'][] = ['¡Ha surgido un error!'];
            http_response_code(400);
        }
        return $rta;
    }

    /**
     * Le coloca fecha fin a un registro de la 'compraestado'
     */
    public function finalizarCompraEstado($id_compraestado)
    {
        $t = time();
        $t = (date("Y-m-d H:i:s", $t));
        $objCompraEstado = new Model_compraestado();
        $objCompraEstado->Buscar($id_compraestado);

        $objCompraEstado->setearValores($objCompraEstado->getIdcompraestado(), $objCompraEstado->getObjcompra(), $objCompraEstado->getObjcompraestadotipo(), $objCompraEstado->getCefechaini(), $t);

        $objCompraEstado->Modificar();
    }

    /**
     * Crea un nuevo registro en 'compraestado'
     */
    public function crearRegistroCompraEstado($id_compra, $id_compraestadotipo)
    {
        $t = time();
        $t = (date("Y-m-d H:i:s", $t));

        $objCompraEstado = new Model_compraestado();
        $objCompra = new Model_compra();
        $objCompraEstadoTipo = new Model_compraestadotipo();

        $objCompra->Buscar($id_compra);
        $objCompraEstadoTipo->Buscar($id_compraestadotipo);

        if ($id_compraestadotipo == 3 || $id_compraestadotipo == 4) {
            $objCompraEstado->setearValores(null, $objCompra, $objCompraEstadoTipo, $t, $t);
        } else {
            $objCompraEstado->setearValores(null, $objCompra, $objCompraEstadoTipo, $t, null);
        }

        $objCompraEstado->Insertar();
    }

    /**
     * Busca los últimos estados de cada compra y los retorna a la tabla de todas las compras
     */
    public function getLastEstadoCompra()
    {

        $parametros = [];
        $compras = [];
        $listaCompraEstados = $this->buscarCompraEstado();
        $objUsuario = new Usuario();

        foreach ($listaCompraEstados as $compra_estados) {

            $parametros['idcompra'] = $compra_estados->getObjCompra()->getIdCompra();

            $estadoCompra = $this->buscarUltimoEstadoCompra($parametros);
            $parametros = [];

            foreach ($estadoCompra as $value) {
                $parametros = [];
                $parametros['idcompra'] = $value->getObjCompra()->getIdCompra();
                $compra = $this->buscarCompra($parametros);

                $parametros = [];
                if ($estadoCompra != null)
                    $parametros['idusuario'] = $compra[0]->getObjUsuario()->getIdUsuario();
                $usuario = $objUsuario->buscarUsuario($parametros);

                $compras[] = [
                    'id_compra' => $value->getObjCompra()->getIdCompra(),
                    'cliente' => $usuario[0]->getUserNombre(),
                    'estado' => $value->getObjcompraestadotipo()->getIdCompraestadotipo(),
                ];
            }
        }

        $compras = array_unique($compras, SORT_REGULAR);

        return $compras;
    }

    protected function buscarUltimoEstadoCompra($param)
    {
        $objCompraEstado = new Model_compraestado();
        $where = " true";

        if (isset($param['idcompra'])) {
            $where .= " AND idcompra = " . $param['idcompra'] . " AND idcompraestado != 1 ORDER BY idcompraestadotipo DESC LIMIT 1";
        }

        $compra_estado = $objCompraEstado->Listar($where);
        return $compra_estado;
    }

    /**
     * Trae todos los productos de una orden de compra
     */
    public function traerProductosDeOrden($id_orden)
    {

        $param['idcompra'] = $id_orden;
        $listaCompraItems = [];

        $listaCompraItems['compra_items'] = $this->buscarCompraItem($param);
        $listaCompraItems['estado_compra'] = 2;

        $compra_estado = $this->buscarCompraEstado($param);

        $listaCompraItems['all_estados'] = $compra_estado;

        foreach ($compra_estado as $value) {
            if ($value->getObjcompraestadotipo()->getIdCompraestadotipo() > 2) {
                $listaCompraItems['estado_compra'] = $value->getObjcompraestadotipo()->getIdCompraestadotipo();
            }
        }

        return $listaCompraItems;
    }

    /**
     * Cambia el estado de la compra, si acepta o cancela
     */
    public function changeStateCompra($id_compra, $tipo)
    {
        $parametros = [];
        $parametros['idcompra'] = $id_compra;
        $compra = $this->buscarCompra($parametros);
        $parametros = [];

        $parametros['idcompraestadotipo'] = 2;
        $compra_estado = $this->buscarCompraEstado($parametros);

        $id_compraestado = $compra_estado[0]->getIdcompraestado();
        $this->finalizarCompraEstado($id_compraestado);

        $correo_usuario = $compra[0]->getObjUsuario()->getUserMail();

        if ($tipo == 1) { // Envio la compra
            $this->crearRegistroCompraEstado($id_compra, 3);
            enviarCorreo($correo_usuario, 'Enviado');
        } else { // Cancela la compra
            $this->crearRegistroCompraEstado($id_compra, 4);
            $this->returnProductosAlStock($id_compra);
            enviarCorreo($correo_usuario, 'Cancelado');
        }
        return true;
    }

    /**
     * Devuelve todos los productos relacionado a esa compra en caso
     * de que la misma se haya cancelado
     */
    public function returnProductosAlStock($id_compra)
    {
        $obj_CompraItem = new Model_compraitem();
        $objProducto = new Model_producto();
        $compra_items = $obj_CompraItem->Listar('idcompra =' . $id_compra);

        foreach ($compra_items as $value) {
            $id_producto = $value->getObjProducto()->getIdProducto();
            $producto = $objProducto->Listar('idproducto = ' . $id_producto);

            $cantidad_producto = intval($producto[0]->getProcantstock());
            $nuevo_stock = $cantidad_producto + $value->getCicantidad();

            $objProducto->setearValores($producto[0]->getIdProducto(), $producto[0]->getPronombre(), $producto[0]->getProdetalle(), $nuevo_stock, $producto[0]->getUrlImagen(), $producto[0]->getPrecio());
            $objProducto->Modificar();
        }
    }

    /**
     * Buscar ordenes de compras realizadas por el usuario para mostrarlo en miperfil.php
     */
    public function buscarOrdenesDeCompra($id_usuario)
    {
        #1 Primero, busco todas aquellas compras que fueron realizadas por el usuario
        $param['idusuario'] = $id_usuario;
        $compras_usuario = $this->buscarCompra($param);
        $ordenes = [];

        foreach ($compras_usuario as $value) {

            $id_compra = $value->getIdCompra();
            $param['idcompra'] = $id_compra;
            $compra_estado = $this->buscarCompraEstado($param);

            foreach ($compra_estado as $c_estado) {

                $id_compraestado_tipo = $c_estado->getObjcompraestadotipo()->getIdCompraestadotipo();
                if ($id_compraestado_tipo == 2) {
                    $ordenes[] = [
                        'id_compra' => $id_compra
                    ];
                }
            }
        }
        return $ordenes;
    }

    public function getUltimoEstadoCompra($id_compra)
    {
        $param = [];
        $param['idcompra'] = $id_compra;
        $estado_compra = $this->buscarCompraEstado($param);
        $aux = 0;

        foreach ($estado_compra as $e) {
            $id_compra_estado_tipo = $e->getObjcompraestadotipo()->getIdCompraestadotipo();
            $aux = ($id_compra_estado_tipo > $aux) ? $id_compra_estado_tipo : $aux;
        }

        // $aux va a ser el último estado de la compra, para saber cómo finalizó
        return $aux;
    }

}
