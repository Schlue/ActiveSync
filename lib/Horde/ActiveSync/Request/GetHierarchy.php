<?php
/**
 * Horde_ActiveSync_Request_GetHierarchy::
 *
 * Portions of this class were ported from the Z-Push project:
 *   File      :   wbxml.php
 *   Project   :   Z-Push
 *   Descr     :   WBXML mapping file
 *
 *   Created   :   01.10.2007
 *
 *   � Zarafa Deutschland GmbH, www.zarafaserver.de
 *   This file is distributed under GPL-2.0.
 *   Consult COPYING file for details
 *
 * @license   http://www.horde.org/licenses/gpl GPLv2
 *
 * @copyright 2009-2020 Horde LLC (http://www.horde.org)
 * @author    Michael J Rubinsky <mrubinsk@horde.org>
 * @package   ActiveSync
 */
/**
 * Handle GetHierarchy requests.
 *
 * @license   http://www.horde.org/licenses/gpl GPLv2
 *
 * @copyright 2009-2020 Horde LLC (http://www.horde.org)
 * @author    Michael J Rubinsky <mrubinsk@horde.org>
 * @package   ActiveSync
 * @internal
 */
class Horde_ActiveSync_Request_GetHierarchy extends Horde_ActiveSync_Request_Base
{
    /**
     * Handle request
     *
     * @return boolean
     */
    public function handle()
    {
        $folders = $this->_driver->getFolders();
        if (!$folders) {
            return false;
        }

        $this->_encoder->StartWBXML();
        $this->_encoder->startTag(self::FOLDERHIERARCHY_FOLDERS);

        foreach ($folders as $folder) {
            $this->_encoder->startTag(self::FOLDERHIERARCHY_FOLDER);
            $folder->encodeStream($this->_encoder);
            $this->_encoder->endTag();
        }
        $this->_encoder->endTag();

        return true;
    }

}
