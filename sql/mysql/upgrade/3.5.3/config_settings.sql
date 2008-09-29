--
-- Table structure for table `config_groups`
--

CREATE TABLE `config_groups` (
  `id` int(255) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255),
  `description` mediumtext,
  `category` varchar(255),
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_groups`
--

INSERT INTO `config_groups` VALUES
(1, 'browse', 'Browse View', 'Configurable options for working in Browse View', 'User Interface Settings'),
(2, 'cache', 'Cache', 'Configure settings for the KnowledgeTree cache. Only advanced users should change these settings.', 'General Settings'),
(3, 'CustomErrorMessages', 'Custom Error Messages', 'Configuration settings for custom error messages. Only advanced users should change these settings.', 'User Interface Settings'),
(4, 'dashboard', 'Dashboard', 'Configures Dashboard Settings', 'General Settings'),
(5, 'DiskUsage', 'Disk Usage Dashlet', 'Configures the Disk Usage dashlet ', 'General Settings'),
(6, 'email', 'Email', 'Enables Email on your KnowledgeTree installation and configures Email settings. Note that several KnowledgeTree features use these settings. ', 'Email Settings'),
(7, 'export', 'Export', 'Configures KnowledgeTree\'s "Bulk Export" feature.', 'General Settings'),
(8, 'externalBinary', 'External Binaries', 'KnowledgeTree uses various external binaries. This section defines the paths to these binaries. <br>Only advanced users should change these settings.', 'General Settings'),
(9, 'i18n', 'Internationalization', 'Configures settings for Internationalization.', 'Internationalisation Settings'),
(10, 'import', 'Import', 'Configures settings on Bulk Import.', 'General Settings'),
(11, 'indexer', 'Document Indexer', 'Configures the Document Indexer. Only advanced users should change these settings.', 'Search and Indexing Settings'),
(12, 'KnowledgeTree', 'KnowledgeTree', 'Configures general settings for your KnowledgeTree server installation.', 'General Settings'),
(13, 'KTWebDAVSettings', 'WebDAV', 'Configuration options for third-party WebDAV clients', 'Client Tools Settings'),
(14, 'openoffice', 'OpenOffice.org Service', 'Configuration options for the OpenOffice.org service. Note that several KnowledgeTree features use this service.', 'Search and Indexing Settings'),
(15, 'search', 'Search', 'Configures settings for KnowledgeTree\'s Search function.', 'Search and Indexing Settings'),
(16, 'session', 'Session Management', 'Session management configuration.', 'General Settings'),
(17, 'storage', 'Storage', 'Configure the KnowledgeTree storage manager.', 'General Settings'),
(18, 'tweaks', 'Tweaks', 'Small configuration tweaks', 'General Settings'),
(19, 'ui', 'User Interface', 'General user interface configuration', 'User Interface Settings'),
(20, 'urls', 'Urls', 'The paths to the KnowledgeTree server and filesystem. <br>Full values are specific to your installation (Windows or Linux). Only advanced users should change these settings.', 'General Settings'),
(21, 'user_prefs', 'User Preferences', 'Configures user preferences.', 'General Settings'),
(22, 'webservice', 'Web Services', 'KnowledgeTree Web Service Interface configuration. Note that a number of KnowledgeTree Tools rely on this service.', 'Client Tools Settings'),
(23, 'ldapAuthentication', 'LDAP Authentication', 'Configures LDAP Authentication', 'General Settings');

-- --------------------------------------------------------

--
-- Table structure for table `config_settings`
--

CREATE TABLE `config_settings` (
  `id` int(11) NOT NULL auto_increment,
  `group_name` varchar(255) NOT NULL,
  `display_name` varchar(255),
  `description` mediumtext,
  `item` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL default 'default',
  `default_value` varchar(255) NOT NULL,
  `type` enum('boolean','string','numeric_string','numeric','radio','dropdown') default 'string',
  `options` mediumtext,
  `can_edit` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_settings`
--

INSERT INTO `config_settings` VALUES
(1, 'ui', 'OEM Application Name', 'Specifies the application name used by KnowledgeTree OEM partners. This name replaces \'KnowledgeTree\' wherever the application name displays in the interface.', 'appName', 'KnowledgeTree', 'KnowledgeTree', 'string', NULL, 1),
(2, 'KnowledgeTree', 'Scheduler Interval', 'Defines the frequency, in seconds, at which the Scheduler is set to run.', 'schedulerInterval', 'default', '30', 'numeric_string', NULL, 1),
(3, 'dashboard', 'Always Display \'Your Checked-out Documents\'', 'Defines whether to display the \'Your Checked-out Documents\' dashlet, even when there is no data to display. Default is \'False\'.', 'alwaysShowYCOD', 'default', 'false', 'boolean', NULL, 1),
(4, 'urls', 'Graphics Url', 'The path to the user interface graphics.', 'graphicsUrl', 'default', '${rootUrl}/graphics', 'string', NULL, 1),
(5, 'urls', 'User Interface Url', 'The path to the core user interface libraries.', 'uiUrl', 'default', '${rootUrl}/presentation/lookAndFeel/knowledgeTree', 'string', NULL, 1),
(6, 'tweaks', 'Browse to Unit Folder', 'Specifies a logged in user\'s \'Unit\' folder as their default folder view in Browse Documents. The default, \'False\', displays the root folder.', 'browseToUnitFolder', 'default', 'false', 'boolean', NULL, 1),
(7, 'tweaks', 'Generic Metadata Required', 'Defines whether to present KnowledgeTree\'s generic metadata fields for users to fill out on document upload. Default is \'True\'.', 'genericMetaDataRequired', 'default', 'true', 'boolean', NULL, 1),
(8, 'tweaks', 'Noisy Bulk Operations', 'Defines whether bulk operations generates a transaction notice on each item, or only on the folder. The default, \'False\' indicates that only folder transactions occur.', 'noisyBulkOperations', 'default', 'false', 'boolean', NULL, 1),
(9, 'tweaks', 'Php Error Log File', 'Enables PHP error logging to the log/php_error_log file. Default is \'False\'.', 'phpErrorLogFile', 'default', 'false', 'boolean', NULL, 1),
(10, 'email', 'Email Server', 'The address of the SMTP server. If the host name fails, try the IP address.', 'emailServer', 'none', 'none', 'string', NULL, 1),
(11, 'email', 'Email Port', 'The port of the SMTP server. The default is 25.', 'emailPort', 'default', '', 'numeric_string', NULL, 1),
(12, 'email', 'Email Authentication', 'Defines whether authentication is required for connecting to SMTP. Default is \'False\'. Change to \'True\' to force users to log in using their username and password.', 'emailAuthentication', 'default', 'false', 'boolean', NULL, 1),
(13, 'email', 'Email Username', 'The user name of the SMTP (email) server.', 'emailUsername', 'default', 'username', 'string', NULL, 1),
(14, 'email', 'Email Password', 'The password for the Email server. ', 'emailPassword', 'default', 'password', 'string', NULL, 1),
(15, 'email', 'Email From', 'Defines the sending email address for emails sent from KnowledgeTree.', 'emailFrom', 'default', 'kt@example.org', 'string', NULL, 1),
(16, 'email', 'Email From Name', 'The name used by KnowledgeTree for system-generated emails.', 'emailFromName', 'default', 'KnowledgeTree Document Management System', 'string', NULL, 1),
(17, 'email', 'Allow Attachment', 'Defines whether to allow users to send attachments from within KnowledgeTree. Default is \'False\'.', 'allowAttachment', 'default', 'false', 'boolean', NULL, 1),
(18, 'email', 'Allow External Email Addresses', 'Defines whether to allow KnowledgeTree users to send email to any email address - to other KnowledgeTree users and to external users. Default is \'False\'.', 'allowEmailAddresses', 'default', 'false', 'boolean', NULL, 1),
(19, 'email', 'Send As System', 'Defines whether to always send email from the KnowledgeTree \'Email From\' address, even if there is an identifiable sending user. Default is \'False\'.', 'sendAsSystem', 'default', 'false', 'boolean', NULL, 1),
(20, 'email', 'Only Own Groups', 'Defines whether to restrict users to sending emails only within their KnowledgeTree user group. <br>Default is \'False\'. <br>Set to \'True\' to disable sending of emails outside of the user\'s group.', 'onlyOwnGroups', 'default', 'false', 'boolean', NULL, 1),
(21, 'user_prefs', 'Password Length', 'Defines the minimum password length on password-setting. ', 'passwordLength', 'default', '6', 'numeric_string', NULL, 1),
(22, 'user_prefs', 'Restrict Admin Passwords', 'Defines whether to require the admin user to apply minimum password length when creating and editing accounts. Default is \'False\', which allows admin users to create accounts with shorter passwords than the specified minimum.', 'restrictAdminPasswords', 'default', 'false', 'boolean', NULL, 1),
(23, 'user_prefs', 'Restrict Preferences', 'Defines whether to restrict users from accessing the Preferences menu. Default is \'False\'.', 'restrictPreferences', 'default', 'false', 'boolean', NULL, 1),
(24, 'session', 'Session Timeout', 'Defines the period, in seconds, after which the system times out following a period of inactivity.', 'sessionTimeout', 'default', '1200', 'numeric_string', NULL, 1),
(25, 'session', 'Anonymous Login', 'Defines whether to allow anonymous users to log in automatically. Default is \'False\'. <br>Best practice is not to allow automatic login of anonymous users unless you understand KnowledgeTree\'s security mechanisms, and have sensibly applied the roles \'Everyone\' and \'Authenticated Users\'. ', 'allowAnonymousLogin', 'default', 'false', 'boolean', NULL, 1),
(26, 'ui', 'Company Logo', 'Specifies the path (relative to the KnowledgeTree directory) to the custom logo for the KnowledgeTree user interface. <br>The logo must be 50px tall, and on a white background.', 'companyLogo', 'default', '${rootUrl}/resources/companylogo.png', 'string', NULL, 1),
(27, 'ui', 'Company Logo Width', 'Defines the width, in pixels, of your custom logo.', 'companyLogoWidth', 'default', '313px', 'string', NULL, 1),
(28, 'ui', 'Company Logo Title', 'Alternative text for the title of your custom company logo, for accessibility purposes.', 'companyLogoTitle', 'default', 'ACME Corporation', 'string', NULL, 1),
(29, 'ui', 'Always Show All Results', 'Defines, where \'show all users\' is an available action, whether to display the full list of users and groups on page load, without requiring the user to click \'show all users\'. Default is \'False\'.', 'alwaysShowAll', 'default', 'false', 'boolean', NULL, 1),
(30, 'ui', 'Condensed Admin UI', 'Defines whether to use a condensed (compact) version of the KnowledgeTree user interface for the admin user. Default is \'False\'.', 'condensedAdminUI', 'default', 'false', 'boolean', NULL, 1),
(31, 'ui', 'Fake Mimetype', 'Defines whether browsers may provide the option to \'open\' a document from download. Default is \'False\'.<br>Change to \'True\' to prevent (most) browsers from giving users the \'Open\' option.', 'fakeMimetype', 'default', 'false', 'boolean', NULL, 1),
(32, 'i18n', 'UseLike', 'Enables \'search ideographic language\' on languages that do not have distinguishable words (typically, where there is no space character), and allows KnowledgeTree\'s Search function to deal with this issue. Default is \'False\'.', 'useLike', 'default', 'false', 'boolean', NULL, 1),
(33, 'import', 'unzip', 'Specifies the location of the unzip binary. The unzip command uses \'execSearchPath\' to find the unzip binary if the path is not provided. Values are auto-populated, specific to your installation (Windows or Linux).', 'unzip', 'default', 'unzip', 'string', NULL, 1),
(34, 'export', 'zip', 'The location of the zip binary. <br>The zip command uses \'execSearchPath\' to find the zip binary if the path is not provided. Values are auto-populated, specific to your installation (Windows or Linux).', 'zip', 'default', 'zip', 'string', NULL, 1),
(35, 'externalBinary', 'xls2csv', 'Path to binary', 'xls2csv', 'default', 'xls2csv', 'string', NULL, 1),
(36, 'externalBinary', 'pdftotext', 'Path to binary', 'pdftotext', 'default', 'pdftotext', 'string', NULL, 1),
(37, 'externalBinary', 'catppt', 'Path to binary', 'catppt', 'default', 'catppt', 'string', NULL, 1),
(38, 'externalBinary', 'pstotext', 'Path to binary', 'pstotext', 'default', 'pstotext', 'string', NULL, 1),
(39, 'externalBinary', 'catdoc', 'Path to binary', 'catdoc', 'default', 'catdoc', 'string', NULL, 1),
(40, 'externalBinary', 'antiword', 'Path to binary', 'antiword', 'default', 'antiword', 'string', NULL, 1),
(41, 'externalBinary', 'python', 'Path to binary', 'python', 'default', 'python', 'string', NULL, 1),
(42, 'externalBinary', 'java', 'Path to binary', 'java', 'default', 'java', 'string', NULL, 1),
(43, 'externalBinary', 'php', 'Path to binary', 'php', 'default', 'php', 'string', NULL, 1),
(44, 'externalBinary', 'df', 'Path to binary', 'df', 'default', 'df', 'string', NULL, 1),
(45, 'cache', 'Proxy Cache Path', 'The path to the proxy cache. Default is <var directory>/cache.', 'proxyCacheDirectory', 'default', '${varDirectory}/proxies', 'string', NULL, 1),
(46, 'cache', 'Proxy Cache Enabled', 'Enables proxy caching. Default is \'True\'. ', 'proxyCacheEnabled', 'default', 'true', 'boolean', NULL, 1),
(47, 'KTWebDAVSettings', 'Debug', 'Switch debug output to \'on\' only if you must view \'all\' debugging information for KTWebDAV. The default is \'off\'.', 'debug', 'off', 'off', 'radio', 'a:1:{s:7:"options";a:2:{i:0;s:2:"on";i:1;s:3:"off";}}', 1),
(48, 'KTWebDAVSettings', 'Safemode', 'To allow \'write\' access to WebDAV clients, set safe mode to "off". The default is \'on\'.', 'safemode', 'on', 'on', 'radio', 'a:1:{s:7:"options";a:2:{i:0;s:2:"on";i:1;s:3:"off";}}', 1),
(49, 'search', 'Search Base', 'The location of the Search and Indexing libraries.', 'searchBasePath', 'default', '${fileSystemRoot}/search2', 'string', NULL, 0),
(50, 'search', 'Fields Path', 'The location of the Search and Indexing fields.', 'fieldsPath', 'default', '${searchBasePath}/search/fields', 'string', NULL, 0),
(51, 'search', 'Results Display Format', 'Defines how search results display. Options are: search engine style, or browse view style. The default is \'Search Engine Style\'.', 'resultsDisplayFormat', 'default', 'searchengine', 'dropdown', 'a:1:{s:7:"options";a:2:{i:0;a:2:{s:5:"label";s:19:"Search Engine Style";s:5:"value";s:12:"searchengine";}i:1;a:2:{s:5:"label";s:17:"Browse View Style";s:5:"value";s:10:"browseview";}}}', 1),
(52, 'search', 'Results per Page', 'The number of results to display per page.', 'resultsPerPage', 'default', '25', 'numeric_string', NULL, 1),
(53, 'search', 'Date Format', 'The date format used when making queries using widgets.', 'dateFormat', 'default', 'Y-m-d', 'string', NULL, 0),
(54, 'browse', 'Property Preview Activation', 'Defines the action for displaying the Property Preview. Options are \'On Click\' or \'Mouseover\'. Default is \'On Click\'.', 'previewActivation', 'default', 'onclick', 'dropdown', 'a:1:{s:7:"options";a:2:{i:0;a:2:{s:5:"label";s:9:"Mouseover";s:5:"value";s:10:"mouse-over";}i:1;a:2:{s:5:"label";s:8:"On Click";s:5:"value";s:7:"onclick";}}}', 1),
(55, 'indexer', 'Core Class', 'Defines the core indexing class. Options include: JavaXMLRPCLuceneIndexer or PHPLuceneIndexer.', 'coreClass', 'default', 'JavaXMLRPCLuceneIndexer', 'string', NULL, 0),
(56, 'indexer', 'Batch Documents', 'The number of documents to be indexed in a cron session. ', 'batchDocuments', 'default', '20', 'numeric_string', 'a:3:{s:9:"increment";i:10;s:7:"minimum";i:20;s:7:"maximum";i:200;}', 1),
(57, 'indexer', 'Batch Migrate Documents', 'The number of documents to be migrated in a cron session, using KnowledgeTree\'s migration script. ', 'batchMigrateDocuments', 'default', '500', 'numeric_string', NULL, 1),
(58, 'indexer', 'Indexing Base ', 'The location of the Indexing engine.', 'indexingBasePath', 'default', '${searchBasePath}/indexing', 'string', NULL, 0),
(59, 'indexer', 'Lucene Directory', 'The location of the Lucene indexes.', 'luceneDirectory', 'default', '${varDirectory}/indexes', 'string', NULL, 0),
(60, 'indexer', 'Extractors ', 'The location of the text extractors.', 'extractorPath', 'default', '${indexingBasePath}/extractors', 'string', NULL, 0),
(61, 'indexer', 'Extractor Hook ', 'The location of the extractor hooks.', 'extractorHookPath', 'default', '${indexingBasePath}/extractorHooks', 'string', NULL, 0),
(62, 'indexer', 'Java Lucene Server ', 'The location (URL) of the Java Lucene server. Ensure that this matches the Lucene server configuration. ', 'javaLuceneURL', 'default', 'http://127.0.0.1:8875', 'string', NULL, 0),
(63, 'openoffice', 'Host', 'Defines the host on which OpenOffice is installed. Ensure that this points to the OpenOffice server. ', 'host', 'default', '127.0.0.1', 'string', NULL, 1),
(64, 'openoffice', 'Port', 'Defines the port on which OpenOffice listens. ', 'port', 'default', '8100', 'numeric_string', NULL, 1),
(65, 'webservice', 'Upload Directory', 'Directory to which all uploads via webservices are persisted before moving into the repository.', 'uploadDirectory', 'default', '${varDirectory}/uploads', 'string', NULL, 1),
(66, 'webservice', 'Download Url', 'Url which is sent to clients via web service calls so they can then download file via HTTP GET.', 'downloadUrl', 'default', '${rootUrl}/ktwebservice/download.php', 'string', NULL, 1),
(67, 'webservice', 'Upload Expiry', 'Period indicating how long a file should be retained in the uploads directory.', 'uploadExpiry', 'default', '30', 'numeric_string', 'a:1:{s:6:"append";s:7:"seconds";}', 1),
(68, 'webservice', 'Download Expiry', 'Period indicating how long a download link will be available.', 'downloadExpiry', 'default', '30', 'numeric_string', 'a:1:{s:6:"append";s:7:"seconds";}', 1),
(69, 'webservice', 'Random Key Text', 'Random text used to construct a hash. This can be customised on installations so there is less chance of overlap between installations.', 'randomKeyText', 'default', 'bkdfjhg23yskjdhf2iu', 'string', NULL, 1),
(70, 'webservice', 'Validate Session Count', 'Validating session counts can interfere with access. It is best to leave this disabled, unless very strict access is required.', 'validateSessionCount', 'false', 'false', 'boolean', NULL, 1),
(71, 'webservice', 'Use Default Document Type If Invalid', 'If the document type is invalid when adding a document, we can be tollerant and just default to the Default document type.', 'useDefaultDocumentTypeIfInvalid', 'true', 'true', 'boolean', NULL, 1),
(72, 'webservice', 'Debug', 'The web service debugging if the logLevel is set to DEBUG. We can set the value to 4 or 5 to get more verbose web service logging. Level 4 logs the name of functions being accessed. Level 5 logs the SOAP XML requests and responses.', 'debug', '0', '0', 'numeric_string', NULL, 1),
(73, 'DiskUsage', 'Warning Threshold', 'The percentage below which the mount in the Disk Usage dashlet changes to Orange, indicating that the mount point is running out of free space. ', 'warningThreshold', '10', '10', 'numeric_string', 'a:1:{s:6:"append";s:1:"%";}', 1),
(74, 'DiskUsage', 'Urgent Threshold', 'The percentage below which the mount in the Disk Usage dashlet changes to Red, indicating that the lack of free space in the mount is critically low.', 'urgentThreshold', '5', '5', 'numeric_string', 'a:1:{s:6:"append";s:1:"%";}', 1),
(75, 'KnowledgeTree', 'Use AJAX Dashboard', 'Defines whether to use the AJAX dashboard, which allows users to drag the dashlets to change the Dashboard display.<br>Default is \'True\'. ', 'useNewDashboard', 'true', 'true', 'boolean', NULL, 1),
(76, 'i18n', 'Default Language', 'Defines the default language for the KnowledgeTree user interface. The default is English (en).', 'defaultLanguage', 'default', 'en', 'string', NULL, 1),
(77, 'CustomErrorMessages', 'Custom Error Messages', 'Enables and disables custom error messages. Default is \'On\' (enabled).', 'customerrormessages', 'default', 'on', 'radio', 'a:1:{s:7:"options";a:2:{i:0;s:2:"on";i:1;s:3:"off";}}', 1),
(78, 'CustomErrorMessages', 'Custom Error Page Path', 'The file name or URL of the custom error page.', 'customerrorpagepath', 'default', 'customerrorpage.php', 'string', NULL, 1),
(79, 'CustomErrorMessages', 'Custom Error Handler', 'Enables and disables the custom error handler feature. Default is \'On\' (enabled).', 'customerrorhandler', 'default', 'on', 'radio', 'a:1:{s:7:"options";a:2:{i:0;s:2:"on";i:1;s:3:"off";}}', 1),
(80, 'ui', 'Enable Custom Skinning', 'Defines whether customs skins may be used for the KnowledgeTree user interface. Default is \'False\'.', 'morphEnabled', 'default', 'false', 'boolean', NULL, 1),
(81, 'ui', 'Default Skin', 'Defines, when skinning is enabled, the location of the custom skin to use for the KnowledgeTree user interface.', 'morphTo', 'default', 'blue', 'string', NULL, 1),
(82, 'KnowledgeTree', 'Log Level', 'Defines the level of logging to use (DEBUG, INFO, WARN, ERROR). The default is INFO.', 'logLevel', 'default', 'INFO', 'dropdown', 'a:1:{s:7:"options";a:4:{i:0;a:2:{s:5:"label";s:4:"INFO";s:5:"value";s:4:"INFO";}i:1;a:2:{s:5:"label";s:4:"WARN";s:5:"value";s:4:"WARN";}i:2;a:2:{s:5:"label";s:5:"ERROR";s:5:"value";s:5:"ERROR";}i:3;a:2:{s:5:"label";s:5:"DEBUG";s:5:"value";s:5:"DEBUG";}}}', 1),
(83, 'storage', 'Manager', 'Defines the storage manager to use for storing documents on the file system. ', 'manager', 'default', 'KTOnDiskHashedStorageManager', 'string', NULL, 1),
(84, 'ui', 'IE GIF Theme Overrides', 'Defines whether to use the additional IE-specific GIF theme overrides, which may restrict <br>the working of arbitrary theme packs without having GIF versions available. Default is \'False\'.', 'ieGIF', 'false', 'true', 'boolean', NULL, 1),
(85, 'ui', 'Automatic Refresh', 'Set to true to automatically refresh the page after the session would have expired.', 'automaticRefresh', 'default', 'false', 'boolean', NULL, 1),
(86, 'ui', 'dot', 'Location of the dot binary (command location). On Unix systems, to determine whether the \'dot\' application is installed.', 'dot', 'dot', 'dot', 'string', NULL, 1),
(87, 'urls', 'Log Directory', 'The path to the Log directory.', 'logDirectory', 'default', '${varDirectory}/log', 'string', NULL, 1),
(88, 'urls', 'UI Directory', 'The path to the UI directory.', 'uiDirectory', 'default', '${fileSystemRoot}/presentation/lookAndFeel/knowledgeTree', 'string', NULL, 1),
(89, 'urls', 'Temp Directory', 'The path to the temp directory.', 'tmpDirectory', 'default', '${varDirectory}/tmp', 'string', NULL, 1),
(90, 'urls', 'Stopwords File', 'The path to the stopword file.', 'stopwordsFile', 'default', '${fileSystemRoot}/config/stopwords.txt', 'string', NULL, 1),
(91, 'cache', 'Cache Enabled', 'Enables the KnowledgeTree cache. Default is \'False\'.', 'cacheEnabled', 'default', 'false', 'boolean', NULL, 1),
(92, 'cache', 'Cache Directory', 'The location of the KnowledgeTree cache.', 'cacheDirectory', 'default', '${varDirectory}/cache', 'string', NULL, 1),
(93, 'openoffice', 'Program Path', 'Defines the path to the OpenOffice program directory. ', 'programPath', 'default', '../openoffice/program', 'string', NULL, 1),
(94, 'urls', 'documentRoot', '', 'documentRoot', 'default', '${varDirectory}/Documents', 'string', NULL, 0),
(95, 'KnowledgeTree', 'Redirect To Browse View', 'Defines whether to redirect to the Browse view (Browse Documemts) on login, instead of the Dashboard.<br>Default is \'False\'. ', 'redirectToBrowse', 'default', 'false', 'boolean', NULL, 1),
(96, 'KnowledgeTree', 'Redirect To Browse View: Exceptions', 'Specifies that, when \'Redirect To Browse\' is set to \'True\' all users, except for the users listed in the text field below are redirected to the Browse view on log in. The users listed for this setting are directed to the KnowledgeTree Dashboard. To define exceptions, add user names in the text field as follows, e.g. admin, joebloggs, etc.', 'redirectToBrowseExceptions', '', '', 'string', NULL, 1),
(97, 'session', 'Allow Automatic Sign In', 'Defines whether to automatically create a user account on first login for any user who does not yet exist in the system. Default is \'False\'.', 'allowAutoSignup', 'default', 'false', 'boolean', '', 1),
(98, 'ldapAuthentication', 'Create Groups Automatically', 'Defines whether to allow LDAP groups to be created automatically. Default is \'False\'.', 'autoGroupCreation', 'default', 'false', 'boolean', '', 1);

