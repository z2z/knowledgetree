<?php


require_once('search/SearchCommandParser.php');
require_once('search/SearchCommandLexer.php');
require_once('search/fieldRegistry.inc.php');
require_once('search/expr.inc.php');
require_once(KT_LIB_DIR . '/security/Permission.inc');

function rank_compare($a, $b)
{
	if ($a->Rank == $b->Rank)
	{
		if ($a->Title == $b->Title)
			return 0;
		// we'll show docs in ascending order by name
		return ($a->Title < $b->Title)?-1:1;
	}
	// we want to be in descending order
	return ($a->Rank > $b->Rank)?-1:1;
}

function search_alias_compare($a, $b)
{
	if ($a['alias'] == $b['alias']) return 0;
	return ($a['alias'] < $b['alias'])?-1:1;
}

class SearchHelper
{
	public static function checkOpenOfficeAvailablity()
	{
		$config =& KTConfig::getSingleton();
		$ooHost = $config->get('openoffice/host', 'localhost');
		$ooPort = $config->get('openoffice/port', 8100);

		$connection = @fsockopen($ooHost, $ooPort,$errno, $errstr, 2);
		if (false === $connection)
		{
			return  sprintf(_kt("Cannot connect to Open Office Server on host '%s:%s'.\nPlease consult the Administrator Guide for more information on configuring Open Office Server."), $ooHost, $ooPort);
		}
		fclose($connection);

		return null;
	}

	public static function getSavedSearchEvents()
	{
		// TODO
		$sql = "";
	}

	public static function getJSdocumentTypesStruct($documenttypes = null)
	{
		if (is_null($documenttypes))
		{
			$documenttypes = SearchHelper::getDocumentTypes();
		}
		$dt=0;
		$documenttypes_str = '[';
		foreach($documenttypes as $user)
		{
			if ($dt++ > 0) $documenttypes_str .= ',';
			$id=$user['id'];
			$name=$user['name'];

			$documenttypes_str .= "\n\t{id: \"$id\", name: \"$name\"}";
		}
		$documenttypes_str .= ']';
		return $documenttypes_str;

	}

	public static function getJSmimeTypesStruct($mimetypes = null)
	{
		if (is_null($mimetypes))
		{
			$mimetypes = SearchHelper::getMimeTypes();
		}
		$mt=0;
		$mimetypes_str = '[';
		foreach($mimetypes as $user)
		{
			if ($mt++ > 0) $mimetypes_str .= ',';

			$name=$user['name'];

			$mimetypes_str .= "\n\t\"$name\"";
		}
		$mimetypes_str .= ']';

		return $mimetypes_str;
	}

	public static function getJSusersStruct($users = null)
	{
		if (is_null($users))
		{
			$users = SearchHelper::getUsers();
		}

		$uo=0;
		$users_str = '[';
		foreach($users as $user)
		{
			if ($uo++ > 0) $users_str .= ',';
			$id=$user['id'];
			$name=$user['name'];

			$users_str .= "\n\t{id: \"$id\", name: \"$name\"}";
		}
		$users_str .= ']';

		return $users_str;
	}

	public static function getJSfieldsStruct($fields = null)
	{
		if (is_null($fields))
		{
			$fields = SearchHelper::getSearchFields();
		}
        $fields_str = '[';
		$fo=0;
		foreach($fields as $field)
		{
			if ($fo++ > 0) $fields_str .= ',';
			$alias = $field['alias'];
			$display = $field['display'];
			$type = $field['type'];
			$fields_str .= "\n\t{alias: \"$alias\", name: \"$display\", type:\"$type\"}";
		}
		$fields_str .= ']';

		return $fields_str;
	}

	public static function getJSworkflowStruct($workflows = null)
	{
		if (is_null($workflows))
		{
			$workflows = SearchHelper::getWorkflows();
		}

		$workflow_str = '[';
        $wo=0;
        foreach($workflows as $workflow)
        {
        	if ($wo++ > 0) $workflow_str .= ',';
        	$wid = $workflow['id'];
        	$name = $workflow['name'];

        	$workflow_str .= "\n\t{id:\"$wid\", name: \"$name\", states: [ ";

        	$result['workflows'][$wid] = $workflow;
        	$states = SearchHelper::getWorkflowStates($wid);
        	$result['workflows'][$wid]['states'] = array();
        	$so=0;
        	foreach($states as $state)
        	{
        		if ($so++>0) $workflow_str .= ',';
				$sid = $state['id'];
				$name=$state['name'];
				$result['workflows'][$wid]['states'][$sid] = $state;
				$workflow_str .= "\n\t\t{id:\"$wid\", name: \"$name\"}";
        	}
        	$workflow_str .= ']}';
        }
        $workflow_str .= ']';

        return $workflow_str;
	}

	public static function getJSfieldsetStruct($fieldsets = null)
	{
		if (is_null($fieldsets))
		{
			$fieldsets = SearchHelper::getFieldsets();
		}

		$fieldset_str = '[';
		$fso=0;
        foreach($fieldsets as $fieldset)
        {
        	$fsid=$fieldset['id'];
        	$name = $fieldset['name'];
        	$desc = $fieldset['description'];
        	if ($fso++>0) $fieldset_str .= ',';
        	$fieldset_str .= "\n\t{id:\"$fsid\",name:\"$name\",description:\"$desc\", fields: [";


        	$result['fieldsets'][$fsid] = $fieldset;
        	$fields = SearchHelper::getFields($fsid);
			$result['fieldsets'][$fsid]['fields'] = array();
			$fo=0;
        	foreach($fields as $field)
        	{
        		if ($fo++ >0) $fieldset_str .= ',';
				$fid = $field['id'];
				$name= $field['name'];
				$desc = $field['description'];
				$datatype=$field['datatype'];
				$control=$field['control'];
        		$fieldset_str .= "\n\t\t{id:\"$fid\", name:\"$name\", description:\"$desc\", datatype:\"$datatype\", control:\"$control\", options: [";
        		$options = $field['options'];
        		$oo = 0;
        		if (!is_array($options))
        		{
        			$options = array();
        		}
        		foreach($options as $option)
        		{
        			if ($oo++ > 0) $fieldset_str .= ',';
        			$oid = $option['id'];
					$name= $option['name'];
        			$fieldset_str .= "\n\t\t\t{id: \"$oid\", name: \"$name\"}";
        		}
        		$fieldset_str .= ']}';
        		$result['fieldsets'][$fsid]['fields'][$fid] = $field;
        	}
        	$fieldset_str .= ']}';

        }
        $fieldset_str .= ']';

        return $fieldset_str;
	}


	public static function getSavedSearches($userID)
	{
		if (empty($default->db))
		{
			return array();
		}

		$sql = "SELECT id, name FROM search_saved WHERE type='S'";

		// if we are not the system admin, then we get only ours or shared searches
		if (!Permission::userIsSystemAdministrator($userID))
		{
			$sql .= "  and ( user_id=$userID OR shared=1 ) ";
		}

		$rs = DBUtil::getResultArray($sql);
		return $rs;
	}

	public static function getSearchFields()
	{
		$registry = ExprFieldRegistry::getRegistry();

		$fields = $registry->getFields();

		$results = array();
		foreach($fields as $field )
		{
			$type = $field->getInputRequirements();
			$type = $type['value']['type'];
			$results[] = array('alias'=>$field->getAlias(), 'display'=>$field->getDisplay(), 'type'=>$type);
		}
		usort($results, search_alias_compare);
		return $results;
	}

	public static function getFolder($folderID, $userid)
	{
		$folder = Folder::get($folderID + 0);
		if (PEAR::isError($folder))
		{
			return $folder;
		}

		if (!Permission::userHasFolderReadPermission($folder))
		{
			return new PEAR_Error(_kt('no permission to read folder'));
		}

		$sql = "SELECT id, name FROM folders WHERE parent_id=$folderID ORDER BY name";
		$rs = DBUtil::getResultArray($sql);
		if (PEAR::isError($rs))
		{
			return $rs;
		}

		$folders = array();

		foreach($rs as $folder)
		{
			$fobj = Folder::get($folder['id']);

			if (Permission::userHasFolderReadPermission($fobj))
			{
				$folders[] = $folder;
			}
		}
		return $folders;
	}

	public static function getFields($fieldsetID)
	{
		if ($fieldsetID < 0)
		{
			$documentTypeID = sanitizeForSQL(-$fieldsetID);
			$sql = "SELECT
						df.id, df.name, df.data_type, df.has_lookup, df.has_lookuptree, df.description
					FROM
						document_type_fields_link dtfl
						INNER JOIN  document_fields df on dtfl.field_id=df.id
					WHERE
						dtfl.document_type_id=$documentTypeID";


		}
		else
		{
			$fieldsetID = sanitizeForSQL($fieldsetID);
			$sql = "SELECT id, name, data_type, has_lookup, has_lookuptree, description FROM document_fields WHERE parent_fieldset=$fieldsetID";
		}

		$rs = DBUtil::getResultArray($sql);
		if (PEAR::isError($rs))
		{
			return $rs;
		}
		if (count($rs) == 0)
		{
			return new PEAR_Error(_kt('Fieldset was not found'));
		}

		$result=array();
		foreach($rs as $item)
		{
			$fieldid=$item['id'];
			$type='normal';
			$options = array();
			$haslookup =$item['has_lookup'] + 0 > 0;
			$hastree = ($item['has_lookuptree']+0 > 1);

			if ($haslookup || $hastree)
			{
				$type = 'lookup';
				$sql = "select id, name from metadata_lookup where document_field_id=$fieldid";
				$options = DBUtil::getResultArray($sql);

			}
			/*if ($hastree)
			{
				$type = 'lookup';
				$sql = "select id, name, metadata_lookup_tree_parent as parent from metadata_lookup_tree where document_field_id=$fieldid";
				$options = DBUtil::getResultArray($sql);
			}*/

			if ($item['data_type'] == 'USERLIST')
			{
				$type = 'lookup';
				$sql = "SELECT id, name from users WHERE disabled=0";
				$options = DBUtil::getResultArray($sql);
			}

			$ritem = array(
				'id'=>$fieldid,
				'name'=>$item['name'],
				'description'=>$item['description'],
				'datatype'=>$item['data_type'],
				'control'=>$type,
				'options'=>$options
			);

			$result[]= $ritem;
		}
		return $result;
	}

	public static function getFieldsets()
	{
		$sql = "SELECT id, name, description FROM fieldsets WHERE disabled=0";
		$rs = DBUtil::getResultArray($sql);

		return $rs;
	}

	public static function getDocumentTypeFieldsets($documentTypeID)
	{
		$documentTypeID = sanitizeForSQL($documentTypeID);
		$sql = "SELECT
					fs.id, fs.name, fs.description
				FROM
					fieldsets fs LEFT JOIN document_type_fieldsets_link dtfl ON dtfl.fieldset_id=fs.id
				WHERE
					fs.disabled=0 AND (dtfl.document_type_id=$documentTypeID OR fs.is_generic=1)";
		$rs = DBUtil::getResultArray($sql);

		return $rs;
	}

	public static function getDocumentTypes()
	{
		$sql = "SELECT id, name from document_types_lookup WHERE disabled=0";
		$rs = DBUtil::getResultArray($sql);
		return $rs;
	}

	public static function getMimeTypes() {
		$sql = "SELECT DISTINCT mimetypes as name FROM mime_types order by mimetypes ";
		$rs = DBUtil::getResultArray($sql);
		return $rs;
	}

	public static function getWorkflows()
	{
		$sql = "SELECT id, human_name as name FROM workflows WHERE enabled=1";
		$rs = DBUtil::getResultArray($sql);
		return $rs;
	}

	public static function getUsers()
	{
		$sql = "SELECT id, name FROM users WHERE disabled=0";
		$rs = DBUtil::getResultArray($sql);
		return $rs;
	}

	public static function getWorkflowStates($workflowid)
	{
		$sql = "SELECT id,human_name as name FROM workflow_states WHERE workflow_id=$workflowid";
		$rs = DBUtil::getResultArray($sql);
		return $rs;
	}

}


function getExpressionLocalityString($expr_str, $locality, $length, $start_offset=10)
{
    if ($locality - $start_offset < 0)
    {
        $locality  = 0;
    }
    else
    {
        $locality -= $start_offset;
    }

    return substr($expr_str, $locality, $length);
}

/**
 * This parses a query.
 *
 * @param OpExpr $expr_str
 * @return array of MatchResult
 */
function parseExpression($expr_str)
{
    $parser = new SearchCommandParser();
    $lexer = new SearchCommandLexer($expr_str);

//    $parser->PrintTrace();
    $use_internal=false;

    try
    {
        while ($lexer->yylex())
        {
            //print "\n" . $lexer->value  . "\n";

            $parser->doParse($lexer->token, $lexer->value);

            if (!$parser->isExprOk())
            {
                $use_internal=true;
                $expr_str=getExpressionLocalityString($expr_str, $lexer->offset, 20);
                throw new Exception(sprintf(_kt("Parsing problem near '%s' in '%s' of expression."),$lexer->value,$expr_str));
            }
        }

        // we are now done
        $parser->doParse(0, 0);

        if (!$parser->isExprOk())
        {
            $use_internal=true;
            $expr_str=getExpressionLocalityString($expr_str, $lexer->offset, 20);
            throw new Exception(sprintf(_kt("There is a problem parsing the expression '%s'"),$expr_str));
        }

    }
    catch(ResolutionException $e)
    {
        throw $e;
    }
    catch(Exception $e)
    {
        if ($use_internal)
        {
            throw $e;
        }
        $expr_str=getExpressionLocalityString($expr_str, $lexer->offset, 20);
        throw new Exception(sprintf(_kt("Parsing problem near '%s' of expression '%s'."), $lexer->value, $expr_str));
    }

    return $parser->getExprResult();
}



?>