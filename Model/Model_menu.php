<?php 

class Model_menu extends BaseDatos {
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;
    private $mensajeOperacion;

    public function __construct() {
        parent::__construct();
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->idpadre = "";
        $this->medeshabilitado = null;
    }

    public function setearValores($idmenu, $menombre, $medescripcion, $idpadre, $medeshabilitado) {
        $this->setIdMenu($idmenu);
        $this->setMeNombre($menombre);
        $this->setMeDescripcion($medescripcion);
        $this->setIdPadre($idpadre);
        $this->setMeDeshabilitado($medeshabilitado);
    }

    public function getIdMenu() {
        return $this->idmenu;
    }
    public function setIdMenu($idmenu) {
        $this->idmenu = $idmenu;
    }

    public function getMeNombre() {
        return $this->menombre;
    }
    public function setMeNombre($menombre) {
        $this->menombre = $menombre;
    }

    public function getMeDescripcion() {
        return $this->medescripcion;
    }
    public function setMeDescripcion($medescripcion) {
        $this->medescripcion = $medescripcion;
    }

    public function getIdPadre() {
        return $this->idpadre;
    }
    public function setIdPadre($idpadre) {
        $this->idpadre = $idpadre;
    }

    public function getMeDeshabilitado() {
        return $this->medeshabilitado;
    }
    public function setMeDeshabilitado($medeshabilitado) {
        $this->medeshabilitado = $medeshabilitado;
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
    
        // Asegúrate de que medeshabilitado siempre sea NULL al crear un menú
        $this->setMeDeshabilitado(null);
    
        // Comprobar si idpadre está vacío o nulo
        if ($this->getIdPadre() === NULL || $this->getIdPadre() === '') {
            // No incluir idpadre en la consulta
            $query = "INSERT INTO menu (menombre, medescripcion, medeshabilitado) 
                      VALUES ('{$this->getMeNombre()}', '{$this->getMeDescripcion()}', NULL)";
        } else {
            // Incluir idpadre en la consulta
            $query = "INSERT INTO menu (menombre, medescripcion, idpadre, medeshabilitado) 
                      VALUES ('{$this->getMeNombre()}', '{$this->getMeDescripcion()}', '{$this->getIdPadre()}', NULL)";
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
    

    

    public function Modificar()
    {
        $rta = false;

        if ($this->getIdPadre() === NULL || $this->getIdPadre() === '') {
            
            if ($this->getMeDeshabilitado() == -1) {
                $query = "UPDATE menu SET idmenu='{$this->getIdMenu()}',menombre='{$this->getMeNombre()}',medescripcion='{$this->getMeDescripcion()}' WHERE idmenu = {$this->getIdMenu()}";
            } else {
                $query = "UPDATE menu SET idmenu='{$this->getIdMenu()}',menombre='{$this->getMeNombre()}',medescripcion='{$this->getMeDescripcion()}',medeshabilitado='{$this->getMeDeshabilitado()}' WHERE idmenu = {$this->getIdMenu()}";
            }

        } else {

            if ($this->getMeDeshabilitado() == -1) {
                $query = "UPDATE menu SET idmenu='{$this->getIdMenu()}',menombre='{$this->getMeNombre()}',idpadre='{$this->getIdPadre()}',medescripcion='{$this->getMeDescripcion()}' WHERE idmenu = {$this->getIdMenu()}";
            } else {
                $query = "UPDATE menu SET idmenu='{$this->getIdMenu()}',menombre='{$this->getMeNombre()}',idpadre='{$this->getIdPadre()}',medescripcion='{$this->getMeDescripcion()}',medeshabilitado='{$this->getMeDeshabilitado()}' WHERE idmenu = {$this->getIdMenu()}";
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

        $query = "DELETE FROM menu WHERE idmenu = {$this->getIdMenu()}";

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
        $query = "SELECT * FROM menu WHERE idmenu = {$id_menu}";
        $rta = false;

        if ($this->Iniciar()) {

            if ($this->Ejecutar($query)) {

                if ($row = $this->Registro()) {
                    $this->setearValores($row['idmenu'], $row['menombre'], $row['medescripcion'], $row['idpadre'], $row['medeshabilitado']);
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
        $lista_menu = [];

        $query = "SELECT * FROM menu ";

        if ($parametro != '') {
            $query.= 'WHERE ' . $parametro;
        }

        $rta = $this->Ejecutar($query);

        if ($rta > -1) {
            if ($rta > 0) {
                while ($row = $this->Registro()) {
                    $objMenu = new Model_menu();
                    $objMenu->Buscar($row['idmenu']);
                    $lista_menu[] = $objMenu;
                }
            }
        } else {
            $this->setMsjOperacion($this->getError());
        }

        return $lista_menu;
    }
}

?>