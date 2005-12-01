<?php

require_once(KT_DIR . '/plugins/ktstandard/contents/BaseIndexer.php');

class KTWordIndexerTrigger extends KTBaseIndexerTrigger {
    var $mimetypes = array(
       'application/msword' => true,
    );
    var $command = 'catdoc';          // could be any application.
    var $args = array("-w");
    var $use_pipes = true;
    
    // see BaseIndexer for how the extraction works.
}

?>
