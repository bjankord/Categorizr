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
**/

function categorizr(){
// Categorizr Options -----------------------------------------------------------------------------------------------------------------
 
$catergorize_tablets_as_desktops = FALSE;  //If TRUE, tablets will be categorized as desktops
$catergorize_tvs_as_desktops     = FALSE;  //If TRUE, smartTVs will be categorized as desktops

// End Categorizr Options --------------------------------------------------------------------------------------------------------------

// Category name - In the event the script is already using 'category' in the session variables, you could easily change it by only needing to change this value.
$category = 'category';

//Set User Agent = $ua
$ua = $_SERVER['HTTP_USER_AGENT'];

// Check if session has already started, otherwise E_NOTICE is thrown
if (session_id() == "")
	session_start();

// Check to see if device type is set in query string
if(isset($_GET["view"])){
	$view = $_GET["view"];
	// If view=desktop set in your query string
	if ($view == "desktop")
	{
		$_SESSION[$category] = "desktop";
	} 
	// If view=tablet set in your query string
	else if ($view == "tablet")
	{
		$_SESSION[$category] = "tablet";
	} 
	// If view=tablet set in your query string
	else if ($view == "tv")
	{
		$_SESSION[$category] = "tv";
	} 
	// If view=mobile set in your query string
	else if ($view == "mobile")
	{
		$_SESSION[$category] = "mobile";
	}
}// End Query String check

// If session not yet set, check user agents
if(!isset($_SESSION[$category])){

	// Check if user agent is a smart TV - http://goo.gl/FocDk
	if ((preg_match('/GoogleTV|SmartTV|Internet.TV|NetCast|NETTV|AppleTV|boxee|Kylo|Roku|DLNADOC|CE\-HTML/i', $ua)))
	{
		$_SESSION[$category] = "tv";
	}
	// Check if user agent is a TV Based Gaming Console
	else if ((preg_match('/Xbox|PLAYSTATION.3|Wii/i', $ua)))
	{
		$_SESSION[$category] = "tv";
	}  
	// Check if user agent is a Tablet
	else if((preg_match('/iP(a|ro)d/i', $ua)) || (preg_match('/tablet/i', $ua)) && (!preg_match('/RX-34/i', $ua)) || (preg_match('/FOLIO/i', $ua)))
	{
		$_SESSION[$category] = "tablet";
	}
	// Check if user agent is an Android Tablet
	else if ((preg_match('/Linux/i', $ua)) && (preg_match('/Android/i', $ua)) && (!preg_match('/Fennec|mobi|HTC.Magic|HTCX06HT|Nexus.One|SC-02B|fone.945/i', $ua)))
	{
		$_SESSION[$category] = "tablet";
	}
	// Check if user agent is a Kindle or Kindle Fire
	else if ((preg_match('/Kindle/i', $ua)) || (preg_match('/Mac.OS/i', $ua)) && (preg_match('/Silk/i', $ua)))
	{
		$_SESSION[$category] = "tablet";
	}
	// Check if user agent is a pre Android 3.0 Tablet
	else if ((preg_match('/GT-P10|SC-01C|SHW-M180S|SGH-T849|SCH-I800|SHW-M180L|SPH-P100|SGH-I987|zt180|HTC(.Flyer|\_Flyer)|Sprint.ATP51|ViewPad7|pandigital(sprnova|nova)|Ideos.S7|Dell.Streak.7|Advent.Vega|A101IT|A70BHT|MID7015|Next2|nook/i', $ua)) || (preg_match('/MB511/i', $ua)) && (preg_match('/RUTEM/i', $ua)))
	{
		$_SESSION[$category] = "tablet";
	} 
	// Check if user agent is unique Mobile User Agent	
	else if ((preg_match('/BOLT|Fennec|Iris|Maemo|Minimo|Mobi|mowser|NetFront|Novarra|Prism|RX-34|Skyfire|Tear|XV6875|XV6975|Google.Wireless.Transcoder/i', $ua)))
	{
		$_SESSION[$category] = "mobile";
	}
	// Check if user agent is an odd Opera User Agent - http://goo.gl/nK90K
	else if ((preg_match('/Opera/i', $ua)) && (preg_match('/Windows.NT.5/i', $ua)) && (preg_match('/HTC|Xda|Mini|Vario|SAMSUNG\-GT\-i8000|SAMSUNG\-SGH\-i9/i', $ua)))
	{
		$_SESSION[$category] = "mobile";
	}
	// Check if user agent is Windows Desktop
	else if ((preg_match('/Windows.(NT|XP|ME|9)/', $ua)) && (!preg_match('/Phone/i', $ua)) || (preg_match('/Win(9|.9|NT)/i', $ua)))
	{
		$_SESSION[$category] = "desktop";
	}  
	// Check if agent is Mac Desktop
	else if ((preg_match('/Macintosh|PowerPC/i', $ua)) && (!preg_match('/Silk/i', $ua)))
	{
		$_SESSION[$category] = "desktop";
	} 
	// Check if user agent is a Linux Desktop
	else if ((preg_match('/Linux/i', $ua)) && (preg_match('/X11/i', $ua)))
	{
		$_SESSION[$category] = "desktop";
	} 
	// Check if user agent is a Solaris, SunOS, BSD Desktop
	else if ((preg_match('/Solaris|SunOS|BSD/i', $ua)))
	{
		$_SESSION[$category] = "desktop";
	}
	// Check if user agent is a Desktop BOT/Crawler/Spider
	else if ((preg_match('/Bot|Crawler|Spider|Yahoo|ia_archiver|Covario-IDS|findlinks|DataparkSearch|larbin|Mediapartners-Google|NG-Search|Snappy|Teoma|Jeeves|TinEye/i', $ua)) && (!preg_match('/Mobile/i', $ua)))
	{
		$_SESSION[$category] = "desktop";
	}  
	// Otherwise assume it is a Mobile Device
	else {
		$_SESSION[$category] = "mobile";
	}
	
}// End if session not set


// Categorize Tablets as desktops
if ($catergorize_tablets_as_desktops && $_SESSION[$category] == "tablet"){
	$_SESSION[$category] = "desktop";
}

// Categorize TVs as desktops
if ($catergorize_tvs_as_desktops && $_SESSION[$category] == "tv"){
	$_SESSION[$category] = "desktop";
}

// Sets $device = to what category UA falls into
$device = $_SESSION[$category];
return $device;

}// End categorizr function

// Calls categorizr
categorizr();
 
// Returns true if desktop user agent is detected
function isDesktop(){
	$device = categorizr();
	if($device == "desktop"){
		return TRUE;
	}
	return FALSE;
}
// Returns true if tablet user agent is detected
function isTablet(){
	$device = categorizr();
	if($device == "tablet"){
		return TRUE;
	}
	return FALSE;
}
// Returns true if tablet user agent is detected
function isTV(){
	$device = categorizr();
	if($device == "tv"){
		return TRUE;
	}
	return FALSE;
}
// Returns true if mobile user agent is detected
function isMobile(){
	$device = categorizr();
	if($device == "mobile"){
		return TRUE;
	}
	return FALSE;
}
?>