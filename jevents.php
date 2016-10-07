<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * JEvents LOGman Plugin.
 *
 * Provides handlers for dealing with JEvents events.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */

class PlgLogmanJevents extends ComLogmanPluginJoomla
{
    /**
     * @var array Queue to make the difference between adds and edits.
     */
    protected $_is_new = array();

    /**
     * Before save event event handler.
     *
     * @param array $data   The event data.
     * @param       $rrule  Not a clue of what this is.
     * @param int   $dryrun Tells is the event is being stored or just being created.
     */
    public function onBeforeSaveEvent($data, $rrule, $dryrun)
    {
        // Only before actually saving an event.
        if (!$dryrun)
        {
            $is_new = false;

            // No ID means that the event is new.
            if (empty($data['evid'])) {
                $is_new = true;
            }

            $this->_is_new[] = $is_new;
        }
    }

    /**
     * After save event event handler.
     *
     * @param $event
     * @param $dryrun
     * @throws Exception
     */
    public function onAfterSaveEvent($event, $dryrun)
    {
        $is_new = array_pop($this->_is_new);

        $table = JTable::getInstance('Category');

        $category_title = null;

        if ($table->load($event->catid)) {
            $category_title = $table->title;
        }

        // Only log activities for saved events.
        if (!$dryrun)
        {
            $this->log(array(
                'object' => array(
                    'package'  => 'jevents',
                    'type'     => 'event',
                    'id'       => $event->ev_id,
                    'name'     => $event->_detail->summary,
                    'metadata' => array('category' => array('id' => $event->catid, 'title' => $category_title))
                ),
                'verb'   => $is_new ? 'add' : 'edit'
            ));
        }
    }

    /**
     * Store repeat event handler.
     *
     * @param iCalRepetition $repeat The event repeat object.
     */
    public function onStoreCustomRepeat($repeat)
    {
        if ($event = $this->_getEvent($repeat->eventid))
        {
            $this->log(array(
                'object' => array(
                    'package'  => 'jevents',
                    'type'     => 'repetition',
                    'id'       => $repeat->rp_id,
                    'name'     => 'repetition',
                    'metadata' => array('event' => array('id' => $repeat->eventid, 'title' => $event->summary))
                ),
                'verb'   => 'edit',
            ));
        }
    }

    /**
     * Repeat delete event handler.
     *
     * @param int $event_id The event id.
     */
    /*public function onDeleteEventRepeat($event_id)
    {
        // Not currently supported due to JEvents implementation issues.
    }*/

    /**
     * Delete event event handler.
     *
     * @param int $event_id The event id.
     */
    /*public function onDeleteCustomEvent($event_id)
    {
        // Not currently supported due to JEvents implementation issues.
    }*/

    /**
     * Publish event event handler.
     *
     * @param int $event_id The event id.
     * @param int $state    The state value.
     */
    public function onPublishEvent($event_ids, $state)
    {
        $result = null;

        switch($state)
        {
            case 1:
                $result = 'published';
                $verb = 'publish';
                break;
            case 0:
                $result = 'unpublished';
                $verb = 'unpublish';
                break;
            case -1:
                $result = 'trashed';
                $verb = 'trash';
                break;
        }

        if ($result)
        {
            foreach ($event_ids as $event_id)
            {
                if ($event = $this->_getEvent($event_id))
                {
                    $this->log(array(
                        'object' => array(
                            'package' => 'jevents',
                            'type'    => 'event',
                            'id'      => $event->ev_id,
                            'name'    => $event->summary
                        ),
                        'result' => $result,
                        'verb'   => $verb,
                    ));
                }
            }
        }
    }

    /**
     * Event getter.
     *
     * @param int $id The event id.
     *
     * @return JEventCal|null The event object, null if not found.
     */
    protected function _getEvent($id)
    {
        $event = null;

        $adapter = $this->getObject('lib:database.adapter.mysqli');

        $query = $this->getObject('lib:database.query.select')
                      ->table(array('events' => 'jevents_vevent'))
                      ->columns('*')
                      ->join(array('details' => 'jevents_vevdetail'), 'details.evdet_id = events.detail_id', 'INNER')
                      ->where('events.ev_id = :id')
                      ->bind(array('id' => $id));

        try {
            $event = $adapter->select($query, KDatabase::FETCH_OBJECT);
        } catch(Exception $e) {
            // Do nothing.
        }

        return $event;
    }
}
