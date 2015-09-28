<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2015 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * JEvents Event activity class.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanJeventsActivityEvent extends ComLogmanModelEntityActivity
{
    protected function _initialize(KObjectConfig $config)
    {
        $config->append(array(
            'format' => '{actor} {action} {object.subtype} {object.type} title {object}',
            'object_column' => 'ev_id',
            'object_table'  => $config->data->package . '_vevent'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $config->append(array(
            'url'     => 'option=com_jevents&task=icalevent.edit&cid[]=' . $this->row,
            'subtype' => array('object' => true, 'objectName' => 'JEvents')
        ));

        parent::_objectConfig($config);
    }
}