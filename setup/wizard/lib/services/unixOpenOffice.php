<?php
/**
* Unix Agent Service Controller. 
*
* KnowledgeTree Community Edition
* Document Management Made Simple
* Copyright(C) 2008,2009 KnowledgeTree Inc.
* Portions copyright The Jam Warehouse Software(Pty) Limited
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

class unixOpenOffice extends unixService {
	// path to office
	private $path;
	// host
	private $host;
	// pid running
	private $pidFile;
	// port to bind to
	private $port;
	// bin folder
	private $bin;
	// office executable
	private $soffice;
	public $name = "KTOpenOffice";
	
	/**
	* Load defaults needed by service
	*
	* @author KnowledgeTree Team
	* @access public
	* @param string
	* @return void
 	*/
	public function load($options = null) {
		if(isset($options['binary'])) {
			$this->setBin($options['binary']);
		} else {
			$this->setBin("/usr/lib/openoffice/program/soffice.bin");
		}
		$this->setPort("8100");
		$this->setHost("localhost");
//		$this->soffice = $this->util->getOpenOffice();
	}
	
	private function setPort($port = "8100") {
		$this->port = $port;
	}
	
	public function getPort() {
		return $this->port;
	}
	
	private function setHost($host = "localhost") {
		$this->host = $host;
	}
	
	public function getHost() {
		return $this->host;
	}
	
	private function setBin($bin = "soffice") {
		$this->bin = $bin;
	}
	
	public function getBin() {
		return $this->bin;
	}
	
    public function install() {
    	$status = $this->status();
    	if($status == '') {
			return $this->start();
    	} else {
    		return $status;
    	}
    }
    
    public function status($updrade = false) {
    	sleep(1);
		$cmd = "ps ax | grep soffice";
		$response = $this->util->pexec($cmd);
    	if(is_array($response['out'])) {
    		if(count($response['out']) > 1) {
    			foreach ($response['out'] as $r) {
    				preg_match('/grep/', $r, $matches); // Ignore grep
    				if(!$matches) {
    					return 'STARTED';
    				}
    			}
    		} else {
    			return '';
    		}
    	}

    	return '';
    }
    
	/**
	* Start Service
	*
	* @author KnowledgeTree Team
	* @access public
	* @param none
	* @return boolean
 	*/
    public function start() {
    	$state = $this->status();
    	if($state != 'STARTED') {
	    	$cmd = "\"{$this->util->getJava()}\" -cp \"".SYS_DIR."\" openOffice ".$this->getBin();
	    	if(DEBUG) {
	    		echo "Command : $cmd<br/>";
	    		return ;
	    	}
	    	$response = $this->util->pexec($cmd);
//	    	$state = $this->status();
//	    	if($state != 'STARTED') {
//	    		$cmd = "nohup ".$this->getBin()." -nofirststartwizard -nologo -headless -accept=\"socket,host=localhost,port=8100;urp;StarOffice.ServiceManager\" &1> /dev/null &";
//	    		$response = $this->util->pexec($cmd);
//	    	}
	    	return $response;
    	} elseif ($state == '') {
    		// Start Service
    		return true;
    	} else {
    		// Service Running Already
    		return true;
    	}
    	
    	return false;
    }
    
	/**
	* Stop Service
	*
	* @author KnowledgeTree Team
	* @access public
	* @param none
	* @return array
 	*/
    function stop() {
    	$cmd = "pkill -f ".$this->soffice;
    	$response = $this->util->pexec($cmd);
		return $response;
	}
	
	function uninstall() {
		$this->stop();
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function unixGetStopMsg($installDir) {
		return "Execute from terminal : $installDir/dmsctl.sh stop soffice";
	}
	
	public function windowsGetStopMsg($installDir) {
		return "Execute from terminal : $installDir/dmsctl.sh stop soffice";
	}
}
?>