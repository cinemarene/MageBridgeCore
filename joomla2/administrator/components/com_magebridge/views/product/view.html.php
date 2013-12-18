<?php
/*
 * Joomla! component MageBridge
 *
 * @author Yireo (info@yireo.com)
 * @package MageBridge
 * @copyright Copyright 2013
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

// Require the parent view
require_once JPATH_COMPONENT.'/view.php';

// Import the needed libraries
jimport('joomla.filter.output');

/**
 * HTML View class
 */
class MageBridgeViewProduct extends MageBridgeView
{
    /*
     * Display method
     *
     * @param string $tpl
     * @return null
     */
	public function display($tpl = null)
	{
        // Fetch this item
        $this->fetchItem();

        // Initialize the form-file
        $file = JPATH_ADMINISTRATOR.'/components/com_magebridge/models/product.xml';

        // Prepare the params-form
        $params = YireoHelper::toRegistry($this->item->params)->toArray();
        $params_form = JForm::getInstance('params', $file);
        $params_form->bind(array('params' => $params));
	    $this->assignRef('params_form', $params_form);

        // Prepare the actions-form
        $actions = YireoHelper::toRegistry($this->item->actions)->toArray();
        $actions_form = JForm::getInstance('actions', $file);
        JPluginHelper::importPlugin('magebridgeproduct');
        JFactory::getApplication()->triggerEvent('onMageBridgeProductPrepareForm', array(&$actions_form, (array)$this->item));
        $actions_form->bind(array('actions' => $actions));
	    $this->assignRef('actions_form', $actions_form);

        // Build the fields
        $this->lists['product'] = MageBridgeFormHelper::getField('product', 'sku', $this->item->sku, null);

        $connectors = MageBridgeConnectorProduct::getInstance()->getConnectors();
		$this->assignRef('connectors', $connectors);

		parent::display($tpl);
	}
}
