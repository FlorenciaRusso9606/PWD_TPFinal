<?php
class ControlNav
{
    /**
     * Devuelve un menu segun el id
     * @param mixed $id
     * @return mixed
     */
    public function getMenus($id)
    {
        $abmMenu = new AbmMenu();

        $menu = $abmMenu->buscar(["idmenu" => $id, "medesahabilitado" => null])[0];
        return $menu;
    }

    /**
     * Devuelve un arreglo de submenus segun el id
     * @param mixed $id
     * @return array
     */
    public function getSubMenus($id)
    {
        $subMenus = [];
        $abmMenu = new AbmMenu();
        $menus = $abmMenu->buscar(["idpadre" => $id, "medeshabilitado" => null]);
        foreach ($menus as $menu) {
            if ($menu->getMedeshabilitado() === null) {
                $subMenus[] = $menu;
            }
        }
        return $subMenus;
    }


    public function getUrl($id)
    {
        $url = $GLOBALS['RUTAVISTA'];
        $url .= $this->getMenus($id)->getMenombre() . ".php";
        return $url;
    }
}
