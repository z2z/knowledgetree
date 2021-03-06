<?php
/**
* Object Service CMIS wrapper API for KnowledgeTree.
*
* KnowledgeTree Community Edition
* Document Management Made Simple
* Copyright (C) 2008, 2009, 2010 KnowledgeTree Inc.
* 
*
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License version 3 as published by the
* Free Software Foundation.
*
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
* details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* You can contact KnowledgeTree Inc., PO Box 7775 #87847, San Francisco,
* California 94120-7775, or email info@knowledgetree.com.
*
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU General Public License version 3.
*
* In accordance with Section 7(b) of the GNU General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* KnowledgeTree" logo and retain the original copyright notice. If the display of the
* logo is not reasonably feasible for technical reasons, the Appropriate Legal Notices
* must display the words "Powered by KnowledgeTree" and retain the original
* copyright notice.
* Contributor( s): ______________________________________
*/

/**
*
* @copyright 2008-2010, KnowledgeTree Inc.
* @license GNU General Public License version 3
* @author KnowledgeTree Team
* @package KTCMIS
* @version Version 0.9
*/

require_once(realpath(dirname(__FILE__) . '/ktService.inc.php'));
require_once(CMIS_DIR . '/services/CMISObjectService.inc.php');

/**
 * Handles requests for and actions on Folders and Documents
 */
class KTObjectService extends KTCMISBase {

    protected $ObjectService;

    public function __construct(&$ktapi = null, $username = null, $password = null)
    {
        parent::__construct($ktapi, $username, $password);
        // instantiate underlying CMIS service
        $this->ObjectService = new CMISObjectService();
        $this->setInterface();
    }

    public function startSession($username, $password)
    {
        parent::startSession($username, $password);
        $this->setInterface();
        return self::$session;
    }

    public function setInterface(&$ktapi = null)
    {
        parent::setInterface($ktapi);
        $this->ObjectService->setInterface(self::$ktapi);
    }

    /**
     * Gets the properties for the selected object
     *
     * @param string $repositoryId
     * @param string $objectId
     * @param string $filter
     * @return properties[]
     */
    public function getProperties($repositoryId, $objectId, $filter = '')
    {
        try {
            $properties = $this->ObjectService->getProperties($repositoryId, $objectId, $includeAllowableActions,
                                                              $includeRelationships);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $properties;
    }

    /**
     * Creates a new document within the repository
     *
     * @param string $repositoryId The repository to which the document must be added
     * @param array $properties Array of properties which must be applied to the created document object
     * @param string $folderId The id of the folder which will be the parent of the created document object
     *                         This parameter is optional IF unfilingCapability is supported
     * @param string $contentStream optional content stream data - expected as a base64 encoded string
     * @param string $versioningState optional version state value: none/checkedout/major/minor
     * @param $policies List of policy ids that MUST be applied
     * @param $addACEs List of ACEs that MUST be added
     * @param $removeACEs List of ACEs that MUST be removed
     * @return string $objectId The id of the created folder object
     */
    public function createDocument($repositoryId, $properties, $folderId = null, $contentStream = null, $versioningState = 'none', 
                                   $policies = array(), $addACEs = array(), $removeACEs = array())
    {
        $objectId = null;

        try {
            $objectId = $this->ObjectService->createDocument($repositoryId, $properties, $folderId, $contentStream, 
                                                             $versioningState,$policies, $addACEs, $removeACEs);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $objectId;
    }

    /**
     * Creates a new folder within the repository
     *
     * @param string $repositoryId The repository to which the folder must be added
     * @param array $properties Array of properties which must be applied to the created folder object
     * @param string $folderId The id of the folder which will be the parent of the created folder object
     * @param array $policies List of policy ids that MUST be applied
     * @param $addACEs List of ACEs that MUST be added
     * @param $removeACEs List of ACEs that MUST be removed
     * @return string $objectId The id of the created folder object
     */
    public function createFolder($repositoryId, $properties, $folderId, $policies = array(), $addACEs = array(), $removeACEs = array())
    {
        $objectId = null;

        try {
            $objectId = $this->ObjectService->createFolder($repositoryId, $properties, $folderId, $policies, $addACEs, $removeACEs);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $objectId;
    }
    
    /**
     * Fetches the content stream data for an object
     *  
     * @param string $repositoryId
     * @param string $objectId
     * @param string $streamId [optional for documents] Specifies the rendition to retrieve if not original document
     * @return string $contentStream (binary or text data)
     */
    function getContentStream($repositoryId, $objectId, $streamId = null)
    {
        try {
            $contentStream = $this->ObjectService->getContentStream($repositoryId, $objectId, $streamId);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $contentStream;
    }
    
    /**
     * Moves a fileable object from one folder to another.
     * 
     * @param string $repositoryId
     * @param string $objectId
     * @param string $targetFolderId
     * @param string $sourceFolderId
     * @return string $objectId
     */
    public function moveObject($repositoryId, $objectId, $targetFolderId, $sourceFolderId)
    {
        try {
            $this->ObjectService->moveObject($repositoryId, $objectId, $targetFolderId, $sourceFolderId);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $objectId;
    }
    
    /**
     * Deletes an object from the repository
     * 
     * @param string $repositoryId
     * @param string $objectId
     * @param string $allVersions [optional] If true, delete all versions
     * @return array
     */
    public function deleteObject($repositoryId, $objectId, $allVersions = true)
    {
        try {
            $this->ObjectService->deleteObject($repositoryId, $objectId, $allVersions);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $objectId;
    }
    
    public function deleteTree($repositoryId, $objectId, $changeToken = null, $unfileNonfolderObject = 'delete', $continueOnFailure = false)
    {
        $failed = array();
        
        try {
            $result = $this->ObjectService->deleteTree($repositoryId, $objectId, $changeToken, $unfileNonfolderObject, $continueOnFailure);
        }
        catch (Exception $e) {
            throw $e;
        }

        // check whether there is a list of items which did not delete
        if (count($result) > 0) {
            return $result;
        }
        
        return $failed;
    }

    /**
     * Sets the content stream data for an existing document
     *
     * if $overwriteFlag = TRUE, the new content stream is applied whether or not the document has an existing content stream
     * if $overwriteFlag = FALSE, the new content stream is applied only if the document does not have an existing content stream
     *
     * NOTE A Repository MAY automatically create new Document versions as part of this service method.
     *      Therefore, the documentId output NEED NOT be identical to the documentId input.
     *
     * @param string $repositoryId
     * @param string $documentId
     * @param boolean $overwriteFlag
     * @param string $contentStream
     * @param string $changeToken
     * @return string $documentId
     */
    function setContentStream($repositoryId, $documentId, $overwriteFlag, $contentStream, $changeToken = null)
    {
        try {
            $documentId = $this->ObjectService->setContentStream($repositoryId, $documentId, $overwriteFlag, $contentStream, $changeToken);
        }
        catch (Exception $e) {
            throw $e;
        }

        return $documentId;
    }

}

?>