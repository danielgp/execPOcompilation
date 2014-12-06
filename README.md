execPOcompilation
=================

recursive search PO files within a given folder and compile them into MO

For Windows OS, you may download GetText utilities from: 
    http://mlocati.github.io/gettext-iconv-windows/
where versions for both 32bit and 64bit are available

Usage:

require_once 'compileLocalization.config.inc.php';
require_once 'compileLocalization.class.inc.php';
$app = new NamespacePgdApp\Core\compileLocalization(__DIR__);
