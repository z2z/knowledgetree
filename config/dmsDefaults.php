<?php

/**
 * $Id$
 *
 * Stores the defaults for the DMS application
 *
 * Copyright (c) 1999-2002 The Owl Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 */

// include the environment settings
require_once("environment.php");

$default->owl_graphics_url	= $default->owl_root_url . "/graphics";
$default->owl_LangDir		= $default->owl_fs_root . "/locale";
// change this to reflect a directory with a different look and feel
$default->owl_ui_directory  = $default->owl_fs_root . "/presentation/lookAndFeel/knowledgeTree";
$default->owl_ui_url  = $default->owl_root_url . "/presentation/lookAndFeel/knowledgeTree";


// Set to true to use the file system to store documents, false only uses the database
$default->owl_use_fs            = true;
//$default->owl_use_fs            = false;
// the Trailing Slash is important here.
//$default->owl_compressed_database = 1;

//****************************************************
// Pick your language system default language
// now each user can pick his language
// if they are allowed by the admin to change their
// preferences.
//****************************************************
// b5
// Chinese
// Danish
// Deutsch
// Dutch
// English
// Francais
// Hungarian
// Italian
// NewEnglish <-  NEW LOOK, English will be obsoleted in a future version
// Norwegian
// Portuguese
// Spanish

$default->owl_lang		= "NewEnglish";
$default->owl_notify_link       = "http://$_SERVER[SERVER_NAME]$default->owl_root_url/";

// Table mappings

// session information
$default->owl_sessions_table = "active_sessions";
// document type fields
$default->owl_fields_table = "document_fields";
// links document
$default->owl_document_fields_table = "document_fields_link";
// document transaction types
$default->owl_transaction_types_table = "document_transaction_types_lookup";
// document transactions
$default->owl_document_transactions_table = "document_transactions"; 
// links document types to document type fields
$default->owl_document_type_fields_table = "document_type_fields_link";
// document type information
$default->owl_document_types_table = "document_types_lookup";
// links documents to words
$default->owl_document_words_table = "document_words_link";
// stores documents
$default->owl_documents_table = "documents";
// stores folders 
$default->owl_folders_table = "folders";
// links folders to users (and roles) for approval collaboration
$default->owl_folders_user_roles_table	= "folders_users_roles_link";
// stores approval collaboration information- approval roles mapped to folders with order
$default->owl_groups_folders_approval_table	= "groups_folders_approval_link";
// links groups to folders
$default->owl_groups_folders_table	= "groups_folders_link";
// stores group information
$default->owl_groups_table	= "groups_lookup";
// links groups to units
$default->owl_groups_units_table = "groups_units_link";
// links
$default->owl_links_table = "links";
// Table with mime info
$default->owl_mime_table	= "mime_types";
// organisation information
$default->owl_organisations_table = "organisations_lookup";
// stores role information (name and access)
$default->owl_roles_table = "roles";
// stores document subscription information
$default->owl_subscriptions_table = "subscriptions"; 
// stores default system settings
$default->owl_system_settings_table = "system_settings"; 
// Table with unit information
$default->owl_units_table = "units";
// Table with user info
$default->owl_users_table	= "users";
// links groups to users
$default->owl_users_groups_table = "users_groups_link"; 
// Table with web documents info for web publishing
$default->owl_web_documents_table = "web_documents";
 // Table with web documents info for web publishing
$default->owl_web_documents_status_table = "web_documents_status_lookup";
// stores websites for web publishing
$default->owl_web_sites_table = "web_sites";
// stores indexed words 
$default->owl_words_lookup_table = "words_lookup";

// Change this to reflect the database you are using
require_once("$default->owl_fs_root/phplib/db_mysql.inc");
//require("$default->owl_fs_root/phplib/db_pgsql.inc");

// Change this to reflect the authentication method you are using
//require_once("$default->owl_fs_root/lib/LDAPAuthenticator.inc");
//require_once("$default->owl_fs_root/lib/Authenticator.inc");
$default->authentication_class = "DBAuthenticator";
require_once("$default->owl_fs_root/lib/authentication/$default->authentication_class.inc");

// logo file that must reside inside lang/graphics directory
$default->logo = "kt.jpg";

// BUG Number: 457588
// This is to display the version information in the footer
//$default->version = "owl 0.7 20021129";
$default->version = "owl-dms 1.0 @build-date@";
$default->phpversion = "4.0.2";

// session timeout (in seconds)
$default->owl_timeout = 1200;
$default->debug = True;

// define site mappings
require_once("$default->owl_fs_root/lib/session/SiteMap.inc");
$default->siteMap = new SiteMap();

// action, page, section, group with access

// general pages
$default->siteMap->addPage("login", "/presentation/login.php?loginAction=login", "General", "Anonymous", "");
$default->siteMap->addPage("loginForm", "/presentation/login.php?loginAction=loginForm", "General", "Anonymous", "login"); 
$default->siteMap->addPage("logout", "/presentation/logout.php", "General", "Anonymous", "logout");
$default->siteMap->addPage("dashboard", "/presentation/dashboardBL.php", "General", "Unit Administrators", "dashboard");

//pages for manage documents section
//$default->siteMap->addDefaultPage("browse", "/presentation/documentmanagement/browseBL.php", "Manage Documents", "Anonymous", "browse documents");
$default->siteMap->addDefaultPage("browse", "/manage.php", "Manage Documents", "Anonymous", "Manage documents");
$default->siteMap->addPage("addDocument", "/presentation/documentmanagement/addDocument.php", "Manage Documents", "Anonymous", "Add A Document");
$default->siteMap->addPage("addFolder", "/presentation/documentmanagement/addFolder.php", "Manage Documents", "Unit Administrators", "Add A Folder");
$default->siteMap->addPage("modifyFolderProperties", "/presentation/documentmanagement/modifyFolder.php", "Manage Documents", "Unit Administrators", "Modify Folder Properties");
$default->siteMap->addPage("deleteFolder", "/presentation/documentmanagement/deleteFolder.php", "Manage Documents", "Unit Administrators", "Delete A Folder");
$default->siteMap->addPage("moveFolder", "/presentation/documentmanagement/moveFolder.php", "Manage Documents", "Unit Administrators", "Move A Folder");


// pages for administration section
$default->siteMap->addDefaultPage("administration", "/admin.php", "Administration", "Unit Administrators", "Administration");
$default->siteMap->addPage("unitAdministration", "/presentation/unitAdmin.php", "Administration", "Unit Administrators", "Unit Administration");
$default->siteMap->addPage("systemAdministration", "/presentation/sysAdmin.php", "Administration", "System Administrators", "System Administration");

// pages for advanced search section
$default->siteMap->addDefaultPage("advancedSearch", "/search.php", "Advanced Search", "Anonymous", "Advanced Search");

// pages for prefs section
$default->siteMap->addDefaultPage("preferences", "/preferences.php", "Preferences", "Anonymous", "Preferences");
$default->siteMap->addPage("viewPreferences", "/preferences.php", "Preferences", "Anonymous", "View Preferences");
$default->siteMap->addPage("editPreferences", "/preferences.php", "Preferences", "Anonymous", "Edit Preferences");

// pages for Help section
$default->siteMap->addDefaultPage("help", "/help.php", "Help", "Anonymous", "Help");

// pages for logout section section
$default->siteMap->addDefaultPage("logout", "/presentation/logout.php", "Logout", "Anonymous", "Logout");


// test pages
$default->siteMap->addPage("scratchPad", "/tests/scratchPad.php", "Tests", "Anonymous", "scratch");
$default->siteMap->addPage("sitemap", "/tests/session/SiteMap.php", "Tests", "Anonymous", "sitemap");
$default->siteMap->addPage("documentBrowserTest", "/tests/documentmanagement/DocumentBrowser.php", "Tests", "Anonymous", "test the document browser");

// action, page, section, group with access, link description







require_once("$default->owl_fs_root/lib/session/Session.inc");
require_once("$default->owl_fs_root/lib/session/control.inc");
require_once("$default->owl_fs_root/lib/database/db.inc");
require_once("$default->owl_fs_root/lib/database/lookup.inc");
require_once("$default->owl_fs_root/lib/dms.inc");
?>
