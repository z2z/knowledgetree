<?php
require_once("../../../../config/dmsDefaults.php");
require_once("$default->fileSystemRoot/presentation/Html.inc");

/**
 * $Id$
 *
 * Displays the administration page.
 *
 * Licensed under the GNU GPL. For full terms see the file DOCS/COPYING.
 *
 * @version $Revision$
 * @author Michael Joseph <michael@jamwarehouse.com>, Jam Warehouse (Pty) Ltd, South Africa
 * @package presentation.lookAndFeel.knowledgeTree.administration
 */

/**
 * Display the heading for the admin section
 */
function renderAdminHeading($sHeading, $sSectionName = "") {
    $sCenter .= renderHeading("Administration");
    $sCenter .= "<table width=\"600\">\n";
    $sCenter .=	"<tr/><tr/><tr><td><b> Welcome to the Administration Section</b></td></tr>\n";
    $sCenter .=	"<tr><td>Please make a selection from the sidemenu.</td></tr>\n";
    $sCenter .=	"</table>\n";
    return $sCenter;	
}

if (checkSession()) {
	require_once("$default->fileSystemRoot/presentation/webpageTemplate.inc");
	
	if (!isset($sectionName)) {
		$sectionName = "Administration";
	}   
    $sCenter .= "<table width=\"600\">\n";
    $sCenter .= renderAdminHeading("Administration");	        
    $sCenter .=	"</table>\n";

	$oPatternCustom = & new PatternCustom();
	$oPatternCustom->setHtml($sCenter);
	$main->setCentralPayload($oPatternCustom);
	$main->render();
}
?>
