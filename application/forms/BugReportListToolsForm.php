<?php
class Form_BugReportListToolsForm extends Zend_Form
{
    public function init ()
    {
        $options = array('0' => 'None' , 'priority' => 'Priority' , 'status' => 'Status' , 'date' => 'Date' , 'url' => 'URL' , 'author' => 'Submitter');
        $sort = $this->createElement('select', 'sort');
        $sort->setLabel('Sort Records:');
        $sort->addMultiOptions($options);
        $this->addElement($sort);
        $filterField = $this->createElement('select', 'filter_field');
        $filterField->setLabel('Filter Field:');
        $filterField->addMultiOptions($options);
        $this->addElement($filterField);
        // create new element
        $filter = $this->createElement('text', 'filter');
        // element options
        $filter->setLabel('Filter Value:');
        $filter->setAttrib('size', 40);
        // add the element to the form
        $this->addElement($filter);
        // add element: submit button
        $this->addElement('submit', 'submit', array('label' => 'Update List'));
    }
}
?>
