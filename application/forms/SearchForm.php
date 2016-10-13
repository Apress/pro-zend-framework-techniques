<?php
class Form_SearchForm extends Zend_Form 
{
    public function init()
    {
        // create new element
        $query = $this->createElement('text', 'query');
        // element options
        $query->setLabel('Keywords');
        $query->setRequired(true);
        $query->setAttrib('size',20);
        // add the element to the form
        $this->addElement($query);
        
        $submit = $this->createElement('submit', 'search');
        $submit->setLabel('Search Site');
        $submit->setDecorators(array('ViewHelper'));     
        $this->addElement($submit);  
    }
}
