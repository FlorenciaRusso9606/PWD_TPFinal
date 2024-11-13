<?php
function data_submitted() {
    $_AAux = array();
    if (!empty($_REQUEST))
        $_AAux = $_REQUEST;
    if (count($_AAux)) {
        foreach ($_AAux as $indice => $valor) {
            if ($valor == "")
                $_AAux[$indice] = 'null';
        }
    }
    return $_AAux;
}

function verEstructura($e) {
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}

spl_autoload_register(function ($class_name) {
    $directories = array(
        isset($_SESSION['ROOT']) ? $_SESSION['ROOT'] . 'Modelo/' : '',
        isset($_SESSION['ROOT']) ? $_SESSION['ROOT'] . 'Modelo/conector/' : '',
        isset($_SESSION['ROOT']) ? $_SESSION['ROOT'] . 'Control/' : '',
        isset($_SESSION['ROOT']) ? $_SESSION['ROOT'] . 'util/' : ''
    );
    foreach ($directories as $directory) {
        if ($directory && file_exists($directory . $class_name . '.php')) {
            require_once($directory . $class_name . '.php');
            return;
        }
    };
    
});
