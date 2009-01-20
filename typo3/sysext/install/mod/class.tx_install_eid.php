<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Marcus Krause (security@typo3.org)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


// *******************************
// Set error reporting
// *******************************
error_reporting (E_ALL ^ E_NOTICE);


// ***********************
// Paths are setup
// ***********************
define('TYPO3_OS', stristr(PHP_OS,'win')&&!stristr(PHP_OS,'darwin')?'WIN':'');
define('TYPO3_MODE','FE');
if (!defined('PATH_thisScript')) 	define('PATH_thisScript',str_replace('//','/', str_replace('\\','/', (php_sapi_name()=='cgi'||php_sapi_name()=='isapi' ||php_sapi_name()=='cgi-fcgi')&&($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED'])? ($_SERVER['ORIG_PATH_TRANSLATED']?$_SERVER['ORIG_PATH_TRANSLATED']:$_SERVER['PATH_TRANSLATED']):($_SERVER['ORIG_SCRIPT_FILENAME']?$_SERVER['ORIG_SCRIPT_FILENAME']:$_SERVER['SCRIPT_FILENAME']))));

if (!defined('PATH_site')) 			define('PATH_site', dirname(PATH_thisScript).'/');
if (!defined('PATH_t3lib')) 		define('PATH_t3lib', PATH_site.'t3lib/');
define('PATH_tslib', PATH_site.'tslib/');
define('PATH_typo3conf', PATH_site.'typo3conf/');
define('TYPO3_mainDir', 'typo3/');		// This is the directory of the backend administration for the sites of this TYPO3 installation.

if (!@is_dir(PATH_typo3conf))	die('Cannot find configuration. This file is probably executed from the wrong location.');

require_once(PATH_t3lib.'class.t3lib_div.php');


/**
 * This is the eID handler for encryption key generation.
 *
 * @author	Marcus Krause <security@typo3.org>
 */
class tx_install_eid {


	/**
	 * Keeps content to be printed.
	 *
	 * @var string
	 */
	var $content;


	/**
	 * Main function which creates the ecryption key for the install tool and displays it in a (popup) window.
	 * Accumulates the content in $this->content
	 *
	 * @return	void
	 */
	function main()	{
			// Create HTML output:
		$this->content='';
		$this->content.='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Encryption key generation</title>
	<meta name="robots" content="noindex,follow" />
</head>
		<body>
		<p><br>Copy the following string and paste it into the according input field of the install tool:<br><br>';
		
		$this->content .= '<font size="-1">' . $this->createEncryptionKey() . '</font>';
		$this->content .= '</p>
		</body>
		</html>';
	}

	/**
	 * Outputs the content from $this->content
	 *
	 * @return	void
	 */
	function printContent()	{
		echo $this->content;
	}

	/**
	 * Returns a newly created TYPO3 encryption key with a given length.
	 *
	 * @param  integer  $keyLength  desired key length
	 * @return string
	 */
	function createEncryptionKey($keyLength = 96) {
		
		$bytes = t3lib_div::generateRandomBytes($keyLength);
		return substr(bin2hex($bytes), -96);
	}
}

// Make instance:
$SOBE = t3lib_div::makeInstance('tx_install_eid');
$SOBE->main();
$SOBE->printContent();

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/sysext/install/mod/class.tx_install_eid.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/sysext/install/mod/class.tx_install_eid.php']);
}

?>