<?php
class MenuitemController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

public function indexAction()
{
   $menu = $this->_request->getParam('menu');
   $mdlMenu = new Model_Menu();
   $mdlMenuItem = new Model_MenuItem();
   $this->view->menu = $mdlMenu->find($menu)->current();
   $this->view->items = $mdlMenuItem->getItemsByMenu($menu);
}
public function addAction ()
{
    $menu = $this->_request->getParam('menu');
    $mdlMenu = new Model_Menu();
    $this->view->menu = $mdlMenu->find($menu)->current();
    $frmMenuItem = new Form_MenuItem();
    if ($this->_request->isPost()) {
        if ($frmMenuItem->isValid($_POST)) {
            $data = $frmMenuItem->getValues();
            $mdlMenuItem = new Model_MenuItem();
            $mdlMenuItem->addItem($data['menu_id'], $data['label'],
                $data['page_id'], $data['link']);
            $this->_request->setParam('menu', $data['menu_id']);
            $this->_forward('index');
        }
    }
    $frmMenuItem->populate(array('menu_id' => $menu));
    $this->view->form = $frmMenuItem;
}
public function moveAction() {
    $id = $this->_request->getParam ( 'id' );
    $direction = $this->_request->getParam ( 'direction' );
    $mdlMenuItem = new Model_MenuItem ( );
    $menuItem = $mdlMenuItem->find ( $id )->current ();
    if ($direction == 'up') {
        $mdlMenuItem->moveUp ( $id );
    } elseif ($direction == 'down') {
        $mdlMenuItem->moveDown ( $id );
    }
    $this->_request->setParam ( 'menu', $menuItem->menu_id );
    $this->_forward ( 'index' );
}
public function updateAction ()
{
    $id = $this->_request->getParam('id');
    // fetch the current item
    $mdlMenuItem = new Model_MenuItem();
    $currentMenuItem = $mdlMenuItem->find($id)->current();
    // fetch its menu
    $mdlMenu = new Model_Menu();
    $this->view->menu = $mdlMenu->find($currentMenuItem->menu_id)->current();
    // create and populate the form instance
    $frmMenuItem = new Form_MenuItem();
    $frmMenuItem->setAction('/menuitem/update');
    // process the postback
    if ($this->_request->isPost()) {
        if ($frmMenuItem->isValid($_POST)) {
            $data = $frmMenuItem->getValues();
            $mdlMenuItem->updateItem($data['id'], $data['label'],
                $data['page_id'], $data['link']);
            $this->_request->setParam('menu', $data['menu_id']);
            return $this->_forward('index');
        }
    } else {
        $frmMenuItem->populate($currentMenuItem->toArray());
    }
    $this->view->form = $frmMenuItem;
}
public function deleteAction() {
    $id = $this->_request->getParam ( 'id' );
    $mdlMenuItem = new Model_MenuItem ( );
    $currentMenuItem = $mdlMenuItem->find ( $id )->current ();
    $mdlMenuItem->deleteItem ( $id );
    $this->_request->setParam ( 'menu', $currentMenuItem->menu_id );
    $this->_forward ( 'index' );
}



}
