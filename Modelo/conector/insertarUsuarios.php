<?php
require 'BaseDatos.php'; // Asegúrate de que la clase BaseDatos esté en este archivo

// Crear una instancia de la conexión a la base de datos
$db = new BaseDatos();

// Verifica si la conexión se ha realizado correctamente
if (!$db->Iniciar()) {
    die("No se pudo conectar a la base de datos: " . $db->getError());
}

// Contraseñas originales
$usuarios = [
    ['Juan Perez', '12345', 'juanperez@example.com'],
    ['Maria Garcia', '67890', 'mariagarcia@example.com'],
    ['Carlos Sanchez', '54321', 'carlossanchez@example.com']
];

foreach ($usuarios as $usuario) {
    // Hash de la contraseña
    $hashedPassword = password_hash($usuario[1], PASSWORD_DEFAULT);
    $usmail = $usuario[2];
    $usdeshabilitado = isset($usuario[3]) ? "'" . $usuario[3] . "'" : 'NULL';

    // SQL para insertar el usuario
    $sql = "INSERT INTO Usuario (usnombre, uspass, usmail, usdeshabilitado) VALUES ('$usuario[0]', '$hashedPassword', '$usmail', $usdeshabilitado)";

    // Ejecutar la consulta
    if ($db->Ejecutar($sql) < 0) {
        echo "Error al insertar usuario: " . $db->getError() . "\n";
    } else {
        echo "Usuario $usuario[0] insertado correctamente.\n";
    }
}

// Inserciones en la tabla Rol (fuera del bucle)
$sqlRol = "INSERT INTO Rol (roldescripcion) VALUES 
    ('Administrador'),
    ('Cliente')";

if ($db->Ejecutar($sqlRol) < 0) {
    echo "Error al insertar roles: " . $db->getError() . "\n";
} else {
    echo "Roles insertados correctamente.\n";
}

// Inserciones en la tabla UsuarioRol (fuera del bucle)
$sqlUsuarioRol = "INSERT INTO UsuarioRol (idusuario, idrol) VALUES 
    (1, 1), 
    (2, 2), 
    (3, 2)";

if ($db->Ejecutar($sqlUsuarioRol) < 0) {
    echo "Error al insertar roles de usuario: " . $db->getError() . "\n";
} else {
    echo "Roles de usuario insertados correctamente.\n";
}
?>
