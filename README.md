execPOcompilation
=================

recursive search PO files within a given folder and compile them into MO

For Windows OS, you may download GetText utilities from: 
    http://mlocati.github.io/gettext-iconv-windows/
where versions for both 32bit and 64bit are available.

(Tried https://github.com/phpmo/php.mo on Windows and does not work w. PHP 5.6 nts w. FCGId)
(Tried https://github.com/trachalakis/Msgfmt.git on Windows and does not work w. PHP 5.6 nts w. FCGId)

Usage:

require_once 'compileLocalization.config.inc.php';
require_once 'compileLocalization.class.inc.php';
$app = new NamespacePgdApp\Core\compileLocalization(__DIR__);
