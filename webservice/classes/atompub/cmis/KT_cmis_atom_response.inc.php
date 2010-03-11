<?php

require_once(KT_ATOM_LIB_FOLDER.'KT_atom_response.inc.php');

class KT_cmis_atom_response extends KT_atom_response {

    static protected $workspace = null;
    
	public function __construct($baseURI = null)
    {
		parent::__construct();

        // require the workspace for creating links within responses
        $queryArray = split('/', trim($_SERVER['QUERY_STRING'], '/'));
        $this->workspace = strtolower(trim($queryArray[0]));
	}

    function getWorkspace()
    {
        return $this->workspace;
    }
    
    protected function constructFeedHeader(){
		$feed = $this->newElement('feed');
		$feed->appendChild($this->newAttr('xmlns','http://www.w3.org/2005/Atom'));
		$this->feed = &$feed;
        $this->DOM->appendChild($this->feed);
	}
    
    public function &newEntry()
    {
		$entry = $this->newElement('atom:entry');
		$this->feed->appendChild($entry);
		
		return $entry;
	}

    // TODO try to get rid of this function
    function appendChild($element)
    {
        $this->feed->appendChild($element);
    }

}

class KT_cmis_atom_Response_GET extends KT_cmis_atom_response{}
class KT_cmis_atom_Response_PUT extends KT_cmis_atom_response{}
class KT_cmis_atom_Response_POST extends KT_cmis_atom_response{}
class KT_cmis_atom_Response_DELETE extends KT_cmis_atom_response{}

?>