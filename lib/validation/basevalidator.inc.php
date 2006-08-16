<?php

/* a pluggable validation environment */

class KTValidator {
    var $sNamespace;
    
    var $sInputVariable;    // what name to look for in "data"
    var $sBasename;         // the key to use for errors
    var $sOutputVariable;   // where to put the output, if any
    var $bProduceOutput;    // should be produce an output in "results"
    var $bRequired = false;
    
    var $aOptions;
    
    function configure($aOptions) {
        $this->sInputVariable = KTUtil::arrayGet($aOptions, 'test');
        $this->sBasename = KTUtil::arrayGet($aOptions, 'basename', $this->sInputVariable);      
        $this->sOutputVariable = KTUtil::arrayGet($aOptions, 'output');
        $this->bProduceOutput = !empty($this->sOutputVariable);
        $this->bRequired = KTUtil::arrayGet($aOptions, 'required', false , false);
        
        $this->aOptions = $aOptions;
    }
    
    function validate($data) {
        $res = array();
        
        $res['results'] = array();
        $res['errors'] = array();
        
        return $res;
    }
}

?>