<?php
class CMS_Application_Resource_Translate extends Zend_Application_Resource_ResourceAbstract
{
    public function init ()
    {
        $options = $this->getOptions();
        $adapter = $options['adapter'];
        $defaultTranslation = $options['default']['file'];
        $defaultLocale = $options['default']['locale'];
        $translate = new Zend_Translate($adapter, $defaultTranslation, $defaultLocale);
        foreach ($options['translation'] as $locale => $translation) {
            $translate->addTranslation($translation, $locale);
        }
        Zend_Registry::set('Zend_Translate', $translate);
        return $translate;
    }
}
