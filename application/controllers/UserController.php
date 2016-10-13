<?php
class UserController extends Zend_Controller_Action
{
public function indexAction()
{
    $auth = Zend_Auth::getInstance();

    if($auth->hasIdentity()) {
        $this->view->identity = $auth->getIdentity (); 
    }
}
    
    
public function createAction ()
{
    $frmUser = new Form_User();
    if ($this->_request->isPost()) {
        if ($frmUser->isValid($_POST)) {
            $mdlUser = new Model_User();
            $mdlUser->createUser(
                $frmUser->getValue('username'),
                $frmUser->getValue('password'),
                $frmUser->getValue('first_name'),
                $frmUser->getValue('last_name'),
                $frmUser->getValue('role')
            );
            return $this->_forward('list');
        }
    }
    $frmUser->setAction('/user/create');
    $this->view->form = $frmUser;
}

public function listAction ()
{
    $currentUsers = Model_User::getUsers();
    if ($currentUsers->count() > 0) {
        $this->view->users = $currentUsers;
    } else {
        $this->view->users = null;
    }
}
public function updateAction ()
{
    $frmUseruserForm = new Form_User();
    $frmUseruserForm->setAction('/user/update');
    $frmUseruserForm->removeElement('password');
    $mdlUseruserModel = new Model_User(); 
    if ($this->_request->isPost()) {
        if ($frmUseruserForm->isValid($_POST)) {
            $mdlUseruserModel->updateUser(
                $frmUseruserForm->getValue('id'),
                $frmUseruserForm->getValue('username'),
                $frmUseruserForm->getValue('first_name'),
                $frmUseruserForm->getValue('last_name'),
                $frmUseruserForm->getValue('role')
            );
            return $this->_forward('list'); 
        }
    } else {
        $id = $this->_request->getParam('id');
        $currentUser = $mdlUseruserModel->find($id)->current();
        $frmUseruserForm->populate($currentUser->toArray());
    }
    $this->view->form = $frmUseruserForm;
}

public function passwordAction ()
{
    $passwordForm = new Form_User();
    $passwordForm->setAction('/user/password');
    $passwordForm->removeElement('first_name');
    $passwordForm->removeElement('last_name');
    $passwordForm->removeElement('username');
    $passwordForm->removeElement('role');
    $userModel = new Model_User();
    if ($this->_request->isPost()) {
        if ($passwordForm->isValid($_POST)) {
            $userModel->updatePassword(
                $passwordForm->getValue('id'),
                $passwordForm->getValue('password')
            );
            return $this->_forward('list');
        }
    } else {
        $id = $this->_request->getParam('id');
        $currentUser = $userModel->find($id)->current();
        $passwordForm->populate($currentUser->toArray());
    }
    $this->view->form = $passwordForm;
}
public function deleteAction()
{
    $id = $this->_request->getParam('id');
    $mdlUseruserModel = new Model_User();
    $mdlUseruserModel->deleteUser($id);
    return $this->_forward('list');
}

public function loginAction ()
{
    $userForm = new Form_User();
    $userForm->setAction('/user/login');
    $userForm->removeElement('first_name');
    $userForm->removeElement('last_name');
    $userForm->removeElement('role');
    if ($this->_request->isPost() && $userForm->isValid($_POST)) {
        $data = $userForm->getValues();
        //set up the auth adapter
        // get the default db adapter
        $db = Zend_Db_Table::getDefaultAdapter();
        //create the auth adapter
        $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'users',
            'username', 'password');
        //set the username and password
        $authAdapter->setIdentity($data['username']);
        $authAdapter->setCredential(md5($data['password']));
        //authenticate
        $result = $authAdapter->authenticate();
        if ($result->isValid()) {
            // store the username, first and last names of the user
            $auth = Zend_Auth::getInstance();
            $storage = $auth->getStorage();
            $storage->write($authAdapter->getResultRowObject(
                array('username' , 'first_name' , 'last_name', 'role')));
            return $this->_forward('index');
        } else {
            $this->view->loginMessage = "Sorry, your username or
                password was incorrect";
        }
    }
    $this->view->form = $userForm;
}
public function logoutAction ()
{
    $authAdapter = Zend_Auth::getInstance();
    $authAdapter->clearIdentity();
}

    
}

