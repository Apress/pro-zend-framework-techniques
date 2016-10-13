<?php
class SearchController extends Zend_Controller_Action
{
    public function indexAction()
    {
        if($this->_request->isPost()) {
            $keywords = $this->_request->getParam('keywords');
            $query = Zend_Search_Lucene_Search_QueryParser::parse($keywords);
            $index = Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes');
            $hits = $index->find($query);
            $this->view->results = $hits;  
            $this->view->keywords = $keywords;          
        }else{
            $this->view->results = null;
        }
    }
    
    public function buildAction()
    {
        // create the index
        $index = Zend_Search_Lucene::create(APPLICATION_PATH . '/indexes');
        
        // fetch all of the current pages
        $mdlPage = new Model_Page();
        $currentPages = $mdlPage->fetchAll();
        if($currentPages->count() > 0) {
            // create a new search document for each page
            foreach ($currentPages as $p) {
                $page = new CMS_Content_Item_Page($p->id);
                $doc = new Zend_Search_Lucene_Document();                
                // you use an unindexed field for the id because you want the id to be
                // included in the search results but not searchable
                $doc->addField(Zend_Search_Lucene_Field::unIndexed('page_id', 
                	$page->id));                
                // you use text fields here because you want the content to be searchable 
                // and to be returned in search results 
                $doc->addField(Zend_Search_Lucene_Field::text('page_name', 
                    $page->name));
                $doc->addField(Zend_Search_Lucene_Field::text('page_headline', 
                	$page->headline));
                $doc->addField(Zend_Search_Lucene_Field::text('page_description', 
                	$page->description));
                $doc->addField(Zend_Search_Lucene_Field::text('page_content', 
                	$page->content));
                // add the document to the index
                $index->addDocument($doc);
            }
        }
        // optimize the index
        $index->optimize();
        // pass the view data for reporting
        $this->view->indexSize = $index->numDocs();
    }
    
}
?>