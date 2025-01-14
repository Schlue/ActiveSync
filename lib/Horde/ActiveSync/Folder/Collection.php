<?php
/**
 * Horde_ActiveSync_Folder_Collection::
 *
 * @license   http://www.horde.org/licenses/gpl GPLv2
 *
 * @copyright 2012-2020 Horde LLC (http://www.horde.org)
 * @author    Michael J Rubinsky <mrubinsk@horde.org>
 * @package   ActiveSync
 */
/**
 * The class contains functionality for maintaining state for a generic
 * collection folder. This would include Appointments, Contacts, and Tasks.
 *
 * @license   http://www.horde.org/licenses/gpl GPLv2
 *
 * @copyright 2012-2020 Horde LLC (http://www.horde.org)
 * @author    Michael J Rubinsky <mrubinsk@horde.org>
 * @package   ActiveSync
 */
class Horde_ActiveSync_Folder_Collection extends Horde_ActiveSync_Folder_Base implements Serializable
{
    const VERSION = 1;

    /**
     * Updates the internal UID cache, and clears the internal
     * update/deleted/changed cache.
     */
    public function updateState()
    {
        $this->haveInitialSync = true;
    }

    /**
     * Convert the instance into a string.
     *
     * @return string The string representation for this instance.
     */
    public function __toString()
    {
        return sprintf(
            'serverid: %s\nclass: %s\n',
            $this->serverid(),
            $this->collectionClass());
    }

    /**
     * Serialize this object.
     *
     * @return string  The serialized data.
     */
    public function serialize()
    {
        return json_encode(array(
            's' => $this->_status,
            'f' => $this->_serverid,
            'c' => $this->_class,
            'lsd' => $this->_lastSinceDate,
            'sd' => $this->_softDelete,
            'i' => $this->haveInitialSync,
            'v' => self::VERSION)
        );
    }

    /**
     * Serialize this object. PHP 8+
     *
     * @return array  The data to serialize as an array.
     */
    public function __serialize(): array 
    {
        return
        [
            's' => $this->_status,
            'f' => $this->_serverid,
            'c' => $this->_class,
            'lsd' => $this->_lastSinceDate,
            'sd' => $this->_softDelete,
            'i' => $this->haveInitialSync,
            'v' => self::VERSION
        ];
    }
    /**
     * Reconstruct the object from serialized data.
     *
     * @param string $data  The serialized data.
     * @throws Horde_ActiveSync_Exception_StaleState
     */
    public function unserialize($data)
    {
       $data = @json_decode($data, true);
        if (!is_array($data) || empty($data['v']) || $data['v'] != self::VERSION) {
            throw new Horde_ActiveSync_Exception_StaleState('Cache version change');
        }
        $this->_status = $data['s'];
        $this->_serverid = $data['f'];
        $this->_class = $data['c'];
        $this->haveInitialSync = $data['i'];
        $this->_lastSinceDate = empty($data['lsd']) ? 0 : $data['lsd'];
        $this->_softDelete = empty($data['sd']) ? 0 : $data['sd'];
    }

    /**
     * Reconstruct the object from serialized data. PHP 8+
     * 
     * @param array $data  The serialized data.
     * @throws Horde_ActiveSync_Exception_StaleState
     */
    public function __unserialize(array $data): void
    {
        if (!is_array($data) || empty($data['v']) || $data['v'] != self::VERSION) {
            throw new Horde_ActiveSync_Exception_StaleState('Cache version change');
        }
        $this->_status = $data['s'];
        $this->_serverid = $data['f'];
        $this->_class = $data['c'];
        $this->haveInitialSync = $data['i'];
        $this->_lastSinceDate = empty($data['lsd']) ? 0 : $data['lsd'];
        $this->_softDelete = empty($data['sd']) ? 0 : $data['sd'];
    }

}
