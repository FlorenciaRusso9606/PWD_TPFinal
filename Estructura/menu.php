<?php

// Inicializar clases de sesión, menú y relación menú-rol
$session = new Session();
$abmMenu = new AbmMenu();
$abmMenuRol = new abmMenuRol();
$rol = $session->getRol();

// Filtrar menús según el rol del usuario
$menusPermitidos = [];
if ($rol !== null) {
    $menusPermitidos = $abmMenuRol->buscar(['idrol' => $rol->getIdRol()]);
}

// Obtener los menús permitidos
$menuIdsPermitidos = array_map(function($menuRol) {
    return $menuRol->getMenu()->getIdMenu();
}, $menusPermitidos);

// Filtrar solo los menús a los que el usuario tiene acceso
$menus = $abmMenu->buscar([]);
$menusFiltrados = array_filter($menus, function($menu) use ($menuIdsPermitidos) {
    return in_array($menu->getIdMenu(), $menuIdsPermitidos);
});

/**
 * Renderiza los submenús de un menú padre.
 * @param array $menus Lista de menús
 * @param Menu $padre El menú padre
 */
function renderizarSubmenu($menus, $padre) {
    $submenu = array_filter($menus, function($item) use ($padre) {
        return $item->getPadre() !== null && $item->getPadre()->getIdMenu() == $padre->getIdMenu();
    });

    $salida = "";
    if (!empty($submenu)) {
        $salida .= '<ul class="dropdown-menu" data-bs-theme="dark">';
        foreach ($submenu as $item) {
            $salida .= '<li><a class="dropdown-item" href="' . $item->getLink() . '">' . $item->getMeNombre() . '</a></li>';
        }
        $salida .= '</ul>';
    }
    return $salida;
}

/**
 * Renderiza el menú principal basado en los permisos.
 * @param array $menus Lista de menús filtrados según permisos
 */
function renderizarMenu($menus) {
    $salida = '<ul class="navbar-nav">';
    foreach ($menus as $item) {
        if ($item->getPadre() === null) {
            $salida .= '<li class="nav-item">';
            $salida .= renderizarSubmenu($menus, $item); // Agregamos submenús si existen
            $salida .= '</li>';
        }
    }
    $salida .= '</ul>';
    return $salida;
}

// Renderizar el menú dinámico
$menu_dinamico = renderizarMenu($menusFiltrados);

?>
