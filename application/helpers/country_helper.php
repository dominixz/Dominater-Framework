<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright	Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Country Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Language and Government
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/country_helper.html
 */

// ------------------------------------------------------------------------

if ( !function_exists( 'HI_maxmind' ) ) {
function HI_maxmind() {}
include("maxmind/geoipcity.inc");
include("maxmind/geoipregionvars.php");
}

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */	
if ( ! function_exists('lang'))
{
	function lang($line, $id = '')
	{
		$Herb =& get_instance();
		$line = $Herb->lang->line($line);

		if ($id != '')
		{
			$line = '<label for="'.$id.'">'.$line."</label>";
		}

		return $line;
	}
}

if ( ! function_exists('show_my_language'))
{
function show_my_language() {
     return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) . "\n" . " HTTP_ACCEPT_LANGUAGE: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'];
}
}

if ( ! function_exists('find_key_in_file'))
{
   /*
    * Usage: returns a particular code in a simplified keyfile
    *  by loading the file, finding the key, returning the value,
    *  closing the file and tossing all keys and values read.
    * File example:
    *   [[codename]]
    *     Insert text or mixed html on next line
    *   [[end]] or [[next codename]]
    * 
    */
   function find_key_in_file( $filename, $code ) {
      $pairs = prefetch_keys( $filename );
      return $pairs["$code"];
   }
}


if ( ! function_exists('prefetch_keys'))
{   
   /*
    * Option to prefetch and return enumerated array of key/value pairs,
    * indexed by keynames.
    * 
    * Currently does not provide any code injection security on keynames or values.
    */
   function prefetch_keys( $filename ) { //echo 'decode keys: ' . $filename . '<br>';
      $new_baseurl = find_baseurl();
      if ( substr_count($filename,".ger") <1 ) return prefetch_keys_special($filename);
      
       $fp = @fopen($filename,'r');
       if ( !$fp ) { echo 'File not found: ' . $filename; return NULL; }
       $contents =  str_replace("\n", "", fread($fp, filesize($filename)));
       
       $length = strlen($contents);
       $c = 0;
        while( $c < $length ) { 
            if ( $contents[$c] == '[' ) {
              $c++;
              if ( $c < $length-1 && $contents[$c] == '[' ) { 
                $c++;
                $kbuffer=""; $lc = 'a';
                while( $contents[$c] != ']' && $c < $length-1 ) {
                    if ( !( $lc == $contents[$c] && $lc == ' ' ) ) // single space
                    $kbuffer = $kbuffer . $contents[$c];
                    $lc = $contents[$c];
                    $c++;
                }
                if ( $contents[$c] == ']' && $c < $length-1 ) $c++;
                if ( $contents[$c] == ']' && $c < $length-1 ) $c++;
                $vbuffer=""; $lc = 'a';
                while( $c < $length-1 && $contents[$c] != '['  ) {
                    if ( !( $lc == $contents[$c] && $lc == ' ' ) ) // single space
                    $vbuffer = $vbuffer . $contents[$c];
                    $lc = $contents[$c];
                    $c++;
                }
                $pairs["$kbuffer"]=str_replace("http://pickpark.com/", $new_baseurl, $vbuffer); //echo $kbuffer . '=' . $pairs["$kbuffer"] . '<br>';
              }
            }
            else $c++;
        }
        return $pairs;
   }
}
   
   /*
    * Option to prefetch and return enumerated array of key/value pairs,
    * indexed by keynames.
    * 
    * Currently does not provide any code injection security on keynames or values.
    */
if ( ! function_exists('prefetch_keys_special'))
{   
   function prefetch_keys_special( $filename ) { //echo 'decode keys: ' . $filename . '<br>';
       $fp = @fopen($filename,'r');
       if ( !$fp ) { echo 'File not found: ' . $filename; return NULL; }
       $contents = str_replace("\n", "",  mb_convert_encoding( (fread($fp, filesize($filename))), 'HTML-ENTITIES','UTF-8') );
       
       $length = strlen($contents);
       $c = 0;
        while( $c < $length ) { 
            if ( $contents[$c] == '[' ) {
              $c++;
              if ( $c < $length-1 && $contents[$c] == '[' ) { 
                $c++;
                $kbuffer=""; $lc = 'a';
                while( $contents[$c] != ']' && $c < $length-1 ) {
                    if ( !( $lc == $contents[$c] && $lc == ' ' ) ) // single space
                    $kbuffer = $kbuffer . $contents[$c];
                    $lc = $contents[$c];
                    $c++;
                }
                if ( $contents[$c] == ']' && $c < $length-1 ) $c++;
                if ( $contents[$c] == ']' && $c < $length-1 ) $c++;
                $vbuffer=""; $lc = 'a';
                while( $c < $length-1 && $contents[$c] != '['  ) {
                    if ( !( $lc == $contents[$c] && $lc == ' ' ) ) // single space
                    $vbuffer = $vbuffer . $contents[$c];
                    $lc = $contents[$c];
                    $c++;
                }
                $pairs["$kbuffer"]=$vbuffer; //echo $kbuffer . '=' . $pairs["$kbuffer"] . '<br>';
              }
            }
            else $c++;
        }
        return $pairs;
   }
}


/*     
ISO 3166 Country Codes
Below are ISO codes for countries, with the addition of MaxMind-specific codes for Europe, Asia Pacific Region, Anonymous Proxy and Satellite Provider.
Please note that "EU" and "AP" codes are only used when a specific country code has not been designated (see FAQ). Blocking or re-directing by "EU" or
"AP" will only affect a small portion of IP addresses. Instead, you should list the countries you want to block/re-direct individually.
*/
   
if ( ! function_exists('get_lang_header'))
{
 function get_lang_header( $lng ) {
   if ( $lng == "ger" ) {
      header('Content-Type: text/html; charset=ISO-8859');
     return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' . 
            '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de"><head>
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859">
            ';
   }
   else {
       header('Content-Type: text/html; charset=UTF-8');      
     echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
     echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '" lang="' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '"><head>
           <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
           ';
   }   
 }
}


if ( ! function_exists('find_language'))
{   
   /*
    * Function: determine target browser language setting
    * Newly added: Support for GeoLite City (Free locater)
    */
   function find_language( ) {
      if ( isset($_GET['geolang']) ) {
         $lang = $_GET['geolang'];
         $country_name="";
         $region_name="";
         $region_code="";
      }
      else {
            // uncomment for Shared Memory support
            // geoip_load_shared_mem("/usr/local/share/GeoIP/GeoIPCity.dat");
            // $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat",GEOIP_SHARED_MEMORY);

            $gi = geoip_open("maxmind/GeoLiteCity.dat",GEOIP_STANDARD);

            $record = geoip_record_by_addr($gi,$_SERVER['REMOTE_ADDR']);
            
            $country_name = $record->country_name;
            //$region_name =  $GEOIP_REGION_NAME[$record->country_code][$record->region];
            $region_code =  $record->region;
            $lang = $record->country_code;               
            geoip_close($gi);
      }
      
      // Browser language fallback (only supports known translations)
      // Add more as new translations emerge
      // Converts 2-letter browser language codes into 3-letter country codes
      if ( is_null($lang) ) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if ( $lang == 'en'
          || $lang == 'en-us'
          || $lang == 'en-gb'
          || $lang == 'en-ca' ) return "eng";
        else
        if ( $lang == 'fr' || $lang == 'fr-ca' ) return "fra";
        else
        if ( $lang == 'it' ) return "ita";
        else
        if ( $lang == 'ja' ) return "jap";
        else
        if ( $lang == 'de' ) return "ger";
        else
        if ( $lang == 'es' || $lang == 'es-mx' || $lang == 'es-pe' ) return "spa";
      }
      else
            
      /* Quebecois */
      if ( $lang == "CA" && $region_name == "Quebec" ) return "fra";
      else
       if ( /* Albanian */
          $lang == "AL" || $country_name == "Albania"
       || $lang == "ME"  || $country_name == "Montenegro" ) $lang = "alb";
       else if ( /* Armenian */
          $lang == "AM" || $country_name == "Armenia" ) $lang = "arm";
       else if ( /* Bosnian */
          $lang == "BA" || $country_name == "Bosnia and Herzegovina" ) $lang = "bos";
       else if ( /* Azeri */
          $lang == "AZ" || $country_name == "Azerbaijan" ) $lang = "aze";
       else if ( /* Bengali */
          $lang == "BD" || $country_name == "Bangladesh" ) $lang = "ben";
       else if ( /* Estonian */
          $lang == "EE" || $country_name == "Estonia" ) $lang = "est";
       else if ( /* Amharic */
          $lang == "ET" || $country_name == "Ethiopia" ) $lang = "amh";
       else if ( /* Thai */
          $lang == "TH" || $country_name == "Thailand" ) $lang = "thai";
       else if ( /* Turkmen */
          $lang == "TM" || $country_name == "Turkmenistan" ) $lang = "turkmen";
       else if ( /* Ukraine */
          $lang == "UA" || $country_name == "Ukraine" ) $lang = "ukraine";
       else if ( /* Georgian */
          $lang == "GE" || $country_name == "Georgia" ) $lang = "georg";
       else if ( /* Croatian */
          $lang == "HR" || $country_name == "Croatia" ) $lang = "croat";
       else if ( /* Hungarian */
          $lang == "HU" || $country_name == "Hungary" ) $lang = "hun";
       else if ( /* Indonesian */
          $lang == "ID" || $country_name == "Indonesia" ) $lang = "ind";
       else if ( /* Icelandic */
          $lang == "IS" || $country_name == "Iceland" ) $lang = "ice";
       else if ( /* Burmese */
          $lang == "MM" || $country_name == "Myanmar" ) $lang = "myan";
       else if ( /* Mongolian */
          $lang == "MN" || $country_name == "Mongolia" ) $lang = "mon";
       else if ( /* Lithuanian */
          $lang == "LT" || $country_name == "Lithuania" ) $lang = "lit";
       else if ( /* Khmer */
          $lang == "KH" || $country_name == "Cambodia" ) $lang = "khm";
       else if ( /* Dhivehi */
          $lang == "MV" || $country_name == "Maldives" ) $lang = "dhi";
       else if ( /* Czech */
          $lang == "CZ" || $country_name == "Czech Republic" ) $lang = "cze";
       else if ( /* Slovene */
          $lang == "SI" || $country_name == "Slovenia" ) $lang = "slo";
       else if ( /* Slovak */
          $lang == "SK" || $country_name == "Slovakia" ) $lang = "slovak";
       else if ( /* Malay */
          $lang == "MY" || $country_name == "Malaysia" ||
          $lang == "BN" || $country_name == "Brunei Darussalam" ) $lang = "mal";
       else if ( /* Latvian */
          $lang == "LV" || $country_name == "Latvia" ) $lang = "lat";
       else if ( /* Norwegian */
          $lang == "NO" || $country_name == "Norway" ||
          $lang == "SJ" || $country_name == "Svalbard and Jan Mayen" ) $lang = "nor";
       else if ( /* Laos */
          $lang == "LA" || $country_name == "Lao People's Democratic Republic" ) $lang="lao";
       else if ( /* Serbian */
          $lang == "RS" || $country_name == "Serbia" ) $lang="serb";
       else if ( /* Uzbek */
          $lang == "UZ" || $country_name == "Uzbekistan" ) $lang = "uzb";
       else if ( /* Nepali */
          $lang == "NP" || $country_name == "Nepal" ) $lang = "nep";
       else if ( /* Vietnamese */
          $lang == "VN" || $country_name == "Vietnam" ) $lang = "viet";
       else if ( /* Polish */
          $lang == "PL" || $country_name == "Poland" ) $lang = "pol";
       else if ( /* Catalan */
          $lang == "AD" || $country_name == "Andorra" ) $lang = "cat";

       else if ( /* Danish */
          $lang == "DK" || $country_name == "Denmark" ||
          $lang == "GL" || $country_name == "Greenland" ||
          $lang == "FO" || $country_name == "Faroe Islands" ) $lang = "dan";

       else if ( /* German */
          $lang == "DE" || $country_name == "Germany" ||
          $lang == "LI" || $country_name == "Liechtenstein" ||
          $lang == "AT" || $country_name == "Austria" ) $lang = "ger";

       else if ( /* Greek */
          $lang == "GR" || $country_name == "Greece" ) $lang = "gre";

       else if ( /* Korean */
          $lang == "KP" || $country_name == "Korea, Democratic People's Republic of" ||
          $lang == "KR" || $country_name == "Korea, Republic of" ) $lang="kor";

       else if ( /* Swedish */
          $lang == "SE" || $country_name == "Sweden" ||
          $lang == "AX" || $country_name == "Aland Islands" ) $lang = "swe";

       else if ( /* Arabic */
          $lang == "BH" || $country_name == "Bahrain" ||
          $lang == "MA" || $country_name == "Morocco" ||
          $lang == "QA" || $country_name == "Qatar" ||
          $lang == "YE" || $country_name == "Yemen" ||
          $lang == "IQ" || $country_name == "Iraq" ||
          $lang == "OM" || $country_name == "Oman" ||
          $lang == "IL" || $country_name == "Israel" ||
          $lang == "SY" || $country_name == "Syrian Arab Republic" ||
          $lang == "SA" || $country_name == "Saudi Arabia" ||
          $lang == "TN" || $country_name == "Tunisia" ||
          $lang == "SO" || $country_name == "Somalia" ||
          $lang == "MR" || $country_name == "Mauritania" ||
          $lang == "KW" || $country_name == "Kuwait" ||
          $lang == "TD" || $country_name == "Chad" ||
          $lang == "JO" || $country_name == "Jordan" ||
          $lang == "ER" || $country_name == "Eritrea" ||
          $lang == "LY" || $country_name == "Libyan Arab Jamahiriya" ||
          $lang == "DZ" || $country_name == "Algeria" ||
          $lang == "EG" || $country_name == "Egypt" ||
          $lang == "EH" || $country_name == "Western Sahara" ||
          $lang == "LB" || $country_name == "Lebanon" ||
          $lang == "PS" || $country_name == "Palestinian Territory" ||
          $lang == "AE" || $country_name == "United Arab Emirates" ) $lang = "ara";

       else if ( /* Persian */
          $lang == "TJ" || $country_name == "Tajikistan" ||
          $lang == "AF" || $country_name == "Afghanistan" ||
          $lang == "IR" || $country_name == "Iran, Islamic Republic of" ) $lang = "per";

       else if ( /* Turkish */
          $lang == "CY" || $country_name == "Cyprus" ||
          $lang == "TR" || $country_name == "Turkey" ||
          $lang == "BG" || $country_name == "Bulgaria" ||
          $lang == "MK" || $country_name == "Macedonia"  ) $lang = "turk";

       else if ( /* Dutch */
          $lang == "NL" || $country_name == "Netherlands" ||
          $lang == "SR" || $country_name == "Suriname" || 
          $lang == "AW" || $country_name == "Aruba" ||
          $lang == "AN" || $country_name == "Netherlands Antilles" ) $lang = "dutch";

       else if ( /* Italian */
          $lang == "IT" || $country_name == "Italy" ||
          $lang == "SM" || $country_name == "San Marino" ||
          $lang == "VA" || $country_name == "Holy See (Vatican City State)" ) $lang = "ita";

       else if ( /* Mandarin (Chinese) */
          $lang == "AP" || $country_name == "Asia/Pacific Region" ||
          $lang == "CN" || $country_name == "China" ||
          $lang == "TW" || $country_name == "Taiwan" ||
          $lang == "HK" || $country_name == "Hong Kong" ||
          $lang == "MO" || $country_name == "Macao" ) $lang = "man";

   /*    else if ( // English 
          $lang == "GD" || $country_name == "Grenada" ||
          $lang == "UM" || $country_name == "United States Minor Outlying Islands" ||
          $lang == "US" || $country_name == "United States" ||
          $lang == "GB" || $country_name == "United Kingdom" ||
          $lang == "AU" || $country_name == "Australia" ||
          $lang == "CX" || $country_name == "Christmas Island" ||
          $lang == "EU" || $country_name == "Europe" ||
          $lang == "IE" || $country_name == "Ireland" ||
          $lang == "FJ" || $country_name == "Fiji" ||
          $lang == "FK" || $country_name == "Falkland Islands (Malvinas)" ||
          $lang == "A1" || $country_name == "Anonymous Proxy" ||
          $lang == "A2" || $country_name == "Satellite Provider" ||
          $lang == "PH" || $country_name == "Philippines" ||
          $lang == "MT" || $country_name == "Malta" ||
          $lang == "MW" || $country_name == "Malawi" ||
          $lang == "NA" || $country_name == "Namibia" ||
          $lang == "NG" || $country_name == "Nigeria" ||
          $lang == "NR" || $country_name == "Nauru" ||
          $lang == "MU" || $country_name == "Mauritius" ||
          $lang == "CM" || $country_name == "Cameroon" ||
          $lang == "KI" || $country_name == "Kiribati" ||
          $lang == "ZA" || $country_name == "South Africa" ||
          $lang == "ZM" || $country_name == "Zambia" ||
          $lang == "ZW" || $country_name == "Zimbabwe" ||
          $lang == "LR" || $country_name == "Liberia" ||
          $lang == "LS" || $country_name == "Lesotho" ||
          $lang == "NZ" || $country_name == "New Zealand" ||
          $lang == "CK" || $country_name == "Cook Islands" ||
          $lang == "TO" || $country_name == "Tonga" ||
          $lang == "TT" || $country_name == "Trinidad and Tobago" ||
          $lang == "TV" || $country_name == "Tuvalu" ||
          $lang == "TZ" || $country_name == "Tanzania, United Republic of" ||
          $lang == "PW" || $country_name == "Palau" ||
          $lang == "SD" || $country_name == "Sudan" ||
          $lang == "SG" || $country_name == "Singapore" ||
          $lang == "SL" || $country_name == "Sierra Leone" ||
          $lang == "SB" || $country_name == "Solomon Islands" ||
          $lang == "SC" || $country_name == "Seychelles" ||
          $lang == "UG" || $country_name == "Uganda" ||
          $lang == "VU" || $country_name == "Vanuatu" ||
          $lang == "WS" || $country_name == "Samoa" ||
          $lang == "VC" || $country_name == "Saint Vincent and the Grenadines" ||
          $lang == "VG" || $country_name == "Virgin Islands, British" ||
          $lang == "VI" || $country_name == "Virgin Islands, U.S." ||
          $lang == "IM" || $country_name == "Isle of Man" ||
          $lang == "TK" || $country_name == "Tokelau" ||
          $lang == "NF" || $country_name == "Norfolk Island" ||
          $lang == "NU" || $country_name == "Niue" ||
          $lang == "PK" || $country_name == "Pakistan" ||
          $lang == "MS" || $country_name == "Montserrat" ||
          $lang == "KY" || $country_name == "Cayman Islands" ||
          $lang == "LC" || $country_name == "Saint Lucia" ||
          $lang == "MG" || $country_name == "Madagascar" ||
          $lang == "MH" || $country_name == "Marshall Islands" ||
          $lang == "MP" || $country_name == "Northern Mariana Islands" ||
          $lang == "GU" || $country_name == "Guam" ||
          $lang == "GG" || $country_name == "Guernsey" ||
          $lang == "GH" || $country_name == "Ghana" ||
          $lang == "JE" || $country_name == "Jersey" ||
          $lang == "JM" || $country_name == "Jamaica" ||
          $lang == "KE" || $country_name == "Kenya" || 
          $lang == "IN" || $country_name == "India" ||
          $lang == "GI" || $country_name == "Gibraltar" ||
          $lang == "GM" || $country_name == "Gambia" ||
          $lang == "PN" || $country_name == "Pitcairn" ||
          $lang == "PR" || $country_name == "Puerto Rico" ||
          $lang == "TC" || $country_name == "Turks and Caicos Islands" ||
          $lang == "PG" || $country_name == "Papua New Guinea" ||
          $lang == "DJ" || $country_name == "Djibouti" ||
          $lang == "RW" || $country_name == "Rwanda" ||
          $lang == "NE" || $country_name == "Niger" ||
          $lang == "BI" || $country_name == "Burundi" ||
          $lang == "LU" || $country_name == "Luxembourg" ||
          $lang == "LK" || $country_name == "Sri Lanka" ||
          $lang == "BM" || $country_name == "Bermuda" ||
          $lang == "AQ" || $country_name == "Antarctica" ||
          $lang == "HM" || $country_name == "Heard Island and McDonald Islands"  || // uninhabited
          $lang == "BV" || $country_name == "Bouvet Island"  || // uninhabited
          $lang == "AS" || $country_name == "American Samoa" ||
          $lang == "AG" || $country_name == "Antigua and Barbuda" ||
          $lang == "CA" || $country_name == "Canada" ||
          $lang == "BS" || $country_name == "Bahamas" ||
          $lang == "BB" || $country_name == "Barbados" ||
          $lang == "IO" || $country_name == "British Indian Ocean Territory" ||
          $lang == "SZ" || $country_name == "Swaziland" ||
          $lang == "AI" || $country_name == "Anguilla" ||
          $lang == "KN" || $country_name == "Saint Kitts and Nevis" ||
          $lang == "GS" || $country_name == "South Georgia and the South Sandwich Islands" ||
          $lang == "FM" || $country_name == "Micronesia, Federated States of" ||
          $lang == "GY" || $country_name == "Guyana"  ) $lang = "eng"; */

       else if ( /* Finnish */
          $lang == "FI" || $country_name == "Finland" ) $lang = "fi";

       else if ( /* Tswana */
          $lang == "BW" || $country_name == "Botswana" ) $lang = "tsw";

       else if ( /* Spanish (Spain) */                   
          $lang == "MX" || $country_name == "Mexico" ||
          $lang == "ES" || $country_name == "Spain" ||
          $lang == "BO" || $country_name == "Bolivia" ||
          $lang == "CL" || $country_name == "Chile" ||
          $lang == "CO" || $country_name == "Colombia" ||
          $lang == "CR" || $country_name == "Costa Rica" ||
          $lang == "CC" || $country_name == "Cocos (Keeling) Islands" ||
          $lang == "CU" || $country_name == "Cuba" ||
          $lang == "DO" || $country_name == "Dominican Republic" ||
          $lang == "EC" || $country_name == "Ecuador" ||
          $lang == "PY" || $country_name == "Paraguay" ||
          $lang == "PA" || $country_name == "Panama" ||
          $lang == "GT" || $country_name == "Guatemala" ||
          $lang == "PE" || $country_name == "Peru" ||
          $lang == "UY" || $country_name == "Uruguay" ||
          $lang == "NI" || $country_name == "Nicaragua" ||
          $lang == "SV" || $country_name == "El Salvador" ||
          $lang == "HN" || $country_name == "Honduras" ||
          $lang == "BZ" || $country_name == "Belize" ) $lang = "spa";
       else if (
          $lang == "AR" || $country_name == "Argentina" ) $lang = "spa_arg";

       else if ( /* Portuguese */
          $lang == "TL" || $country_name == "Timor-Leste" ||
          $lang == "ST" || $country_name == "Sao Tome and Principe" ||
          $lang == "MZ" || $country_name == "Mozambique" ||
          $lang == "PT" || $country_name == "Portugal" ||
          $lang == "VE" || $country_name == "Venezuela" ||
          $lang == "BR" || $country_name == "Brazil" ||
          $lang == "CV" || $country_name == "Cape Verde" ||
          $lang == "AO" || $country_name == "Angola" ||
          $lang == "GW" || $country_name == "Guinea-Bissau" ) $lang = "port";
       
       else if ( /* French */
          $lang == "FR" || $country_name == "France" ||
          $lang == "TF" || $country_name == "French Southern Territories" ||
          $lang == "CD" || $country_name == "Congo, The Democratic Republic of the" ||
          $lang == "MC" || $country_name == "Monaco" ||
          $lang == "CF" || $country_name == "Central African Republic" ||
          $lang == "CI" || $country_name == "Cote d'Ivoire" ||
          $lang == "ML" || $country_name == "Mali" ||
          $lang == "BE" || $country_name == "Belgium" ||
          $lang == "CG" || $country_name == "Congo" ||
          $lang == "GQ" || $country_name == "Equatorial Guinea" ||
          $lang == "KM" || $country_name == "Comoros" ||
          $lang == "GA" || $country_name == "Gabon" ||
          $lang == "CH" || $country_name == "Switzerland" ||
          $lang == "TG" || $country_name == "Togo" ||
          $lang == "BF" || $country_name == "Burkina Faso" ||
          $lang == "SN" || $country_name == "Senegal" ||
          $lang == "PF" || $country_name == "French Polynesia" ||
          $lang == "NC" || $country_name == "New Caledonia" ||
          $lang == "GP" || $country_name == "Guadeloupe" ||
          $lang == "DM" || $country_name == "Dominica" ||
          $lang == "PM" || $country_name == "Saint Pierre and Miquelon" ||
          $lang == "RE" || $country_name == "Reunion" || 
          $lang == "WF" || $country_name == "Wallis and Futuna" ||
          $lang == "MQ" || $country_name == "Martinique" ||
          $lang == "YT" || $country_name == "Mayotte" ||
          $lang == "BJ" || $country_name == "Benin" ||
          $lang == "BT" || $country_name == "Bhutan" ||
          $lang == "HT" || $country_name == "Haiti" ||
          $lang == "GF" || $country_name == "French Guiana" ||
          $lang == "GN" || $country_name == "Guinea" ) $lang = "fra";

       else if ( /* Russian */
          $lang == "BY" || $country_name == "Belarus" ||
          $lang == "KG" || $country_name == "Kyrgyzstan" ||
          $lang == "KZ" || $country_name == "Kazakhstan" ||
          $lang == "RU" || $country_name == "Russian Federation" ) $lang = "rus";

       else if ( /* Romanian */
          $lang == "RO" || $country_name == "Romania" ||
          $lang == "MD" || $country_name == "Moldova, Republic of" ) $lang = "rom";


       else if ( /* Japanese */
          $lang == "JP" || $country_name == "Japan" ) $lang = "jap";
       
       else $lang = "eng";
       
        return $lang;
   }
}


if ( ! function_exists('global_lang_prefetch'))
{      
   if ( !isset($text_) ) $text_ = '';
   function global_lang_prefetch( $prefix ) {
      global $text_;
      $text_ = prefetch_keys( $prefix . "." . find_language() );     
   }
}

if ( ! function_exists('merge_keys'))
{     
   function merge_keys( $a, $b ) {
      reset($b);
      while (list($key, $value) = each($b)) $a[$key] = $value;
      return $a;
   }
}

if ( ! function_exists('prefetch_language'))
{    
   function prefetch_language( $prefix ) {
      $filename = $prefix . "." . find_language();
      if ( !file_exists($filename) ) {  // Default to English if not found
         $filename = $prefix . "." . "eng";
      }
      $keys_a = prefetch_keys($prefix."."."eng");
      $keys_b = prefetch_keys( $filename );
      return (merge_keys( $keys_a, $keys_b ));
   }
}

if ( ! function_exists('show_keyfile'))
{  
   /* Load, parse, display and destroy keys and values stored in a paired keyfile. */   
   function show_keyfile( $filename ) {
    echo var_dump( prefetch_keys( $filename ) );
   }
}

if ( ! function_exists('fast_lang_block'))
{  
   /*
    * Doesn't check to see if the language file is supported.
    */
   function fast_lang_block( $prefix, $code, $language ) {
      return find_key_in_file( $prefix . '.' . $language, $code );
   }
}

if ( ! function_exists('safe_lang_block'))
{  
   /*
    * Checks to see if the language is supported, defaults to English,
    * does not provide code injection protection.
    */
   function safe_lang_block( $prefix, $key, $language ) {
        if ( $language == "eng" ) {  // English
            return find_key_in_file( $prefix . '.eng', $key );
        } else 
        if ( $language == "ger" ) {  // German
            return find_key_in_file( $prefix . '.ger', $key );
        } else 
        if ( $language == "rus" ) {  // Russian
            return find_key_in_file( $prefix . '.rus', $key );
        } else 
        if ( $language == "fra" ) {  // French
            return find_key_in_file( $prefix . '.fra', $key );
        } else
        if ( $language == "hin" ) {  // Hindi
            return find_key_in_file( $prefix . '.hin', $key );
        } else 
        if ( $language == "jap" ) {  // Japanese
            return find_key_in_file( $prefix . '.jap', $key );
        } else 
        if ( $language == "pol" ) {  // Polish
            return find_key_in_file( $prefix . '.pol', $key );
        } else         
        if ( $language == "chi" ) {  // Chinese
            return find_key_in_file( $prefix . '.chi', $key );
        } else         
        if ( $language == "cat" ) {  // Catalan
            return find_key_in_file( $prefix . '.cat', $key );
        } else 
        if ( $language == "spa" ) {  // Spanish
            return find_key_in_file( $prefix . '.spa', $key );
        } else
        {
            return safe_lang_block( $key, "eng" );
        }        
   }
}
   
if ( ! function_exists('find_currency') )
{     
   /*
    * Function: convert country code to its currency code
    *   source: ISO 4217 Currency Code List
    * Newly added: Support for GeoLite City (Free locater)
    */
   function find_currency( $lang, $country_name="", $region_name="" ) {
            
      if ( $lang == "CA" || $country_name == "Canada" ) return "CAD";

       else if ( $lang == "US" || $country_name == "United States"
              || $lang == "VI" || $country_name == "Virgin Islands, U.S." 
              || $lang == "UM" || $country_name == "United States Minor Outlying Islands" ) $lang = "USD";      

       else if ( $lang == "VG" || $country_name == "Virgin Islands, British"
              || $lang == "GB" || $country_name == "United Kingdom" ) $lang = "GBP";
       
       else if ( $lang == "IM" || $country_name == "Isle of Man" ) $lang="IMP";

       else if ( // Euros
          $lang == "IE" || $country_name == "Ireland" ||
          $lang == "EU" || $country_name == "Europe" ||
          $lang == "DE" || $country_name == "Germany" ||
          $lang == "LI" || $country_name == "Liechtenstein" ||
          $lang == "AT" || $country_name == "Austria" ||
          $lang == "GR" || $country_name == "Greece" ) $lang = "EUR";  // Euro
       else if ( /* Slovene */
          $lang == "SI" || $country_name == "Slovenia" ) $lang = "EUR";  // Euro
       else if ( /* Slovak */
          $lang == "SK" || $country_name == "Slovakia" ) $lang = "EUR"; // Euro
       else if ( /* Catalan */
          $lang == "AD" || $country_name == "Andorra" ) $lang = "EUR"; // Euro      
       else if ( /* Finnish */
          $lang == "FI" || $country_name == "Finland" ) $lang = "EUR";

       
       else if ( /* Albanian */
          $lang == "AL" || $country_name == "Albania"
       || $lang == "ME"  || $country_name == "Montenegro" ) $lang = "ALL"; // Leke
       else if ( /* Armenian */
          $lang == "AM" || $country_name == "Armenia" ) $lang = "AWG"; // Drams
       else if ( /* Bosnian */
          $lang == "BA" || $country_name == "Bosnia and Herzegovina" ) $lang = "BAM"; // Convertible Marka
       else if ( /* Azeri */
          $lang == "AZ" || $country_name == "Azerbaijan" ) $lang = "AZN"; // New Manats
       else if ( /* Bengali */
          $lang == "BD" || $country_name == "Bangladesh" ) $lang = "BDT"; // Baka  
       else if ( /* Estonian */
          $lang == "EE" || $country_name == "Estonia" ) $lang = "EEK";  // Krooni
       else if ( /* Amharic */
          $lang == "ET" || $country_name == "Ethiopia" ) $lang = "ETB";  // Birr
       else if ( /* Thai */
          $lang == "TH" || $country_name == "Thailand" ) $lang = "THB";  // Baht
       else if ( /* Turkmen */
          $lang == "TM" || $country_name == "Turkmenistan" ) $lang = "TMM";  // Manats
       else if ( /* Ukraine */
          $lang == "UA" || $country_name == "Ukraine" ) $lang = "UAH"; // Hryvnia
       else if ( /* Georgian */
          $lang == "GE" || $country_name == "Georgia" ) $lang = "GEL"; // Lari
       else if ( /* Croatian */
          $lang == "HR" || $country_name == "Croatia" ) $lang = "HRK"; // Kuna
       else if ( /* Hungarian */
          $lang == "HU" || $country_name == "Hungary" ) $lang = "HUF"; // Forint
       else if ( /* Indonesian */
          $lang == "ID" || $country_name == "Indonesia" ) $lang = "IDR"; // Rupiahs
       else if ( /* Icelandic */
          $lang == "IS" || $country_name == "Iceland" ) $lang = "ISK";  // Kronur
       else if ( /* Burmese */
          $lang == "MM" || $country_name == "Myanmar" ) $lang = "MMK"; // Kyats
       else if ( /* Mongolian */
          $lang == "MN" || $country_name == "Mongolia" ) $lang = "MNT"; // Tugriks
       else if ( /* Lithuanian */
          $lang == "LT" || $country_name == "Lithuania" ) $lang = "LTL"; // Litai
       else if ( /* Khmer */
          $lang == "KH" || $country_name == "Cambodia" ) $lang = "KHR"; // Riels
       else if ( /* Dhivehi */
          $lang == "MV" || $country_name == "Maldives" ) $lang = "MVR"; // Rufiyaa
       else if ( /* Czech */
          $lang == "CZ" || $country_name == "Czech Republic" ) $lang = "CZK"; // Koruny
       else if ( /* Malay */
          $lang == "MY" || $country_name == "Malaysia" ||
          $lang == "BN" || $country_name == "Brunei Darussalam" ) $lang = "MYR"; // Ringgits
       else if ( /* Latvian */
          $lang == "LV" || $country_name == "Latvia" ) $lang = "LVL"; // Lati
       else if ( /* Norwegian */
          $lang == "NO" || $country_name == "Norway" ||
          $lang == "SJ" || $country_name == "Svalbard and Jan Mayen" ) $lang = "NOK"; // Kroner
       else if ( /* Laos */
          $lang == "LA" || $country_name == "Lao People's Democratic Republic" ) $lang="LAK"; // Kips
       else if ( /* Serbian */
          $lang == "RS" || $country_name == "Serbia" ) $lang="RSD"; // Dinars
       else if ( /* Uzbek */
          $lang == "UZ" || $country_name == "Uzbekistan" ) $lang = "UZS"; // Sums
       else if ( /* Nepali */
          $lang == "NP" || $country_name == "Nepal" ) $lang = "NPR"; // Nepal Rupees
       else if ( /* Vietnamese */
          $lang == "VN" || $country_name == "Vietnam" ) $lang = "VND"; // Dong
       else if ( /* Polish */
          $lang == "PL" || $country_name == "Poland" ) $lang = "PLN"; // Zlotych 

       else if ( /* Danish */
          $lang == "DK" || $country_name == "Denmark" ||
          $lang == "GL" || $country_name == "Greenland" ||
          $lang == "FO" || $country_name == "Faroe Islands" ) $lang = "DKK"; // Kroner

       else if ( /* Korean */
          $lang == "KP" || $country_name == "Korea, Democratic People's Republic of" ||
          $lang == "KR" || $country_name == "Korea, Republic of" ) $lang="KPW"; // Won

       else if ( /* Swedish */
          $lang == "SE" || $country_name == "Sweden" ||
          $lang == "AX" || $country_name == "Aland Islands" ) $lang = "SEK"; // Kronor

       else if (
          $lang == "EG" || $country_name == "Egypt" ) $lang = "EGP";

       else if ( /* Arabic */
          $lang == "BH" || $country_name == "Bahrain" ||
          $lang == "MA" || $country_name == "Morocco" ||
          $lang == "QA" || $country_name == "Qatar" ||
          $lang == "YE" || $country_name == "Yemen" ||
          $lang == "IQ" || $country_name == "Iraq" ||
          $lang == "OM" || $country_name == "Oman" ||
          $lang == "IL" || $country_name == "Israel" ||
          $lang == "SY" || $country_name == "Syrian Arab Republic" ||
          $lang == "SA" || $country_name == "Saudi Arabia" ||
          $lang == "TN" || $country_name == "Tunisia" ||
          $lang == "SO" || $country_name == "Somalia" ||
          $lang == "MR" || $country_name == "Mauritania" ||
          $lang == "KW" || $country_name == "Kuwait" ||
          $lang == "TD" || $country_name == "Chad" ||
          $lang == "JO" || $country_name == "Jordan" ||
          $lang == "ER" || $country_name == "Eritrea" ||
          $lang == "LY" || $country_name == "Libyan Arab Jamahiriya" ||
          $lang == "DZ" || $country_name == "Algeria" ||
          $lang == "EH" || $country_name == "Western Sahara" ||
          $lang == "LB" || $country_name == "Lebanon" ||
          $lang == "PS" || $country_name == "Palestinian Territory" ||
          $lang == "AE" || $country_name == "United Arab Emirates" ) $lang = "AED"; // Dirhams, UAE

       else if ( /* Persian */
          $lang == "TJ" || $country_name == "Tajikistan" ||
          $lang == "AF" || $country_name == "Afghanistan" ||
          $lang == "IR" || $country_name == "Iran, Islamic Republic of" ) $lang = "IRR"; // Rials

       else if ( /* Turkish */
          $lang == "CY" || $country_name == "Cyprus" ||
          $lang == "TR" || $country_name == "Turkey" ||
          $lang == "BG" || $country_name == "Bulgaria" ||
          $lang == "MK" || $country_name == "Macedonia"  ) $lang = "TRY"; // Lira

       else if ( /* Dutch */
          $lang == "NL" || $country_name == "Netherlands" ||
          $lang == "SR" || $country_name == "Suriname" || 
          $lang == "AW" || $country_name == "Aruba" ||
          $lang == "AN" || $country_name == "Netherlands Antilles" ) $lang = "ANG"; // Florins

       else if ( /* Italian */
          $lang == "IT" || $country_name == "Italy" ||
          $lang == "SM" || $country_name == "San Marino" ||
          $lang == "VA" || $country_name == "Holy See (Vatican City State)" ) $lang = "EUR"; // Euro

       else if ( /* Mandarin (Chinese) */
          $lang == "AP" || $country_name == "Asia/Pacific Region" ||
          $lang == "CN" || $country_name == "China" ||
          $lang == "TW" || $country_name == "Taiwan" ||
          $lang == "MO" || $country_name == "Macao" ) $lang = "CNY"; // China

       else if ( /* Hong Kong Dollars */ 
          $lang == "HK" || $country_name == "Hong Kong" ) $lang = "HKD";


   /*    else if ( // English 
          $lang == "GD" || $country_name == "Grenada" ||
          $lang == "UM" || $country_name == "United States Minor Outlying Islands" ||

          $lang == "AU" || $country_name == "Australia" ||
          $lang == "CX" || $country_name == "Christmas Island" ||
          
         
          $lang == "FJ" || $country_name == "Fiji" ||
          $lang == "FK" || $country_name == "Falkland Islands (Malvinas)" ||
          $lang == "A1" || $country_name == "Anonymous Proxy" ||
          $lang == "A2" || $country_name == "Satellite Provider" ||
          $lang == "PH" || $country_name == "Philippines" ||
          $lang == "MT" || $country_name == "Malta" ||
          $lang == "MW" || $country_name == "Malawi" ||
          $lang == "NA" || $country_name == "Namibia" ||
          $lang == "NG" || $country_name == "Nigeria" ||
          $lang == "NR" || $country_name == "Nauru" ||
          $lang == "MU" || $country_name == "Mauritius" ||
          $lang == "CM" || $country_name == "Cameroon" ||
          $lang == "KI" || $country_name == "Kiribati" ||
          $lang == "ZA" || $country_name == "South Africa" ||
          $lang == "ZM" || $country_name == "Zambia" ||
          $lang == "ZW" || $country_name == "Zimbabwe" ||
          $lang == "LR" || $country_name == "Liberia" ||
          $lang == "LS" || $country_name == "Lesotho" ||
          $lang == "NZ" || $country_name == "New Zealand" ||
          $lang == "CK" || $country_name == "Cook Islands" ||
          $lang == "TO" || $country_name == "Tonga" ||
          $lang == "TT" || $country_name == "Trinidad and Tobago" ||
          $lang == "TV" || $country_name == "Tuvalu" ||
          $lang == "TZ" || $country_name == "Tanzania, United Republic of" ||
          $lang == "PW" || $country_name == "Palau" ||
          $lang == "SD" || $country_name == "Sudan" ||
          $lang == "SG" || $country_name == "Singapore" ||
          $lang == "SL" || $country_name == "Sierra Leone" ||
          $lang == "SB" || $country_name == "Solomon Islands" ||
          $lang == "SC" || $country_name == "Seychelles" ||
          $lang == "UG" || $country_name == "Uganda" ||
          $lang == "VU" || $country_name == "Vanuatu" ||
          $lang == "WS" || $country_name == "Samoa" ||
          $lang == "VC" || $country_name == "Saint Vincent and the Grenadines" ||

          
          
          $lang == "TK" || $country_name == "Tokelau" ||
          $lang == "NF" || $country_name == "Norfolk Island" ||
          $lang == "NU" || $country_name == "Niue" ||
          $lang == "PK" || $country_name == "Pakistan" ||
          $lang == "MS" || $country_name == "Montserrat" ||
          $lang == "KY" || $country_name == "Cayman Islands" ||
          $lang == "LC" || $country_name == "Saint Lucia" ||
          $lang == "MG" || $country_name == "Madagascar" ||
          $lang == "MH" || $country_name == "Marshall Islands" ||
          $lang == "MP" || $country_name == "Northern Mariana Islands" ||
          $lang == "GU" || $country_name == "Guam" ||
          $lang == "GG" || $country_name == "Guernsey" ||
          $lang == "GH" || $country_name == "Ghana" ||
          $lang == "JE" || $country_name == "Jersey" ||
          $lang == "JM" || $country_name == "Jamaica" ||
          $lang == "KE" || $country_name == "Kenya" || 
          $lang == "IN" || $country_name == "India" ||
          $lang == "GI" || $country_name == "Gibraltar" ||
          $lang == "GM" || $country_name == "Gambia" ||
          $lang == "PN" || $country_name == "Pitcairn" ||
          $lang == "PR" || $country_name == "Puerto Rico" ||
          $lang == "TC" || $country_name == "Turks and Caicos Islands" ||
          $lang == "PG" || $country_name == "Papua New Guinea" ||
          $lang == "DJ" || $country_name == "Djibouti" ||
          $lang == "RW" || $country_name == "Rwanda" ||
          $lang == "NE" || $country_name == "Niger" ||
          $lang == "BI" || $country_name == "Burundi" ||
          $lang == "LU" || $country_name == "Luxembourg" ||
          $lang == "LK" || $country_name == "Sri Lanka" ||
          $lang == "BM" || $country_name == "Bermuda" ||
          $lang == "AQ" || $country_name == "Antarctica" ||
          $lang == "HM" || $country_name == "Heard Island and McDonald Islands"  || // uninhabited
          $lang == "BV" || $country_name == "Bouvet Island"  || // uninhabited
          $lang == "AS" || $country_name == "American Samoa" ||
          $lang == "AG" || $country_name == "Antigua and Barbuda" ||
          $lang == "BS" || $country_name == "Bahamas" ||
          $lang == "BB" || $country_name == "Barbados" ||
          $lang == "IO" || $country_name == "British Indian Ocean Territory" ||
          $lang == "SZ" || $country_name == "Swaziland" ||
          $lang == "AI" || $country_name == "Anguilla" ||
          $lang == "KN" || $country_name == "Saint Kitts and Nevis" ||
          $lang == "GS" || $country_name == "South Georgia and the South Sandwich Islands" ||
          $lang == "FM" || $country_name == "Micronesia, Federated States of" ||
          $lang == "GY" || $country_name == "Guyana"  ) $lang = "eng"; */


       else if ( /* Tswana */
          $lang == "BW" || $country_name == "Botswana" ) $lang = "BWP";

       else if ( /* Spanish (Spain) */                   
          $lang == "MX" || $country_name == "Mexico" ||
          $lang == "ES" || $country_name == "Spain" ||
          $lang == "BO" || $country_name == "Bolivia" ||
          $lang == "CL" || $country_name == "Chile" ||
          $lang == "CO" || $country_name == "Colombia" ||
          $lang == "CR" || $country_name == "Costa Rica" ||
          $lang == "CC" || $country_name == "Cocos (Keeling) Islands" ||
          $lang == "CU" || $country_name == "Cuba" ||
          $lang == "DO" || $country_name == "Dominican Republic" ||
          $lang == "EC" || $country_name == "Ecuador" ||
          $lang == "PY" || $country_name == "Paraguay" ||
          $lang == "PA" || $country_name == "Panama" ||
          $lang == "GT" || $country_name == "Guatemala" ||
          $lang == "PE" || $country_name == "Peru" ||
          $lang == "UY" || $country_name == "Uruguay" ||
          $lang == "NI" || $country_name == "Nicaragua" ||
          $lang == "SV" || $country_name == "El Salvador" ||
          $lang == "HN" || $country_name == "Honduras" ||
          $lang == "BZ" || $country_name == "Belize" ) $lang = "spa";
       else if (
          $lang == "AR" || $country_name == "Argentina" ) $lang = "ARS";

       else if ( /* Portuguese */
          $lang == "TL" || $country_name == "Timor-Leste" ||
          $lang == "ST" || $country_name == "Sao Tome and Principe" ||
          $lang == "MZ" || $country_name == "Mozambique" ||
          $lang == "PT" || $country_name == "Portugal" ||
          $lang == "VE" || $country_name == "Venezuela" ||
          $lang == "BR" || $country_name == "Brazil" ||
          $lang == "CV" || $country_name == "Cape Verde" ||
          $lang == "AO" || $country_name == "Angola" ||
          $lang == "GW" || $country_name == "Guinea-Bissau" ) $lang = "port";
       
       else if ( /* French */
          $lang == "FR" || $country_name == "France" ||
          $lang == "TF" || $country_name == "French Southern Territories" ||
          $lang == "CD" || $country_name == "Congo, The Democratic Republic of the" ||
          $lang == "MC" || $country_name == "Monaco" ||
          $lang == "CF" || $country_name == "Central African Republic" ||
          $lang == "CI" || $country_name == "Cote d'Ivoire" ||
          $lang == "ML" || $country_name == "Mali" ||
          $lang == "BE" || $country_name == "Belgium" ||
          $lang == "CG" || $country_name == "Congo" ||
          $lang == "GQ" || $country_name == "Equatorial Guinea" ||
          $lang == "KM" || $country_name == "Comoros" ||
          $lang == "GA" || $country_name == "Gabon" ||
          $lang == "CH" || $country_name == "Switzerland" ||
          $lang == "TG" || $country_name == "Togo" ||
          $lang == "BF" || $country_name == "Burkina Faso" ||
          $lang == "SN" || $country_name == "Senegal" ||
          $lang == "PF" || $country_name == "French Polynesia" ||
          $lang == "NC" || $country_name == "New Caledonia" ||
          $lang == "GP" || $country_name == "Guadeloupe" ||
          $lang == "DM" || $country_name == "Dominica" ||
          $lang == "PM" || $country_name == "Saint Pierre and Miquelon" ||
          $lang == "RE" || $country_name == "Reunion" || 
          $lang == "WF" || $country_name == "Wallis and Futuna" ||
          $lang == "MQ" || $country_name == "Martinique" ||
          $lang == "YT" || $country_name == "Mayotte" ||
          $lang == "BJ" || $country_name == "Benin" ||
          $lang == "BT" || $country_name == "Bhutan" ||
          $lang == "HT" || $country_name == "Haiti" ||
          $lang == "GF" || $country_name == "French Guiana" ||
          $lang == "GN" || $country_name == "Guinea" ) $lang = "EUR";

       else if ( /* Russian */
          $lang == "BY" || $country_name == "Belarus" ||
          $lang == "KG" || $country_name == "Kyrgyzstan" ||
          $lang == "KZ" || $country_name == "Kazakhstan" ||
          $lang == "RU" || $country_name == "Russian Federation" ) $lang = "RUB";

       else if ( /* Romanian */
          $lang == "RO" || $country_name == "Romania" ||
          $lang == "MD" || $country_name == "Moldova, Republic of" ) $lang = "RON";


       else if ( /* Japanese */
          $lang == "JP" || $country_name == "Japan" ) $lang = "JPY";
       
       else $lang = "EUR";
       
        return $lang;
   }
}

   // Uses Xavier finance
   // From/to can either be a number or a symbol like "EUR"
if ( ! function_exists('exchange'))
{   
   function exchange( $from, $to, $amount, $time ) {
   // Currency types
   
$currencies = array(     
     0=>"EUR",     1=>"USD",         2=>"JPY",     3=>"GBP",
     4=>"CYP",     5=>"CZK",         6=>"DKK",     7=>"EEK",
     8=>"HUF",     9=>"LTL",        10=>"MTL",    11=>"PLN",
    12=>"SEK",    13=>"SIT",        14=>"SKK",    15=>"CHF",
    16=>"ISK",    17=>"NOK",        18=>"BGN",    19=>"HRK",
    20=>"ROL",    21=>"RON",        22=>"RUB",    23=>"TRL",
    24=>"AUD",    25=>"CAD",        26=>"CNY",    27=>"HKD",
    28=>"IDR",    29=>"KRW",        30=>"MYR",    31=>"NZD",
    32=>"PHP",    33=>"SGD",        34=>"THB",    35=>"ZAR"   );  $max_currency=36;

$_currencies = array(     
     "EUR"=>0,     "USD"=>1,         "JPY"=>2,     "GBP"=>3,
     "CYP"=>4,     "CZK"=>5,         "DKK"=>6,     "EEK"=>7,
     "HUF"=>8,     "LTL"=>9,         "MTL"=>10,    "PLN"=>11,
     "SEK"=>12,    "SIT"=>13,        "SKK"=>14,    "CHF"=>15,
     "ISK"=>16,    "NOK"=>17,        "BGN"=>18,    "HRK"=>29,
     "ROL"=>20,    "RON"=>21,        "RUB"=>22,    "TRL"=>23,
     "AUD"=>24,    "CAD"=>25,        "CNY"=>26,    "HKD"=>27,
     "IDR"=>28,    "KRW"=>29,        "MYR"=>30,    "NZD"=>31,
     "PHP"=>32,    "SGD"=>33,        "THB"=>34,    "ZAR"=>35   );

   // Currency types
$country_currencies = array(1 => "EUR", //$c["Germany"],
		    2 => "EUR",  //$c["France"],
		    3 => "GBP",  //$c["United Kingdom"],
		    4 => "EUR",  //$c["Italy"],
		    5 => "EUR",  //$c["Luxenburg"],
		    6 => "EUR",  //$c["Netherlands"],
		    7 => "EUR",  //$c["Austria"],
		    8 => "RUB",  //$c["Russia"],
		    9 => "EUR",  //$c["Suisse"],
		   10 => "USD", //$c["United States"],		   
		   11 => "CAD", //$c["Canada"],
		   12 => "EUR", //$c["Spain"],
		   13 => "EUR", //$c["Portugal"],
		   14 => "EUR", //$c["India"],
		   15 => "USD",    //$c["Argentina"]  ARS
		   16 => "USD",    //$c["Brazil"]  BRL
	           17 => "EUR" );  //$c["Finland"]

      if ( is_numeric($from) ) $from = $country_currencies[$from];
      if ( is_numeric($to) )   $to   = $country_currencies[$to];
      $amount = doubleval($amount);
      
      if ( $from == $to ) return $amount;
     
     $t= explode(" ",$time);
     $date= explode("-",$t[0]);
     $day = $date[2];
     $month = $date[1]; 
     $year = $date[0];
     $result = @xml2array("http://api.finance.xaviermedia.com/api/" . $year . "/" . $month . "/" . $day . ".xml");
     while ( strpos($result,"404") ) {
	  $day=$day+10;
	  if ( $day > 30 ) { $month = $month+1; if ( $month > 12 ) { $month = "01"; $year++; } else $month = sprintf("%2d", (int) $month); $day=10; }
	  $result = @xml2array("http://api.finance.xaviermedia.com/api/' . $year . '/' . $month . '/' . $day . '.xml");
     }
     
     $rates["EUR"] = $result["xavierresponse"]["exchange_rates"]["fx"][0]["rate"];
     $rates["USD"] = $result["xavierresponse"]["exchange_rates"]["fx"][1]["rate"];
     
     for ( $x=2; $x<$max_currency; $x++ ) 
     $rates[$currencies[$x]] = $result["xavierresponse"]["exchange_rates"]["fx"][$x]["rate"];
     
     $calc = $amount * doubleval($rates[$from]) * doubleval($rates[$to]);
     
     //echo $amount . '*' . $rates[$from] . '*' . $rates[$to] . '=' . $calc . '<br>';
     return ( $calc );
   }
}



// Returns a combo with the financial selector
// name: for forms
// selected: the number of the selected country (1-17)
if ( ! function_exists('currency_combo'))
{   
function currency_combo( $name, $selected ) {
     
$countries = array(1 => "Germany",
		   2 => "France",
		   3 => "United Kingdom",
		   4 => "Italy",
		   5 => "Luxenburg",
		   6 => "Netherlands",
		   7 => "Austria",
		   8 => "Russia",
		   9 => "Suisse",
		   10 => "United States",		   
		   11 => "Canada",
		   12 => "Spain",
		   13 => "Portugal",
		   14 => "India",
		   15 => "Argentina",
		   16 => "Brazil",
		   17 => "Finland" );

   $output = '<select name="' . $name . '" STYLE="width: 100px"><option value="0">----------------------</option>';
   
   foreach($countries as $l => $x) { $output .= "<option value=\"" . $l . "\"";
	if ($selected == $l) $output .= " selected=\"selected\"";
	$output .= ">" . $x . "</option>\n";   }
   $output .= '</select>';
   
   return $output;
}
}

if ( ! function_exists('currency_to_code'))
{   
function currency_to_code( $currency ) {
     $country_currencies = array(1 => "EUR", //$c["Germany"],
		    2 => "EUR",  //$c["France"],
		    3 => "GBP",  //$c["United Kingdom"],
		    4 => "EUR",  //$c["Italy"],
		    5 => "EUR",  //$c["Luxenburg"],
		    6 => "EUR",  //$c["Netherlands"],
		    7 => "EUR",  //$c["Austria"],
		    8 => "RUB",  //$c["Russia"],
		    9 => "EUR",  //$c["Suisse"],
		   10 => "USD", //$c["United States"],		   
		   11 => "CAD", //$c["Canada"],
		   12 => "EUR", //$c["Spain"],
		   13 => "EUR", //$c["Portugal"],
		   14 => "EUR", //$c["India"],
		   15 => "USD",    //$c["Argentina"]  ARS
		   16 => "USD",    //$c["Brazil"]  BRL
	           17 => "EUR" );  //$c["Finland"]
     return $country_currencies[$currency];
}
}



if ( ! function_exists('getgeo'))
{   
// Maxmind Stuff
function getgeo( $ref_table, $ref_id, $all_or_newest=FALSE, $table="maxmind" ) { $Herb =& get_instance();
	if ( $all_or_newest ) return find_like("maxmind","ref_table",$ref_table," AND `ref_id`='" . $ref_id . "' ORDER BY `recorded` DESC" ); 
	$newest =  find_like($table,"ref_table",$ref_table," AND `ref_id`='" . $ref_id . "' ORDER BY `recorded` DESC LIMIT 1" );
	if ( !is_null($newest) ) return array_pop($newest);
	return NULL;
}
}

if ( ! function_exists('putgeo'))
{   
// Maxmind Stuff
function putgeo( $ref_table, $ref_id, $id=NULL, $table="maxmind" ) {  $Herb =& get_instance();
	
        // uncomment for Shared Memory support
        // geoip_load_shared_mem("/usr/local/share/GeoIP/GeoIPCity.dat");
        // $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat",GEOIP_SHARED_MEMORY);

        $gi = geoip_open("maxmind/GeoLiteCity.dat",GEOIP_STANDARD);

        $record = geoip_record_by_addr($gi,$_SERVER['REMOTE_ADDR']);
	
	if ( $record == NULL ) { return NULL; }

        if ( is_null($id) ) {
		$id = 
		multiinsert( $table,
			        "ref_id",      $ref_id,
				"ref_table",   $ref_table,
			        "geoip",       $_SERVER['REMOTE_ADDR'],
			        "geocountry",  $record->country_code,
			        "geocountry3", $record->country_code3,
			        "geoname",     $record->country_name,
			        "georegion",   $record->region,
			        "geocity",     $record->city,
				"geozip",      $record->postal_code,
			        "geolat",      $record->latitude,
				"geolong",     $record->longitude,
			        "geodma",      $record->dma_code,
			        "geoarea",     $record->area_code,
				"geotype",     GEOIP_STANDARD,
			        "geolang",     $record->country_code
			       );
	} else
        if ( not_null($table) && not_null($id) ) {
          set($table,$id,"geoip",         $_SERVER['REMOTE_ADDR']);
          set($table,$id,"geocountry",    $record->country_code);
          set($table,$id,"geocountry3",   $record->country_code3);
          set($table,$id,"geoname",       $record->country_name);
          set($table,$id,"georegion",     $record->region );
          //set($table,$id,"georegionname", $GEOIP_REGION_NAME[$record->country_code][$record->region] );
          set($table,$id,"geocity",       $record->city);
          set($table,$id,"geozip",        $record->postal_code);            
          set($table,$id,"geolat",        $record->latitude);
          set($table,$id,"geolong",       $record->longitude);            
          set($table,$id,"geodma",        $record->dma_code);            
          set($table,$id,"geoarea",       $record->area_code);            
          set($table,$id,"geotype",       GEOIP_STANDARD);
            
          set($table,$id,"geolang",   $record->country_code);
	  
	  set($table,$id,"ref_table", $ref_table);
	  set($table,$id,"ref_id", $ref_id);
        }
        $country_name = $record->country_name;
        if ( isset($GEOIP_REGION_NAME) )
	$region_name =  $GEOIP_REGION_NAME[$record->country_code][$record->region];
        $region_code =  $record->region;
//        $lang = $record->country_code;
        geoip_close($gi);
	return find($table,"id",$id);
}
}


if ( ! function_exists('maxmind'))
{   
// Maxmind Stuff
function maxmind( ) {
	
        // uncomment for Shared Memory support
        // geoip_load_shared_mem("/usr/local/share/GeoIP/GeoIPCity.dat");
        // $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat",GEOIP_SHARED_MEMORY);

        $gi = geoip_open("maxmind/GeoLiteCity.dat",GEOIP_STANDARD);

        $record = geoip_record_by_addr($gi,$_SERVER['REMOTE_ADDR']);
	
	if ( $record == NULL ) { return NULL; }

        if ( isset($GEOIP_REGION_NAME) )
	$record->region_name =  $GEOIP_REGION_NAME[$record->country_code][$record->region];
        $record->region_code =  $record->region;
        geoip_close($gi);
	return $record;
}
}


// ------------------------------------------------------------------------
/* End of file country_helper.php */
/* Location: ./system/helpers/country_helper.php */