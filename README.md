execPOcompilation
=================

recursive search PO files within a given folder and compile them into MO

For Windows OS, you may download GetText utilities (which include the compiler) from: 
    http://mlocati.github.io/gettext-iconv-windows/
where versions for both 32bit and 64bit are available

(07.12.2014 - tried https://github.com/phpmo/php.mo on Windows and does not work w. PHP 5.6 nts w. FCGId)
(07.12.2014 - tried https://github.com/trachalakis/Msgfmt.git on Windows and does not work w. PHP 5.6 nts w. FCGId)

Usage:

1. upon downloading this, run "composer install" to get the dependent packages added
2. open the file "compileLocalization.class.inc" in a text editor and adjust the compiler and folders desired to be scanned for PO files
3. run a PHP instance referring to this folder + index.php file OR open a browser and refer to this package and index.php file (after you have placed this package under a visible folder in your web server, of course)
