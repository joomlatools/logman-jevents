<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * JEvents Repeat activity class.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanJeventsActivityRepetition extends ComLogmanModelEntityActivity
{
    protected function _initialize(KObjectConfig $config)
    {
        $data = $config->data;

        $config->append(array(
            'format'        => '{actor} {action} {object.subtype} {object.type} {target.subtype} {target} {target.type}',
            'object_column' => 'rp_id',
            'object_table'  => $data->package . '_repetition'
        ));

        parent::_initialize($config);
    }

    protected function _objectConfig(KObjectConfig $config)
    {
        $metadata = $this->getMetadata();

        $url = sprintf('option=com_jevents&task=icalrepeat.edit&cid[]=%d&evid=%d', $this->row, $metadata->event->id);

        $config->append(array(
            'type' => array('url' => $url, 'find' => 'object'),
            'subtype' => array('objectName' => 'event', 'object' => true)
        ));

        parent::_objectConfig($config);
    }

    public function getPropertyTarget()
    {
        $metadata = $this->getMetadata();

        return $this->_getObject(array(
            'objectName' => $metadata->event->title,
            'find'       => 'target',
            'url'        => 'option=com_jevents&task=icalevent.edit&cid[]=' . $metadata->event->id,
            'type'       => array('objectName' => 'event', 'object' => true),
            'subtype'    => array('objectName' => 'JEvent', 'object' => true)
        ));
    }

    protected function _findActivityTarget()
    {
        $result = false;

        $adapter = $this->getTable()->getAdapter();

        $query = $this->getObject('lib:database.query.select')
                      ->table('jevents_vevent')
                      ->columns('COUNT(*)')
                      ->where('ev_id = :id')
                      ->bind(array('id' => $this->getMetadata()->event->id));

        try {
            $result = (bool) $adapter->select($query, KDatabase::FETCH_FIELD);
        } catch(Exception $e) {
            // Do nothing.
        }

        return $result;
    }
}