<?php
/**
 * this class loads the cms skin
 *
 */
class Zend_View_Helper_LoadSkin extends Zend_View_Helper_Abstract
{
    public function loadSkin ($skin)
    {
        // load the skin config file
        $skinData = new Zend_Config_Xml('./skins/' . $skin . '/skin.xml');
        $stylesheets = $skinData->stylesheets->stylesheet->toArray();
        // append each stylesheet
        if (is_array($stylesheets)) {
            foreach ($stylesheets as $stylesheet) {
                $this->view->headLink()->appendStylesheet('/skins/' . $skin . '/css/' . $stylesheet);
            }
        }
    }
}
