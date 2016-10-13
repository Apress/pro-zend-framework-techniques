<?php
class CMS_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
    public function init ()
    {
        $options = $this->getOptions();
        // Get a Zend_Cache_Core object
        $cache = Zend_Cache::factory(
            $options['frontEnd'], 
            $options['backEnd'], 
            $options['frontEndOptions'], 
            $options['backEndOptions']);
        Zend_Registry::set('cache', $cache);
        return $cache;
    }
}
