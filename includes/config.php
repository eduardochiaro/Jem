<?php

/*CONFIG*/
define('_DB_NAME', 'jem');
define('_DB_USER', 'root');
define('_DB_PASSWORD', 'password');
define('_DB_HOST', 'localhost');
define('_DB_CHARSET', 'utf8');
define('_DB_ADAPTER', 'Mysqli');
define('_PATH_SEPARATOR', '/');
define('_SUB_PATH', _PATH_SEPARATOR.'jem'._PATH_SEPARATOR);
define('_SITE_PATH', 'http://'.$_SERVER['HTTP_HOST']._SUB_PATH);
define('_TIME_ZONE', 'Europe/Rome');
define('_DEBUG', true);

// Configurazione Mailer
define('_MAILER_TYPE_', "mail"); //"mail", "sendmail", or "smtp"
define('_MAILER_SENDMAIL_PATH_', '/usr/sbin/sendmail');
define('_MAILER_SMTP_HOST', "localhost");
define('_MAILER_SMTP_AUTH', false);
define('_MAILER_SMTP_USER', "");
define('_MAILER_SMTP_PWD', "");
define('_MAILER_HTML', true);

/*Non toccare da qui in poi*/
define('_ABS_PATH', dirname(__FILE__) . _PATH_SEPARATOR .'..' . _PATH_SEPARATOR);
define('_CORE_PATH', realpath(_ABS_PATH . _PATH_SEPARATOR.'core'));
define('_FRONTEND_PATH', realpath(_ABS_PATH . _PATH_SEPARATOR.'frontend'));
define('_CORE_CLASSES_PATH', realpath(_CORE_PATH . _PATH_SEPARATOR.'classes'));
define('_HTML_CLASSES_PATH', realpath(_CORE_PATH . _PATH_SEPARATOR.'html'));
define('_INCLUDES_PATH', realpath(_ABS_PATH . _PATH_SEPARATOR.'includes'));
define('_LANGUAGES_PATH', realpath(_INCLUDES_PATH . _PATH_SEPARATOR.'languages'));
define('_LIBRARIES_PATH', realpath(_ABS_PATH . _PATH_SEPARATOR.'libraries'));
define('_COMPONENTS_PATH', realpath(_ABS_PATH . _PATH_SEPARATOR.'components'));
define('_UPLOADS_PATH', realpath(_ABS_PATH . _PATH_SEPARATOR.'uploads'));
define('_EDITOR_UPLOADS_PATH', realpath(_UPLOADS_PATH . _PATH_SEPARATOR.'editor'));
define('_FRAMEWORK_UPLOADS_PATH', realpath(_UPLOADS_PATH . _PATH_SEPARATOR.'framework'));
define('_FRONTEND_UPLOADS_PATH', realpath(_UPLOADS_PATH . _PATH_SEPARATOR.'frontend'));
define("_OFFLINE",false);
define("_QUERYSTRING_DELETE","page,ajax");
define("_LIMIT_PAGINATIONS_",20);
define("_PAGES_PAGINATIONS_",7);
define("_FILE_SIZE_",2097152); //byte
define("_PHOTOS_W_",800);
define("_PHOTOS_H_",600);

define('_JEM_DB_', 'database');
define('_JEM_USER_',  'siteuser');
define('_JEM_MODULE_',  'module');
define('_JEM_FORM_',  'form');
define('_JEM_TIME_',  'scriptime');
define('_JEM_TRANSLATION_',  'translation');
define('_JEM_ERROR_MESSAGES_', 'errors-messages');
define('_JEM_INFO_MESSAGES_', 'info-messages');
define('_JEM_FRONT_ERROR_MESSAGES_', 'front-errors-messages');
define('_JEM_FRONT_INFO_MESSAGES_', 'front-info-messages');
define('_JEM_DEBUG_MESSAGES_', 'debug-messages');
define('IMAGETYPE_GIF', 1);
define('IMAGETYPE_JPEG', 2);
define('IMAGETYPE_PNG', 3);


