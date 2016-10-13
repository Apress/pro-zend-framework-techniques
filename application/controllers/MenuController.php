<?php
class MenuController extends Zend_Controller_Action
{
    public function init ()
    {/* Initialize action controller here */
}
    public function indexAction ()
    {
        $mdlMenu = new Model_Menu();
        $this->view->menus = $mdlMenu->getMenus();
    }
    public function createAction ()
    {
        $frmMenu = new Form_Menu();
        if ($this->getRequest()->isPost()) {
            if ($frmMenu->isValid($_POST)) {
                $menuName = $frmMenu->getValue('name');
                $mdlMenu = new Model_Menu();
                $result = $mdlMenu->createMenu($menuName);
                if ($result) {
                    // redirect to the index action
                    return $this->_forward('index');
                }
            }
        }
        $frmMenu->setAction('/menu/create');
        $this->view->form = $frmMenu;
    }
    public function editAction ()
    {
        $id = $this->_request->getParam('id');
        $mdlMenu = new Model_Menu();
        $frmMenu = new Form_Menu();
        // if this is a postback, then process the form if valid
        if ($this->getRequest()->isPost()) {
            if ($frmMenu->isValid($_POST)) {
                $menuName = $frmMenu->getValue('name');
                $mdlMenu = new Model_Menu();
                $result = $mdlMenu->updateMenu($id, $menuName);
                if ($result) {
                    // redirect to the index action
                    return $this->_forward('index');
                }
            }
        } else {
            // fetch the current menu from the db
            $currentMenu = $mdlMenu->find($id)->current();
            // populate the form
            $frmMenu->getElement('id')->setValue($currentMenu->id);
            $frmMenu->getElement('name')->setValue($currentMenu->name);
        }
        $frmMenu->setAction('/menu/edit');
        // pass the form to the view to render
        $this->view->form = $frmMenu;
    }
    public function deleteAction ()
    {
        $id = $this->_request->getParam('id');
        $mdlMenu = new Model_Menu();
        $mdlMenu->deleteMenu($id);
        $this->_forward('index');
    }
    public function renderAction ()
    {
        $menu = $this->_request->getParam('menu');
        // fetch the Zend_Cache object
        $bootstrap = $this->getInvokeArg('bootstrap');
        $cache = $bootstrap->getResource('cache');
        $cacheKey = 'menu_' . $menu;
        // attempt to load the menu from cache
        $container = $cache->load($cacheKey);
        if (! $container) {
            // if the menu is not cached then build it and cache it
            $mdlMenuItems = new Model_MenuItem();
            $menuItems = $mdlMenuItems->getItemsByMenu($menu);
            if (count($menuItems) > 0) {
                foreach ($menuItems as $item) {
                    // add a cache tag so you can update the menu when you update the items
                    $tags[] = 'menu_item_' . $item->id;
                    $label = $item->label;
                    if (! empty($item->link)) {
                        $uri = $item->link;
                    } else {
                        // add a cache tag to this menu so you can update the cached menu
                        // when you update the page
                        $tags[] = 'page_' . $item->page_id;
                        // update this to form more search engine friendly URLs
                        $page = new CMS_Content_Item_Page($item->page_id);
                        $uri = '/page/open/title/' . $page->name;
                    }
                    $itemArray[] = array('label' => $label , 'uri' => $uri);
                }
                $container = new Zend_Navigation($itemArray);
                // cache the container
                $cache->save($container, $cacheKey, $tags);
            }
        }
        if ($container instanceof Zend_Navigation_Container) {
            $this->view->navigation()->setContainer($container);
        }
    }
}
