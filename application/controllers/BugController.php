<?php
class BugController extends Zend_Controller_Action
{
    public function init ()
    {}
    public function indexAction ()
    {}
    public function createAction ()
    {}
    public function submitAction ()
    {
        $bugReportForm = new Form_BugReportForm();
        $bugReportForm->setAction('/bug/submit');
        $bugReportForm->setMethod('post');
        if ($this->getRequest()->isPost()) {
            if ($bugReportForm->isValid($_POST)) {
                $bugModel = new Model_Bug();
                // if the form is valid then create the new bug
                $result = $bugModel->createBug($bugReportForm->getValue('author'), $bugReportForm->getValue('email'), $bugReportForm->getValue('date'), $bugReportForm->getValue('url'), $bugReportForm->getValue('description'), $bugReportForm->getValue('priority'), $bugReportForm->getValue('status'));
                // if the createBug method returns a result 
                // then the bug was successfully created
                if ($result) {
                    $this->_forward('confirm');
                }
            }
        }
        $this->view->form = $bugReportForm;
    }
    public function confirmAction ()
    {}
    public function listAction ()
    {
        // get the filter form
        $listToolsForm = new Form_BugReportListToolsForm();
        $listToolsForm->setAction('/bug/list');
        $listToolsForm->setMethod('post');
        $this->view->listToolsForm = $listToolsForm;
        // set the sort and filter criteria. you need to update this to use the request, 
        // as these values can come in from the form post or a url parameter
        $sort = $this->_request->getParam('sort', null);
        $filterField = $this->_request->getParam('filter_field', null);
        $filterValue = $this->_request->getParam('filter');
        if (! empty($filterField)) {
            $filter[$filterField] = $filterValue;
        } else {
            $filter = null;
        }
        // now you need to manually set these controls values
        $listToolsForm->getElement('sort')->setValue($sort);
        $listToolsForm->getElement('filter_field')->setValue($filterField);
        $listToolsForm->getElement('filter')->setValue($filterValue);
        // fetch the bug paginator adapter
        $bugModels = new Model_Bug();
        $adapter = $bugModels->fetchPaginatorAdapter($filter, $sort);
        $paginator = new Zend_Paginator($adapter);
        // show 10 bugs per page
        $paginator->setItemCountPerPage(10);
        // get the page number that is passed in the request. 
        //if none is set then default to page 1.
        $page = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($page);
        // pass the paginator to the view to render 
        $this->view->paginator = $paginator;
    }
    public function editAction ()
    {
        $bugModel = new Model_Bug();
        $bugReportForm = new Form_BugReportForm();
        $bugReportForm->setAction('/bug/edit');
        $bugReportForm->setMethod('post');
        if ($this->getRequest()->isPost()) {
            if ($bugReportForm->isValid($_POST)) {
                $bugModel = new Model_Bug();
                // if the form is valid then update the bug
                $result = $bugModel->updateBug($bugReportForm->getValue('id'), $bugReportForm->getValue('author'), $bugReportForm->getValue('email'), $bugReportForm->getValue('date'), $bugReportForm->getValue('url'), $bugReportForm->getValue('description'), $bugReportForm->getValue('priority'), $bugReportForm->getValue('status'));
                return $this->_forward('list');
            }
        } else {
            $id = $this->_request->getParam('id');
            $bug = $bugModel->find($id)->current();
            $bugReportForm->populate($bug->toArray());
            //format the date field
            $bugReportForm->getElement('date')->setValue(date('m-d-Y', $bug->date));
        }
        $this->view->form = $bugReportForm;
    }
    public function deleteAction ()
    {
        $bugModel = new Model_Bug();
        $id = $this->_request->getParam('id');
        $bugModel->deleteBug($id);
        return $this->_forward('list');
    }
}













