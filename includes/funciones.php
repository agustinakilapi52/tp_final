<?php 
function data_submitted() {
    
    $_AAux= array();
    if (!empty($_REQUEST))
        $_AAux =$_REQUEST;
     if (count($_AAux)){
            foreach ($_AAux as $indice => $valor) {
                if ($valor=="")
                    $_AAux[$indice] = 'null' ;
            }
        }
     return $_AAux;
        
}
function verEstructura($e){
    echo "<pre>";
    print_r($e);
    echo "</pre>"; 
}

spl_autoload_register( function ($class_name)  {
    //echo "class ".$class_name ;
    $directorys = array(
        $GLOBALS['ROOT'].'Model/',
        // $_SESSION['ROOT'].'Modelo/conector/',
        $GLOBALS['ROOT'].'Controller/',
      //  $GLOBALS['ROOT'].'util/class/',
    );
    //print_object($directorys) ;
    foreach($directorys as $directory){
        if(file_exists($directory.$class_name . '.php')){
            // echo "se incluyo".$directory.$class_name . '.php';
            require_once($directory.$class_name . '.php');
            return;
        }
    }
});


function base_url($url = '') {
    // Define la URL base de tu aplicación aquí
    $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/tp-avanzada/';
    
    // Combina la URL base con el parámetro dado
    return rtrim($base_url, '/') . '/' . ltrim($url, '/');
}

function acceso($roles, $rol_permitido = '') {
    $valido = false;
    foreach ($roles as $value) {
        if ($value == 1 || $rol_permitido == $value) {
            $valido = true;
        }
    }
    return $valido;
}

?>