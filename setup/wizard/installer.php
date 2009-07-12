<?php
/**
* Installer Controller.
*
* KnowledgeTree Community Edition
* Document Management Made Simple
* Copyright (C) 2008,2009 KnowledgeTree Inc.
* Portions copyright The Jam Warehouse Software (Pty) Limited
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
*
* @copyright 2008-2009, KnowledgeTree Inc.
* @license GNU General Public License version 3
* @author KnowledgeTree Team
* @package Installer
* @version Version 0.1
*/

include("path.php");
include("session.php");
require_once("template.inc");
require_once("step_action.php");

class Installer {
	/**
	* Reference to simple xml object
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var object SimpleXMLElement 
	*/
    protected $simpleXmlObj = null;
    
	/**
	* Reference to step action object
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var object StepAction
	*/
    protected $stepAction = null;
    
	/**
	* List of installation steps as strings
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var array string
	*/
    protected $stepClassNames = array();
    
	/**
	* List of installation steps as human readable strings
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var array string
	*/
	protected $stepNames = array();
	
	/**
	* Flag if a step object needs confirmation
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var array boolean
	*/
    protected $stepConfirmation = false;
    
	/**
	* List of installation steps as human readable strings
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var array string
	*/
	protected $stepObjects = array();
	
	/**
	* Reference to session object
	*
	* @author KnowledgeTree Team
	* @access protected
	* @var object Session
	*/
    protected $session = null;

	/**
	* Constructs installation object
	*
	* @author KnowledgeTree Team
	* @access public
	* @param object Session $session Instance of the Session object
 	*/
    public function __construct($session) {
        $this->session = $session;
    }

	/**
	* Main control to handle the flow of install
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return void
	*/
    public function step() {
        $this->setXmlSteps(); // Xml steps
        $this->setSteps(); // String steps
        $response = $this->landing();
        switch($response) {
            case 'next':
                $this->proceed(); // Load next window
            	break;

            case 'previous':
                $this->backward(); // Load previous window
            	break;
            	
            case 'confirm':
                $this->stepConfirmation = true;
                $this->landing();
            	break;
            	
            case 'error':
                $this->landing(); // Load landing with errors
            	break;
            	
            case 'landing':
                $this->landing(); // Load landing
            	break;
            	
            case 'install':
                $this->runStepsInstallers(); // Load landing
            	break;
            	
            default:
                die("That was unexpected!"); // No class response
            	break;
        }
        $this->stepAction->paintAction(); // Display step
    }

	/**
	* Install steps
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return void
	*/
    function runStepsInstallers() {
    	foreach ($this->getSteps() as $className) {
    		$stepAction = new stepAction($className); // Instantiate a step action
    		$class = $stepAction->createStep(); // Get step class
    		if($class->runInstall()) { // Check if step needs to be installed
				$class->setDataFromSession($className); // Set Session Information
				$class->setDBConfig(); // Set any posted variables
				$class->installStep(); // Run install step
    		}
    	}
    }
    
	/**
	* Read xml configuration file
	*
	* @author KnowledgeTree Team
	* @param string $name of config file
	* @access public
	* @return simple xml object $simplexml
	*/
    public function readXml($name = "config.xml") {
    	try {
        	$simplexml = @simplexml_load_file(CONF_DIR.$name);
        	
        	return $simplexml;
    	} catch (Exception $e) {
    		return "Error loading file : $e";
    	}
    }

	/**
	* Checks if first step of installer
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return boolean
	*/
    public function _firstStep() {
        if(isset($_GET['step_name'])) {
            return false;
        }
        return true;
    }

	/**
	* Returns next step
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return string
	*/
    public function getNextStep() {
        return $this->getStepName(1);
    }

	/**
	* Returns previous step
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return string
	*/
    public function getPreviousStep() {
        return $this->getStepName(-1);
    }

	/**
	* Returns the step name, given a position
	*
	* @author KnowledgeTree Team
	* @param integer $pos current position
	* @access public
	* @return string $name
	*/
    public function getStepName($pos = 0) {
        if($this->_firstStep()) {
            $step = (string) $this->simpleXmlObj->steps->step[0];
        } else {
            $pos += $this->getStepPosition();
            $step = (string) $this->simpleXmlObj->steps->step[$pos];
        }

        return $step;
    }

	/**
	* Returns the step number
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return integer $pos
	*/
    public function getStepPosition() {
        $pos = 0;
        foreach($this->simpleXmlObj->steps->step as $d_step) {
            $step = (string) $d_step;
            if ($step == $_GET['step_name']) {
                   break;
            }
            $pos++;
        }
        if(isset($_GET['step'])) {
            if($_GET['step'] == "next")
                $pos = $pos+1;
            else
                $pos = $pos-1;
        }

        return $pos;
    }
    
	/**
	* Returns the step names for classes
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return array
	*/
    public function getSteps() {
        return $this->stepClassNames;
    }

	/**
	* Returns the steps as human readable string
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return array
	*/
    public function getStepNames() {
        return $this->stepNames;
    }

	/**
	* Dump of SESSION 
	*
	* @author KnowledgeTree Team
	* @param none
	* @access public
	* @return array
	*/
    public function showSession() {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }
    
	/**
	* Executes next step
	*
	* @author KnowledgeTree Team
	* @param none
	* @access private
	* @return string
	*/
    private function proceed() {
        $step_name = $this->getNextStep();

        return $this->runStepAction($step_name);
    }

	/**
	* Executes previous step
	*
	* @author KnowledgeTree Team
	* @param none
	* @access private
	* @return string
	*/
    private function backward() {
        $step_name = $this->getPreviousStep();

        return $this->runStepAction($step_name);
    }

	/**
	* Executes step landing
	*
	* @author KnowledgeTree Team
	* @param none
	* @access private
	* @return string
	*/
    private function landing() {
        $step_name = $this->getStepName();

        return $this->runStepAction($step_name);
    }

	/**
	* Executes step based on step class name
	*
	* @author KnowledgeTree Team
	* @param string $step_name
	* @access private
	* @return string
	*/
    private function runStepAction($stepName) {
        $this->stepAction = new stepAction($stepName);
        $this->stepAction->setSteps($this->getSteps());
        $this->stepAction->setStepNames($this->getStepNames());
        $this->stepAction->setDisplayConfirm($this->stepConfirmation);
        $this->stepAction->loadSession($this->session);
        
        return $this->stepAction->doAction();
    }

	/**
	* Set steps in xml format
	*
	* @author KnowledgeTree Team
	* @param none
	* @access private
	* @return void
	*/
    private function setXmlSteps() {
        $this->simpleXmlObj = $this->readXml();
    }

	/**
	* Set steps class names in string format
	*
	* @author KnowledgeTree Team
	* @param none
	* @access private
	* @return void
	*/
    private function setSteps() {
        $this->stepClassNames = $this->xmlStepsToArray();
    }


	/**
	* Set steps as names
	*
	* @author KnowledgeTree Team
	* @param none
	* @access private
	* @return array $steps
	*/
    private function xmlStepsToArray() {
        $steps = array();
        foreach($this->simpleXmlObj->steps->step as $d_step) {
            $step_name = (string) $d_step[0];
            $steps[] = $step_name;
            $this->stepNames[$step_name] = (string) $d_step['name'];
        }

        return $steps;
    }

}
$ins = new installer(new Session()); // Instantiate the installer
$ins->step(); // Run step
//$ins->showSession();
?>