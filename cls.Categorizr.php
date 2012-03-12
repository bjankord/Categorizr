<?php 
/**
* Categorizr Version 1.1
* http://www.brettjankord.com/2012/01/16/categorizr-a-modern-device-detection-script/
* Written by Brett Jankord - Copyright © 2011
* Thanks to Josh Eisma for helping with code review
*
* Big thanks to Rob Manson and http://mob-labs.com for their work on
* the Not-Device Detection strategy:
* http://smartmobtoolkit.wordpress.com/2009/01/26/not-device-detection-javascript-perl-and-php-code/
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Lesser General Public License for more details.
* You should have received a copy of the GNU General Public License
* and GNU Lesser General Public License
* along with this program. If not, see http://www.gnu.org/licenses/.
* 
* 2012-03-12
* Made a static class out of original code // https://github.com/H-Max/Categorizr
* Just include this class in your php file and use
* CATEGORIZR::getDeviceCategory()
* to read userAgent and grab the deviceCategory in : 
* CATEGORIZR::$userAgent
* CATEGORIZR::$deviceCategory
*
* Use CATEGORIZR::$useSession = true; before calling getDeviceCategory if you want to use session storage directly in the class (rather than using you own session handler)
**/


class categorizr{
	/**
	 *	Categories labels 
	 */
	const _CATEGORY_TV = 'tv';
	const _CATEGORY_MOBILE = 'mobile';
	const _CATEGORY_TABLET = 'tablet';
	const _CATEGORY_DESKTOP = 'desktop';
	
	/**
	 *	Session var name (if used) 
	 */
	const _DEFAULT_SESSIONVAR = 'deviceCategory';
	
	/**
	 *	Make tablets userAgents act like desktop
	 * @var type 
	 */
	public static $tabletAsDesktop = false;
	/**
	 *	Make TV userAgents act like desktop
	 * @var type 
	 */
	public static $tvAsDesktop = false;
	
	/**
	 * Set to true in order to store the result in session
	 * @var boolean 
	 */
	public static $useSession = true;
	/**
	 * Var name in session (if $useSession == true)
	 * @var string 
	 */
	public static $sessionPath = self::_DEFAULT_SESSIONVAR;
	
	/**
	 * Will be initiated with userAgent string at start
	 * @var type 
	 */
	public static $userAgent = '';
	
	/**
	 *	Detected device category (default is mobile)
	 * @var string
	 */
	public static $deviceCategory = self::_CATEGORY_MOBILE;
		
	public function getDeviceCategory($pUserAgent = null){
		//	Read current value from session (if set)
		$current = self::readFromSession();
		
		// User agent passed as parameter, replace default value
		if (!empty($pUserAgent))
			self::$userAgent = $pUserAgent;
		else
			self::$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		//	Return session value if set and self::$useSession is true
		//	Set the userAgent in response to the _FROM_SESSION constant
		if (!empty($current)){
			self::$deviceCategory = $current;
			return $current;
		}
		
		// Check if user agent is a smart TV - http://goo.gl/FocDk
		if ((preg_match('/GoogleTV|SmartTV|Internet.TV|NetCast|NETTV|AppleTV|boxee|Kylo|Roku|DLNADOC|CE\-HTML/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_TV;
		}
		// Check if user agent is a TV Based Gaming Console
		else if ((preg_match('/Xbox|PLAYSTATION.3|Wii/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_TV;
		}  
		// Check if user agent is a Tablet
		else if((preg_match('/iP(a|ro)d/i', self::$userAgent)) || (preg_match('/tablet/i', self::$userAgent)) && (!preg_match('/RX-34/i', self::$userAgent)) || (preg_match('/FOLIO/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_TABLET;
		}
		// Check if user agent is an Android Tablet
		else if ((preg_match('/Linux/i', self::$userAgent)) && (preg_match('/Android/i', self::$userAgent)) && (!preg_match('/Fennec|mobi|HTC.Magic|HTCX06HT|Nexus.One|SC-02B|fone.945/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_TABLET;
		}
		// Check if user agent is a Kindle or Kindle Fire
		else if ((preg_match('/Kindle/i', self::$userAgent)) || (preg_match('/Mac.OS/i', self::$userAgent)) && (preg_match('/Silk/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_TABLET;
		}
		// Check if user agent is a pre Android 3.0 Tablet
		else if ((preg_match('/GT-P10|SC-01C|SHW-M180S|SGH-T849|SCH-I800|SHW-M180L|SPH-P100|SGH-I987|zt180|HTC(.Flyer|\_Flyer)|Sprint.ATP51|ViewPad7|pandigital(sprnova|nova)|Ideos.S7|Dell.Streak.7|Advent.Vega|A101IT|A70BHT|MID7015|Next2|nook/i', self::$userAgent)) || (preg_match('/MB511/i', self::$userAgent)) && (preg_match('/RUTEM/i', self::$userAgent))){
			self::$deviceCategory = _self::CATEGORY_TABLET;
		} 
		// Check if user agent is unique Mobile User Agent	
		else if ((preg_match('/BOLT|Fennec|Iris|Maemo|Minimo|Mobi|mowser|NetFront|Novarra|Prism|RX-34|Skyfire|Tear|XV6875|XV6975|Google.Wireless.Transcoder/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_MOBILE;
		}
		// Check if user agent is an odd Opera User Agent - http://goo.gl/nK90K
		else if ((preg_match('/Opera/i', self::$userAgent)) && (preg_match('/Windows.NT.5/i', self::$userAgent)) && (preg_match('/HTC|Xda|Mini|Vario|SAMSUNG\-GT\-i8000|SAMSUNG\-SGH\-i9/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_MOBILE;
		}
		// Check if user agent is Windows Desktop
		else if ((preg_match('/Windows.(NT|XP|ME|9)/', self::$userAgent)) && (!preg_match('/Phone/i', self::$userAgent)) || (preg_match('/Win(9|.9|NT)/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		}  
		// Check if agent is Mac Desktop
		else if ((preg_match('/Macintosh|PowerPC/i', self::$userAgent)) && (!preg_match('/Silk/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		} 
		// Check if user agent is a Linux Desktop
		else if ((preg_match('/Linux/i', self::$userAgent)) && (preg_match('/X11/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		} 
		// Check if user agent is a Solaris, SunOS, BSD Desktop
		else if ((preg_match('/Solaris|SunOS|BSD/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		}
		// Check if user agent is a Desktop BOT/Crawler/Spider
		else if ((preg_match('/Bot|Crawler|Spider|Yahoo|ia_archiver|Covario-IDS|findlinks|DataparkSearch|larbin|Mediapartners-Google|NG-Search|Snappy|Teoma|Jeeves|TinEye/i', self::$userAgent)) && (!preg_match('/Mobile/i', self::$userAgent))){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		}
		
		if (self::$tabletAsDesktop && self::$deviceCategory == self::_CATEGORY_TABLET){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		}

		// Categorize TVs as desktops
		if (self::$tvAsDesktop && self::$deviceCategory == self::_CATEGORY_TV){
			self::$deviceCategory = self::_CATEGORY_DESKTOP;
		}
		
		//	Store value in session (if self::useSession is true)
		self::storeInSession();
		
		return self::$deviceCategory;
	}
	
	/**
	 *	Store value in session if self::useSession is true 
	 */
	private function storeInSession(){
		if (self::$useSession)
			$_SESSION[self::$sessionPath] = self::$deviceCategory;
	}
	
	/**
	 *	Returns value stored in session is self::useSession is true
	 * @return string 
	 */
	private function readFromSession(){
		if (self::$useSession)
			return $_SESSION[self::$sessionPath];
		else
			return '';
	}
	
	/**
	 *	Flushed values (including session) 
	 */
	public function flush(){
		if (isset($_SESSION[self::$sessionPath]))
			unset($_SESSION[self::$sessionPath]);
		
		self::$deviceCategory = self::_CATEGORY_MOBILE;
		self::$userAgent = '';
	}
}

?>