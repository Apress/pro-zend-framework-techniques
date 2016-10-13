<?php
class FeedController extends Zend_Controller_Action
{
    public function rssAction()
    {
        // build the feed array
        $feedArray = array();
        
        // the title and link are required
        $feedArray['title'] = 'Recent Pages';
        $feedArray['link'] = 'http://localhost';
        
        // the published timestamp is optional
        $feedArray['published'] = Zend_Date::now()->toString(Zend_Date::TIMESTAMP);
        // the charset is required
        $feedArray['charset'] = 'UTF8';
        
        // first get the most recent pages
        $mdlPage = new Model_Page();
        $recentPages = $mdlPage->getRecentPages();
        
        //add the entries 
        if(is_array($recentPages) && count($recentPages) > 0) {
            foreach ($recentPages as $page) {
                // create the entry
                $entry = array();
                $entry['guid'] = $page->id;
                $entry['title'] = $page->headline;
                $entry['link'] = 'http://localhost/page/open/title/' . $page->name ; 
                $entry['description'] = $page->description;
                $entry['content'] = $page->content;
                
                // add it to the feed
                $feedArray['entries'][] = $entry;
            }
        }
        // create an RSS feed from the array
        $feed = Zend_Feed::importArray($feedArray, 'rss');
        
        // now send the feed
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $feed->send();        
    }
    
}
?>