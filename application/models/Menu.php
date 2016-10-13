<?php
require_once 'Zend/Db/Table/Abstract.php';
class Model_Menu extends Zend_Db_Table_Abstract
{
    protected $_name = 'menus';
    protected $_dependentTables = array('Model_MenuItem');
    protected $_referenceMap = array('Menu' => array('columns' => array('parent_id') , 'refTableClass' => 'Model_Menu' , 'refColumns' => array('id') , 'onDelete' => self::CASCADE , 'onUpdate' => self::RESTRICT));
    public function createMenu ($name)
    {
        $row = $this->createRow();
        $row->name = $name;
        return $row->save();
    }
    public function getMenus ()
    {
        $select = $this->select();
        $select->order('name');
        $menus = $this->fetchAll($select);
        if ($menus->count() > 0) {
            return $menus;
        } else {
            return null;
        }
    }
    public function updateMenu ($id, $name)
    {
        $currentMenu = $this->find($id)->current();
        if ($currentMenu) {
            // clear the cache entry for this menu
            $cache = Zend_Registry::get('cache ');
            $id = 'menu_' . $id;
            $cache->remove($id);
            $currentMenu->name = $name;
            return $currentMenu->save();
        } else {
            return false;
        }
    }
    public function deleteMenu ($menuId)
    {
        $row = $this->find($menuId)->current();
        if ($row) {
            return $row->delete();
        } else {
            throw new Zend_Exception("Error loading menu");
        }
    }
}
?>
