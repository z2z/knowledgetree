<?php

// main library routines and defaults
require_once("../config/dmsDefaults.php");
require_once("$default->owl_ui_directory/dashboardUI.inc");
require_once("$default->owl_fs_root/lib/visualpatterns/PatternMainPage.inc");
require_once("$default->owl_fs_root/lib/visualpatterns/PatternImage.inc");
require_once("$default->owl_fs_root/lib/visualpatterns/PatternTableLinks.inc");
require_once("$default->owl_fs_root/lib/visualpatterns/PatternTableSqlQuery.inc");
require_once("$default->owl_fs_root/lib/visualpatterns/PatternCustom.inc");

/**
 * $Id$
 *  
 * Main dashboard page -- This page is presented to the user after login.
 * It contains a high level overview of the users subscriptions, checked out 
 * document, pending approval routing documents, etc. 
 *
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * @version $Revision$
 * @author Michael Joseph <michael@jamwarehouse.com>, Jam Warehouse (Pty) Ltd, South Africa
 * @package presentation
 */

// -------------------------------
// page start
// -------------------------------

if (checkSession()) {
    // create a page  

    // logo
    $img = new PatternImage("$default->owl_root_url/locale/$default->owl_lang/graphics/$default->logo");
    $img->setImgSize(238, 178);
    
    // build the top menu of links
    // TODO: this is a function of the sitemap
    // get list of sections
    $aTopMenuLinks = array(generateControllerUrl("dashboard"), generateControllerUrl("browse"), generateControllerUrl("subscriptions"),
                           generateControllerUrl("search"), generateControllerUrl("administration"), generateControllerUrl("preferences"),
                           generateControllerUrl("documentBrowserTest"), generateControllerUrl("logout"));
    $aTopMenuText = array("Dashboard", "Browse Documents", "Subscriptions", "Advanced Search", "Administration", "Preferences", "Help", "Logout");
    $aTopMenuImages = array("$default->owl_graphics_url/dashboard.jpg", "$default->owl_graphics_url/browse.jpg",
                            "$default->owl_graphics_url/subscriptions.jpg", "$default->owl_graphics_url/search.jpg",
                            "$default->owl_graphics_url/administration.jpg", "$default->owl_graphics_url/preferences.jpg", 
                            "$default->owl_graphics_url/help.jpg", "$default->owl_graphics_url/logout.jpg");
    
    $oPatternTableLinks = new PatternTableLinks($aTopMenuLinks, null, 1, 8, 2, $aTopMenuImages);
    
    $sHtml = startTable("0", "100%") .
                 // pending documents
                 startTableRowCell() .
                     startTable("0", "100%") .
                         tableRow("left", "#996600", tableHeading("sectionHeading", 3, "Pending Documents")) . 
                         tableRow("", "", pendingDocumentsHeaders());
                         // FIXME: replace with the real method when its implemented
                         // something like:
                         //    DocumentManager::getPendingDocuments();                         
                         $aPendingDocumentList = getPendingDocuments($_SESSION["userID"]);
                         for ($i = 0; $i < count($aPendingDocumentList); $i++) {
                             $row = tableData($aPendingDocumentList[$i]->getTitleLink()) .
                                    tableData($aPendingDocumentList[$i]->getStatus()) .
                                    tableData($aPendingDocumentList[$i]->getDays());
    $sHtml = $sHtml .    tableRow("", "", $row); 
                         }
    $sHtml = $sHtml . 
                     stopTable() . 
                 endTableRowCell() .
                 // checked out documents
                 startTableRowCell() .
                     startTable("0", "100%") .
                         tableRow("left", "#996600", tableHeading("sectionHeading", 2, "Checked Out Documents")) . 
                         tableRow("", "", checkedOutDocumentsHeaders());
                         // FIXME: replace with the real method when its implemented
                         // something like:
                         //    DocumentManager::getCheckoutDocuments();                         
                         $aCheckedOutDocumentList = getCheckedoutDocuments($_SESSION["userID"]);
                         for ($i = 0; $i < count($aCheckedOutDocumentList); $i++) {
                             $row = tableData($aCheckedOutDocumentList[$i]->getTitleLink()) .
                                    tableData($aCheckedOutDocumentList[$i]->getDays());
    $sHtml = $sHtml .    tableRow("", "", $row); 
                         }
    $sHtml = $sHtml . 
                     stopTable() . 
                 endTableRowCell() .
                 
                 // subscription alerts
                 startTableRowCell() .
                     startTable("0", "100%") .
                         tableRow("left", "#996600", tableHeading("sectionHeading", 3, "Subscriptions Alerts")) . 
                         tableRow("", "", subscriptionDocumentsHeaders());
                         // FIXME: replace with the real method when its implemented
                         // something like:
                         //    SubscriptionManager::getAlerts();
                         $aSubscriptionList = getSubscriptionDocuments($_SESSION["userID"]);
                         for ($i = 0; $i < count($aSubscriptionList); $i++) {
                             $row = tableData($aSubscriptionList[$i]->getTitleLink()) .
                                    tableData($aSubscriptionList[$i]->getStatus()) .
                                    tableData($aSubscriptionList[$i]->getDays());
    $sHtml = $sHtml .    tableRow("", "", $row); 
                         }
    $sHtml = $sHtml . 
                     stopTable() . 
                 endTableRowCell() .
                 
             stopTable();
    
    $oContent = new PatternCustom();
    $oContent->setHtml($sHtml);
    
    /* get a page */
    $tmp = new PatternMainPage();
    
    /* put the page together */
    $tmp->setNorthWestPayload($img);
    $tmp->setNorthPayload($oPatternTableLinks);
    $tmp->setCentralPayload($oContent);
    $tmp->setFormAction("dashboard.php");
    $tmp->render();
        
} else {
    // redirect to no permission page
    redirect("$default->owl_ui_url/noAccess.php");
}
?>

