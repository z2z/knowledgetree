<?php

/**
 * Document access/management functions for CMIS AtomPub
 * Output returned as an AtomPub feed
 */

include 'services/cmis/RepositoryService.inc.php';
include 'services/cmis/ObjectService.inc.php';

$RepositoryService = new RepositoryService();
$repositories = $RepositoryService->getRepositories();
$repositoryId = $repositories[0]['repositoryId'];

$ObjectService = new ObjectService();
$ObjectService->startSession($username, $password);

$output = CMISDocumentFeed::getDocumentFeed($ObjectService, $repositoryId, $query[2]);

class CMISDocumentFeed {

    /**
     * Retrieves data about a specific document
     *
     * @param object $ObjectService The CMIS service
     * @param string $repositoryId
     * @param string $documentId
     * @return string CMIS AtomPub feed
     */
    static public function getDocumentFeed($ObjectService, $repositoryId, $documentId)
    {
//        $documentData = $ObjectService->getProperties($repositoryId, $documentId, false, false);
//
//        $feed = new KTCMISAPPFeed(KT_APP_BASE_URI, 'Root Folder Children', null, null, null,
//                                  'urn:uuid:' . $cmisEntry['properties']['ObjectId']['value'] . '-children');
//
//        foreach($entries as $cmisEntry)
//        {
//            $entry = $feed->newEntry();
//            $feed->newId('urn:uuid:' . $cmisEntry['properties']['ObjectId']['value'] . '-'
//                                . strtolower($cmisEntry['properties']['ObjectTypeId']['value']), $entry);
//
//            // links
//            if (strtolower($cmisEntry['properties']['ObjectTypeId']['value']) == 'folder')
//            {
//                $link = $feed->newElement('link');
//                $link->appendChild($feed->newAttr('rel','cmis-children'));
//                $link->appendChild($feed->newAttr('href', CMIS_BASE_URI
//                                                        . strtolower($cmisEntry['properties']['ObjectTypeId']['value'])
//                                                        . '/' . $cmisEntry['properties']['ObjectId']['value'] . '/children'));
//                $entry->appendChild($link);
//                $link = $feed->newElement('link');
//                $link->appendChild($feed->newAttr('rel','cmis-descendants'));
//                $link->appendChild($feed->newAttr('href', CMIS_BASE_URI
//                                                        . strtolower($cmisEntry['properties']['ObjectTypeId']['value'])
//                                                        . '/' . $cmisEntry['properties']['ObjectId']['value'] . '/descendants'));
//                $entry->appendChild($link);
//            }
//            $link = $feed->newElement('link');
//            $link->appendChild($feed->newAttr('rel','cmis-type'));
//            $link->appendChild($feed->newAttr('href', CMIS_BASE_URI . 'type/' . strtolower($cmisEntry['properties']['ObjectTypeId']['value'])));
//            $entry->appendChild($link);
//            $link = $feed->newElement('link');
//            $link->appendChild($feed->newAttr('rel','cmis-repository'));
//            $link->appendChild($feed->newAttr('href', CMIS_BASE_URI . 'repository'));
//            $entry->appendChild($link);
//
//            $entry->appendChild($feed->newElement('summary', $cmisEntry['properties']['Name']['value']));
//            $entry->appendChild($feed->newElement('title', $cmisEntry['properties']['Name']['value']));
//
//            // main CMIS entry
//            $objectElement = $feed->newElement('cmis:object');
//            $propertiesElement = $feed->newElement('cmis:properties');
//            // <cmis:propertyId cmis:name="ObjectId"><cmis:value>D2</cmis:value></cmis:propertyId>
//
//            foreach($cmisEntry['properties'] as $propertyName => $property)
//            {
//                $propElement = $feed->newElement('cmis:' . $property['type']);
//                $propElement->appendChild($feed->newAttr('cmis:name', $propertyName));
//                $feed->newField('value', CMISUtil::boolToString($property['value']), $propElement);
//                $propertiesElement->appendChild($propElement);
//            }
//
//            $objectElement->appendChild($propertiesElement);
//            $entry->appendChild($objectElement);
//        }
//
//        // <cmis:hasMoreItems>false</cmis:hasMoreItems>
//
//        $output = $feed->getAPPdoc();
    $output = '<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:cmis="http://www.cmis.org/2008/05">
<entry>
<author><name>admin</name></author>
<content type="application/pdf" src="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/content.h4555-cmis-so.pdf"/><id>urn:uuid:2df9d676-f173-47bb-8ec1-41fa1186b66d</id>
<link rel="self" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d"/>
<link rel="enclosure" type="application/pdf" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/content.h4555-cmis-so.pdf"/><link rel="edit" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d"/>
<link rel="edit-media" type="application/pdf" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/content.h4555-cmis-so.pdf"/><link rel="cmis-allowableactions" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/permissions"/>
<link rel="cmis-relationships" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/associations"/>
<link rel="cmis-parents" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/parents"/>
<link rel="cmis-allversions" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/versions"/>
<link rel="cmis-stream" type="application/pdf" href="http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/content.h4555-cmis-so.pdf"/><link rel="cmis-type" href="http://127.0.0.1:8080/alfresco/service/api/type/document"/>
<link rel="cmis-repository" href="http://127.0.0.1:8080/alfresco/service/api/repository"/>
<published>2009-06-23T09:40:47.889+02:00</published>
<summary></summary>
<title>h4555-cmis-so.pdf</title>
<updated>2009-06-23T09:40:58.524+02:00</updated>
<cmis:object>
<cmis:properties>
<cmis:propertyId cmis:name="ObjectId"><cmis:value>workspace://SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d</cmis:value></cmis:propertyId>
<cmis:propertyString cmis:name="BaseType"><cmis:value>document</cmis:value></cmis:propertyString>
<cmis:propertyString cmis:name="ObjectTypeId"><cmis:value>document</cmis:value></cmis:propertyString>
<cmis:propertyString cmis:name="CreatedBy"><cmis:value>admin</cmis:value></cmis:propertyString>
<cmis:propertyDateTime cmis:name="CreationDate"><cmis:value>2009-06-23T09:40:47.889+02:00</cmis:value></cmis:propertyDateTime>
<cmis:propertyString cmis:name="LastModifiedBy"><cmis:value>admin</cmis:value></cmis:propertyString>
<cmis:propertyDateTime cmis:name="LastModificationDate"><cmis:value>2009-06-23T09:40:58.524+02:00</cmis:value></cmis:propertyDateTime>
<cmis:propertyString cmis:name="Name"><cmis:value>h4555-cmis-so.pdf</cmis:value></cmis:propertyString>
<cmis:propertyBoolean cmis:name="IsImmutable"><cmis:value>false</cmis:value></cmis:propertyBoolean>
<cmis:propertyBoolean cmis:name="IsLatestVersion"><cmis:value>true</cmis:value></cmis:propertyBoolean>
<cmis:propertyBoolean cmis:name="IsMajorVersion"><cmis:value>false</cmis:value></cmis:propertyBoolean>
<cmis:propertyBoolean cmis:name="IsLatestMajorVersion"><cmis:value>false</cmis:value></cmis:propertyBoolean>
<cmis:propertyString cmis:name="VersionLabel"/>
<cmis:propertyId cmis:name="VersionSeriesId"><cmis:value>workspace://SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d</cmis:value></cmis:propertyId>
<cmis:propertyBoolean cmis:name="IsVersionSeriesCheckedOut"><cmis:value>false</cmis:value></cmis:propertyBoolean>
<cmis:propertyString cmis:name="VersionSeriesCheckedOutBy"/>
<cmis:propertyId cmis:name="VersionSeriesCheckedOutId"/>
<cmis:propertyString cmis:name="CheckinComment"/>
<cmis:propertyInteger cmis:name="ContentStreamLength"><cmis:value>343084</cmis:value></cmis:propertyInteger>
<cmis:propertyString cmis:name="ContentStreamMimeType"><cmis:value>application/pdf</cmis:value></cmis:propertyString>
<cmis:propertyString cmis:name="ContentStreamFilename"><cmis:value>h4555-cmis-so.pdf</cmis:value></cmis:propertyString>
<cmis:propertyString cmis:name="ContentStreamURI"><cmis:value>http://127.0.0.1:8080/alfresco/service/api/node/workspace/SpacesStore/2df9d676-f173-47bb-8ec1-41fa1186b66d/content.h4555-cmis-so.pdf</cmis:value></cmis:propertyString>
</cmis:properties>
</cmis:object>
<cmis:terminator/>
<app:edited>2009-06-23T09:40:58.524+02:00</app:edited>
<alf:icon>http://127.0.0.1:8080/alfresco/images/filetypes/pdf.gif</alf:icon>
</entry>
</feed>';

        return $output;
    }

}

?>
