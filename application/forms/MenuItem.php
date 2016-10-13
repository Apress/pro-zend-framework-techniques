<?php
class Form_MenuItem extends Zend_Form
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

        // create new element
        $menuId = $this->createElement('hidden', 'menu_id');
        // element options
        $menuId->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($menuId);

        // create new element
        $label = $this->createElement('text', 'label');
        // element options
        $label->setLabel('Label: ');
        $label->setRequired(TRUE);
        $label->addFilter('StripTags');
        $label->setAttrib('size',40);
        // add the element to the form
        $this->addElement($label);

        // create new element
        $pageId = $this->createElement('select', 'page_id');
        // element options 
        $pageId->setLabel('Select a page to link to: ');
        $pageId->setRequired(true);

        // populate this with the pages
        $mdlPage = new Model_Page();
        $pages = $mdlPage->fetchAll(null, 'name');
        $pageId->addMultiOption(0, 'None');
        if($pages->count() > 0) {
            foreach ($pages as $page) {
                $pageId->addMultiOption($page->id, $page->name);
            }
        }
        // add the element to the form
        $this->addElement($pageId);

        // create new element 
        $link = $this->createElement('text', 'link');
        // element options 
        $link->setLabel('or specify a link: ');
        $link->setRequired(false);
        $link->setAttrib('size',50);
        // add the element to the form
        $this->addElement($link);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit'));
    }
}
?>
