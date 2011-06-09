<?php
/**
 * $Id$
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
 *
 */

require_once(KT_LIB_DIR . '/dispatcher.inc.php');
require_once(KT_LIB_DIR . '/templating/templating.inc.php');

require_once(KT_LIB_DIR . '/browse/browseutil.inc.php');

require_once(KT_LIB_DIR . '/documentmanagement/Document.inc');
require_once(KT_LIB_DIR . '/documentmanagement/DocumentTransaction.inc');

require_once(KT_LIB_DIR . '/widgets/fieldWidgets.php');
require_once(KT_LIB_DIR . '/templating/kt3template.inc.php');

class DeletedDocumentsDispatcher extends KTAdminDispatcher {

    var $sHelpPage = 'ktcore/admin/deleted documents.html';

    public function do_main()
    {
        //$this->aBreadcrumbs[] = array('url' => $_SERVER['PHP_SELF'], 'name' => _kt('Deleted Documents'));
        //
        //$this->oPage->setBreadcrumbDetails(_kt('view'));

        $documents = Document::getList('status_id=' . DELETED);

        if (!empty($documents)) {
            $items = count($documents);
            if (fmod($items, 10) > 0) {
                $pages = floor($items/10)+1;
            } else {
                $pages = ($items/10);
            }

            for ($i = 1; $i <= $pages; $i++) {
                $aPages[] = $i;
            }

            if ($items < 10) {
                $limit = $items-1;
            } else {
                $limit = 9;
            }

            for ($i = 0; $i <= $limit; $i++) {
                $documentsList[] = $documents[$i];
            }
        }

        $templating =& KTTemplating::getSingleton();
        $template = $templating->loadTemplate('ktcore/document/admin/deletedlist');
        $template->setData(array(
            'context' => $this,
            'fullList' => $documents,
            'documents' => $documentsList,
            'pagelist' => $aPages,
            'pagecount' => $pages,
            'itemcount' => $items,
        'section_query_string' => $this->sectionQueryString
        ));

        return $template->render();
    }

    public function do_branchConfirm()
    {
        $submit = KTUtil::arrayGet($_REQUEST, 'submit' , array());
        if (array_key_exists('expunge',$submit)) {
            return $this->do_confirm_expunge();
        }

        if (array_key_exists('restore', $submit)) {
            return $this->do_confirm_restore();
        }

        if (array_key_exists('expungeall', $submit)) {
            return $this->do_confirm_expunge(true);
        }

        $this->errorRedirectToMain(_kt('No action specified.'));
    }

    public function do_confirm_expunge($all = false)
    {
        $selectedDocs = KTUtil::arrayGet($_REQUEST, 'selected_docs', array());
        $fullDocs = KTUtil::arrayGet($_REQUEST, 'docIds', array());
        if ($all == true) {
            $selectedDocs = $fullDocs;
        }

        $this->aBreadcrumbs[] = array('url' =>  $_SERVER['PHP_SELF'], 'name' => _kt('Deleted Documents'));
        $this->oPage->setTitle(sprintf(_kt('Confirm Expunge of %d documents'), count($selectedDocs)));
        $this->oPage->setBreadcrumbDetails(sprintf(_kt('confirm expunge of %d documents'), count($selectedDocs)));

        $documents = array();
        foreach ($selectedDocs as $doc_id) {
            $oDoc = Document::get($doc_id);
            if (PEAR::isError($oDoc) || ($oDoc === false)) {
                $this->errorRedirectToMain(_kt('Invalid document id specified. Aborting expunge'));
            } else if ($oDoc->getStatusId() != DELETED) {
                $this->errorRedirectToMain(sprintf(_kt('%s is not a deleted document. Aborting expunge'), $oDoc->getName()));
            }
            $documents[] = $oDoc;
        }

        $templating = KTTemplating::getSingleton();
        $template = $templating->loadTemplate('ktcore/document/admin/expungeconfirmlist');
        $template->setData(array(
            'context' => $this,
            'documents' => $documents,
            'section_query_string' => $this->sectionQueryString
        ));

        return $template->render();
    }

    public function do_finish_expunge()
    {
        $oStorage = KTStorageManagerUtil::getSingleton();
        $selectedDocs = KTUtil::arrayGet($_REQUEST, 'selected_docs', array());

        $documents = array();
        foreach ($selectedDocs as $doc_id) {
            $oDoc =& Document::get($doc_id);
            if (PEAR::isError($oDoc) || ($oDoc === false)) {
                $this->errorRedirectToMain(_kt('Invalid document id specified. Aborting expunge'));
            } else if ($oDoc->getStatusId() != DELETED) {
                $this->errorRedirectToMain(sprintf(_kt('%s is not a deleted document. Aborting expunge'), $oDoc->getName()));
            }
            $documents[] = $oDoc;
        }

        $this->startTransaction();
        $aErrorDocuments = array();
        $aSuccessDocuments = array();
        $aDeletedDocs = array();

        $oKTTriggerRegistry = KTTriggerRegistry::getSingleton();

        foreach ($documents as $oDoc) {
            // first evaluate the folder for inconsistencies.
            $oFolder = Folder::get($oDoc->getFolderID());
            $sFilename = $oDoc->getFileName();
            $full_path = $oDoc->getFullPath();

            if (PEAR::isError($oFolder)) { $oDoc->setFolderId(1); $oDoc->update(); }

            if (!$oStorage->expunge($oDoc)) {
                $aErrorDocuments[] = $oDoc->getDisplayPath();
            }
            else {
                // Store the documents full path in the transaction history - replace the title with the filename
                $path_arr = explode('/', $full_path);
                array_pop($path_arr);
                $full_path = implode('/', $path_arr);

                $comment = sprintf(_kt("Document expunged: %s/%s"), $full_path, $sFilename);
                $oDocumentTransaction = & new DocumentTransaction($oDoc, $comment, 'ktcore.transactions.expunge');
                $oDocumentTransaction->create();

                // delete this from the db now
                if (!$oDoc->delete()) {
                    $aErrorDocuments[] = $oDoc->getId();
                } else {
                    // removed succesfully
                    $aSuccessDocuments[] = $oDoc->getDisplayPath();
                    $aDeletedDocs[] = $oDoc->getId();

                    // remove any document data
                    $oDoc->cleanupDocumentData($oDoc->getId()); // silly - why the redundancy?

                    $aTriggers = $oKTTriggerRegistry->getTriggers('expunge', 'finalised');
                    foreach ($aTriggers as $aTrigger) {
                        $sTrigger = $aTrigger[0];
                        $oTrigger = new $sTrigger;
                        $aInfo = array(
                            'document' => $oDoc,
                        );
                        $oTrigger->setInfo($aInfo);
                        $ret = $oTrigger->finalised();
                    }
                }
            }
        }
        $this->commitTransaction();

        $aTriggers = $oKTTriggerRegistry->getTriggers('expunge', 'postValidate');
        foreach ($aTriggers as $aTrigger) {
            $sTrigger = $aTrigger[0];
            $oTrigger = new $sTrigger;
            $aInfo = array(
                'documents' => $aDeletedDocs,
            );
            $oTrigger->setInfo($aInfo);
            $ret = $oTrigger->postValidate();
        }

        $msg = sprintf(_kt('%d documents expunged.'), count($aSuccessDocuments));
        if (count($aErrorDocuments) != 0) { $msg .= _kt('Failed to expunge') . ': ' . join(', ', $aErrorDocuments); }
        $this->successRedirectToMain($msg);
    }

    public function do_confirm_restore()
    {
        $selectedDocs = KTUtil::arrayGet($_REQUEST, 'selected_docs', array());

        $this->oPage->setTitle(sprintf(_kt('Confirm Restore of %d documents'), count($selectedDocs)));
        $this->aBreadcrumbs[] = array('url' =>  $_SERVER['PHP_SELF'], 'name' => _kt('Deleted Documents'));
        $this->oPage->setBreadcrumbDetails(sprintf(_kt('Confirm Restore of %d documents'), count($selectedDocs)));

        $documents = array();
        foreach ($selectedDocs as $doc_id) {
            $oDoc = Document::get($doc_id);
            if (PEAR::isError($oDoc) || ($oDoc === false)) {
                $this->errorRedirectToMain(_kt('Invalid document id specified. Aborting expunge'));
            } else if ($oDoc->getStatusId() != DELETED) {
                $this->errorRedirectToMain(sprintf(_kt('%s is not a deleted document. Aborting expunge'), $oDoc->getName()));
            }
            $documents[] = $oDoc;
        }

        $templating = KTTemplating::getSingleton();
        $template = $templating->loadTemplate('ktcore/document/admin/restoreconfirmlist');
        $template->setData(array(
            'context' => $this,
            'documents' => $documents,
            'section_query_string' => $this->sectionQueryString
        ));

        return $template->render();
    }

    public function do_finish_restore()
    {
        $oStorage = KTStorageManagerUtil::getSingleton();
        $selectedDocs = KTUtil::arrayGet($_REQUEST, 'selected_docs', array());

        $documents = array();
        foreach ($selectedDocs as $doc_id) {
            $oDoc =& Document::get($doc_id);
            if (PEAR::isError($oDoc) || ($oDoc === false)) {
                $this->errorRedirectToMain(_kt('Invalid document id specified. Aborting restore'));
            } else if ($oDoc->getStatusId() != DELETED) {
                $this->errorRedirectToMain(sprintf(_kt('%s is not a deleted document. Aborting restore'), $oDoc->getName()));
            }
            $documents[] = $oDoc;
        }

        $this->startTransaction();

        $aErrorDocuments = array();
        $aSuccessDocuments = array();

        foreach ($documents as $oDoc) {
            $oFolder = Folder::get($oDoc->getRestoreFolderId());
            // move to root if parent no longer exists.
            if (PEAR::isError($oFolder)) {
                $oDoc->setFolderId(1);
                $oFolder = Folder::get(1);
            } else {
                $oDoc->setFolderId($oDoc->getRestoreFolderId());
            }

            if ($oStorage->restore($oDoc)) {
                $oDoc = Document::get($oDoc->getId()); // storage path has changed for most recent object...
                $oDoc->setStatusId(LIVE);
                $oDoc->setPermissionObjectId($oFolder->getPermissionObjectId());
                $res = $oDoc->update();
                if (PEAR::isError($res) || ($res == false)) {
                    $aErrorDocuments[] = $oDoc->getName();
                    continue; // skip transactions, etc.
                }

                $res = KTPermissionUtil::updatePermissionLookup($oDoc);

                if (PEAR::isError($res)) {
                    $aErrorDocuments[] = $oDoc->getName();
                    continue; // skip transactions, etc.
                }

                // create a doc-transaction.
                // FIXME does this warrant a transaction-type?
                $oTransaction = new DocumentTransaction($oDoc, sprintf(_kt("Restored from deleted state by %s"), $this->oUser->getName()), 'ktcore.transactions.update');

                if (!$oTransaction->create()) {
                    ; // do nothing?  the state of physicaldocumentmanager...
                }
                $aSuccessDocuments[] = $oDoc->getName();
            } else {
                $aErrorDocuments[] = $oDoc->getName();
            }
        }

        $this->commitTransaction();

        $msg = sprintf(_kt('%d documents restored.'), count($aSuccessDocuments));
        if (count($aErrorDocuments) != 0) {
            $msg .= _kt('Failed to restore') . ': ' . join(', ', $aErrorDocuments);
        }

        $this->successRedirectToMain($msg);
    }

    public function getRestoreLocationfor($oDocument)
    {
        $iFolderId = $oDocument->getRestoreFolderId();
        $oFolder = Folder::get($iFolderId);

        if (PEAR::isError($oFolder)) {
            return _kt('Original folder no longer exists.  Document will be restored in the root folder.');
        } else {
            $aCrumbs = KTBrowseUtil::breadcrumbsForFolder($oFolder);
            $aParts = array();
            foreach ($aCrumbs as $aInfo) {
                $aParts[] = $aInfo['name'];
            }
            return implode(' &raquo; ', $aParts);
        }
    }

    public function handleOutput($output)
    {
        print $output;
    }

}

?>
