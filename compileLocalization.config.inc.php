<?php

define('APP_INDICATIVE', 'execPOcompilation');
define('GETTEXT_COMPILER', 'D:\\www\\App\\GetText\\msgfmt.exe');
define('ERROR_DIR', pathinfo(ini_get('error_log'))['dirname']);
define('ERROR_FILE', 'php' . PHP_VERSION_ID . 'errors_ExecPoCompilation_' . date('Y-m-d') . '.log');
