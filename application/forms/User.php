<?php
class Form_User extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

        //create the form elements
        $username = $this->createElement('text','username');
        $username->setLabel('Username: ');
        $username->setRequired('true');
        $username->addFilter('StripTags');
        $username->addErrorMessage('The username is required!');
        $this->addElement($username);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: ');
        $password->setRequired('true');
        $this->addElement($password);

        $firstName = $this->createElement('text','first_name');
        $firstName->setLabel('First Name: ');
        $firstName->setRequired('true');
        $firstName->addFilter('StripTags');
        $this->addElement($firstName);

        $lastName = $this->createElement('text','last_name');
        $lastName->setLabel('Last Name: ');
        $lastName->setRequired('true');
        $lastName->addFilter('StripTags');
        $this->addElement($lastName);

        $role = $this->createElement('select', 'role');
        $role->setLabel("Select a role:");
        $role->addMultiOption('User', 'user');
        $role->addMultiOption('Administrator', 'administrator');
        $this->addElement($role);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    }
}
?>
