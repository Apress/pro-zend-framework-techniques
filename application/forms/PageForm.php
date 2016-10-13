<?php
class Form_PageForm extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');

        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper')); 
        // add the element to the form
        $this->addElement($id);

        // create new element
        $name = $this->createElement('text', 'name');
        // element options
        $name->setLabel('Page Name: ');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
         // add the element to the form
        $this->addElement($name);

        // create new element
        $headline = $this->createElement('text', 'headline');
        // element options
        $headline->setLabel('Headline: ');
        $headline->setRequired(TRUE);
        $headline->setAttrib('size',50);
        // add the element to the form
        $this->addElement($headline);

        // create new element
        $image = $this->createElement('file', 'image');
        // element options
        $image->setLabel('Image: ');
        $image->setRequired(FALSE);
        $image->setDestination(APPLICATION_PATH . '/../public/images/upload');
        // ensure only 1 file
        $image->addValidator('Count', false, 1);
        // limit to 100K
        $image->addValidator('Size', false, 102400);
        // only JPEG, PNG, and GIFs
        $image->addValidator('Extension', false, 'jpg,png,gif');
        // add the element to the form
        $this->addElement($image);

        // create new element
        $description = $this->createElement('textarea', 'description');
        // element options
        $description->setLabel('Description: ');
        $description->setRequired(TRUE); 
        $description->setAttrib('cols',40);
        $description->setAttrib('rows',4);
        // add the element to the form
        $this->addElement($description);

        // create new element
        $content = $this->createElement('textarea', 'content');
        // element options
        $content->setLabel('Content');
        $content->setRequired(TRUE); 
        $content->setAttrib('cols',50); 
        $content->setAttrib('rows',12);
        // add the element to the form
        $this->addElement($content);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    }
}
?>
