<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright	Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Text Helpers
 *
 * @package		HerbIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/user_guide/helpers/text_helper.html
 */

// ------------------------------------------------------------------------

   // fine print   
if ( !function_exists('fine') ) {
   function fine( $text ) {
        return '<span style="font-size:x-small">' . $text . '</span>';
   }
}

if ( !function_exists('nicetime') ) {
 function nicetime($date)
 {
    if(empty($date)) return "No date provided";
   
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    $now             = time();
    $unix_date       = strtotime($date);
   
       // check validity of date
    if(empty($unix_date)) return "Bad date";

    // is it future date or past date
    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense         = "ago";
       
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++)
     $difference /= $lengths[$j];

    $difference = round($difference);

    if($difference != 1) $periods[$j].= "s";
    return "$difference $periods[$j] {$tense}";
 }
}

if ( ! function_exists('lorem_ipsum'))
{  
    function lorem_ipsum( $nbr=1, $elem='div', $sent=5 ) {
             $sentences = array(1 =>
   'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
   'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
   'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
   'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
             );

             $count = 1;
             $text = array();
             for($i = 1; $i <= $nbr; $i++)
             {
                $text[$i] = "<$elem>";
                for($j = 1; $j <= $sent; $j++)
                    {
                    $text[$i] .= $sentences[$count].' ';
                    if(++$count > 4) { $count = 1; }
                    }
                $text[$i] = trim($text[$i])."</$elem>";
                $text[$i] = wordwrap($text[$i]);
             }             
             return (implode("\n", $text));             
    }
}

if ( ! function_exists('censorship'))
{
 function censorship( $text ) {
   $words =
   array( 0=>'fuck',                       1=>'shit',
          2=>'cunt',                       3=>'asshole',
          4=>'bitch',                      5=>'cock',
                     
          5=>'FUCK',                        6=>'fUcK',
          7=>'FuCK',                       11=>'Fuck',
          12=>'FucK',                      13=>'FUck',
                      
          8=>'CUNT',
          9=>'cUnT',
          10=>'Cunt',
                       
          14=>'COCK' );
   foreach ( $words as $word ) $text = str_replace( $word, ":(", $text );
   return $text;
 }
}


/**
 * Word Limiter
 *
 * Limits a string to X number of words.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */	
if ( ! function_exists('word_limiter'))
{
	function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
		{
			return $str;
		}
	
		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);
			
		if (strlen($str) == strlen($matches[0]))
		{
			$end_char = '';
		}
		
		return rtrim($matches[0]).$end_char;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Character Limiter
 *
 * Limits the string based on the character count.  Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @access	public
 * @param	string
 * @param	integer
 * @param	string	the end character. Usually an ellipsis
 * @return	string
 */	
if ( ! function_exists('character_limiter'))
{
	function character_limiter($str, $n = 500, $end_char = '&#8230;')
	{
		if (strlen($str) < $n)
		{
			return $str;
		}
		
		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (strlen($str) <= $n)
		{
			return $str;
		}

		$out = "";
		foreach (explode(' ', trim($str)) as $val)
		{
			$out .= $val.' ';
			
			if (strlen($out) >= $n)
			{
				$out = trim($out);
				return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
			}		
		}
	}
}
	
// ------------------------------------------------------------------------

/**
 * High ASCII to Entities
 *
 * Converts High ascii text and MS Word special characters to character entities
 *
 * @access	public
 * @param	string
 * @return	string
 */	
if ( ! function_exists('ascii_to_entities'))
{
	function ascii_to_entities($str)
	{
	   $count	= 1;
	   $out	= '';
	   $temp	= array();
	
	   for ($i = 0, $s = strlen($str); $i < $s; $i++)
	   {
		   $ordinal = ord($str[$i]);
	
		   if ($ordinal < 128)
		   {
				/*
					If the $temp array has a value but we have moved on, then it seems only
					fair that we output that entity and restart $temp before continuing. -Paul
				*/
				if (count($temp) == 1)
				{
					$out  .= '&#'.array_shift($temp).';';
					$count = 1;
				}
				$out .= $str[$i];
		   }
		   else
		   {
			   if (count($temp) == 0)
			   {
				   $count = ($ordinal < 224) ? 2 : 3;
			   }
		
			   $temp[] = $ordinal;
		
			   if (count($temp) == $count)
			   {
				   $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);

				   $out .= '&#'.$number.';';
				   $count = 1;
				   $temp = array();
			   }
		   }
	   }

	   return $out;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Entities to ASCII
 *
 * Converts character entities back to ASCII
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	string
 */	
if ( ! function_exists('entities_to_ascii'))
{
	function entities_to_ascii($str, $all = TRUE)
	{
	   if (preg_match_all('/\&#(\d+)\;/', $str, $matches))
	   {
		   for ($i = 0, $s = count($matches['0']); $i < $s; $i++)
		   {				
			   $digits = $matches['1'][$i];

			   $out = '';

			   if ($digits < 128)
			   {
				   $out .= chr($digits);
		
			   }
			   elseif ($digits < 2048)
			   {
				   $out .= chr(192 + (($digits - ($digits % 64)) / 64));
				   $out .= chr(128 + ($digits % 64));
			   }
			   else
			   {
				   $out .= chr(224 + (($digits - ($digits % 4096)) / 4096));
				   $out .= chr(128 + ((($digits % 4096) - ($digits % 64)) / 64));
				   $out .= chr(128 + ($digits % 64));
			   }

			   $str = str_replace($matches['0'][$i], $out, $str);				
		   }
	   }

	   if ($all)
	   {
		   $str = str_replace(array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
							  array("&","<",">","\"", "'", "-"),
							  $str);
	   }

	   return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Plural
 *
 * Takes a singular word and makes it plural
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	str
 */	
if ( ! function_exists('plural'))
{	
	function plural($str, $force = FALSE)
	{
		$str = strtolower(trim($str));
		$end = substr($str, -1);

		if (substr($str,-2) == 'us' && strlen($str) > 2) {
			// Fungus alumnus
			$str = substr($str, 0, -2).'i';
		}
		if ($end == 'y')
		{
			// Y preceded by vowel => regular plural
			$vowels = array('a', 'e', 'i', 'o', 'u');
			$str = in_array(substr($str, -2, 1), $vowels) ? $str.'s' : substr($str, 0, -1).'ies';
		}
		elseif ($end == 's')
		{
			if ($force == TRUE)
			{
				$str .= 'es';
			}
		}
		else
		{
			$str .= 's';
		}

		return $str;
	}
}

// --------------------------------------------------------------------

/**
 * Camelize
 *
 * Takes multiple words separated by spaces or underscores and camelizes them
 *
 * @access	public
 * @param	string
 * @return	str
 */	
if ( ! function_exists('camelize'))
{	
	function camelize($str)
	{		
		$str = 'x'.strtolower(trim($str));
		$str = ucwords(preg_replace('/[\s_]+/', ' ', $str));
		return substr(str_replace(' ', '', $str), 1);
	}
}

// --------------------------------------------------------------------

/**
 * Underscore
 *
 * Takes multiple words separated by spaces and underscores them
 *
 * @access	public
 * @param	string
 * @return	str
 */	
if ( ! function_exists('underscore'))
{
	function underscore($str)
	{
		return preg_replace('/[\s]+/', '_', strtolower(trim($str)));
	}
}


/**
 * Word Censoring Function
 *
 * Supply a string and an array of disallowed words and any
 * matched words will be converted to #### or to the replacement
 * word you've submitted.
 *
 * @access	public
 * @param	string	the text string
 * @param	string	the array of censoered words
 * @param	string	the optional replacement value
 * @return	string
 */	
if ( ! function_exists('word_censor'))
{
	function word_censor($str, $censored, $replacement = '')
	{
		if ( ! is_array($censored))
		{
			return $str;
		}
        
        $str = ' '.$str.' ';

		// \w, \b and a few others do not match on a unicode character
		// set for performance reasons. As a result words like über
		// will not match on a word boundary. Instead, we'll assume that
		// a bad word will be bookended by any of these characters.
		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach ($censored as $badword)
		{
			if ($replacement != '')
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
			}
			else
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

        return trim($str);
	}
}
	
// ------------------------------------------------------------------------

/**
 * Code Highlighter
 *
 * Colorizes code strings
 *
 * @access	public
 * @param	string	the text string
 * @return	string
 */	
if ( ! function_exists('highlight_code'))
{
	function highlight_code($str)
	{		
		// The highlight string function encodes and highlights
		// brackets so we need them to start raw
		$str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);
	
		// Replace any existing PHP tags to temporary markers so they don't accidentally
		// break the string out of PHP, and thus, thwart the highlighting.
	
		$str = str_replace(array('<?', '?>', '<%', '%>', '\\', '</script>'), 
							array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), $str);

		// The highlight_string function requires that the text be surrounded
		// by PHP tags, which we will remove later
		$str = '<?php '.$str.' ?>'; // <?

		// All the magic happens here, baby!	
		$str = highlight_string($str, TRUE);

		// Prior to PHP 5, the highligh function used icky <font> tags
		// so we'll replace them with <span> tags.

		if (abs(PHP_VERSION) < 5)
		{
			$str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
			$str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
		}
		
		// Remove our artificially added PHP, and the syntax highlighting that came with it
		$str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
		$str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is', "$1</span>\n</span>\n</code>", $str);
		$str = preg_replace('/<span style="color: #[A-Z0-9]+"\><\/span>/i', '', $str);
			
		// Replace our markers back to PHP tags.
		$str = str_replace(array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'),
							array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'), $str);
										
		return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Phrase Highlighter
 *
 * Highlights a phrase within a text string
 *
 * @access	public
 * @param	string	the text string
 * @param	string	the phrase you'd like to highlight
 * @param	string	the openging tag to precede the phrase with
 * @param	string	the closing tag to end the phrase with
 * @return	string
 */	
if ( ! function_exists('highlight_phrase'))
{
	function highlight_phrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
	{
		if ($str == '')
		{
			return '';
		}
	
		if ($phrase != '')
		{
			return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
		}

		return $str;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Word Wrap
 *
 * Wraps text at the specified character.  Maintains the integrity of words.
 * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
 * will URLs.
 *
 * @access	public
 * @param	string	the text string
 * @param	integer	the number of characters to wrap at
 * @return	string
 */	
if ( ! function_exists('word_wrap'))
{
	function word_wrap($str, $charlim = '76')
	{
		// Se the character limit
		if ( ! is_numeric($charlim))
			$charlim = 76;
	
		// Reduce multiple spaces
		$str = preg_replace("| +|", " ", $str);
	
		// Standardize newlines
		if (strpos($str, "\r") !== FALSE)
		{
			$str = str_replace(array("\r\n", "\r"), "\n", $str);			
		}
	
		// If the current word is surrounded by {unwrap} tags we'll 
		// strip the entire chunk and replace it with a marker.
		$unwrap = array();
		if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches))
		{
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$unwrap[] = $matches['1'][$i];				
				$str = str_replace($matches['1'][$i], "{{unwrapped".$i."}}", $str);
			}
		}
	
		// Use PHP's native function to do the initial wordwrap.  
		// We set the cut flag to FALSE so that any individual words that are 
		// too long get left alone.  In the next step we'll deal with them.
		$str = wordwrap($str, $charlim, "\n", FALSE);
	
		// Split the string into individual lines of text and cycle through them
		$output = "";
		foreach (explode("\n", $str) as $line) 
		{
			// Is the line within the allowed character count?
			// If so we'll join it to the output and continue
			if (strlen($line) <= $charlim)
			{
				$output .= $line."\n";			
				continue;
			}
			
			$temp = '';
			while((strlen($line)) > $charlim) 
			{
				// If the over-length word is a URL we won't wrap it
				if (preg_match("!\[url.+\]|://|wwww.!", $line))
				{
					break;
				}

				// Trim the word down
				$temp .= substr($line, 0, $charlim-1);
				$line = substr($line, $charlim-1);
			}
		
			// If $temp contains data it means we had to split up an over-length 
			// word into smaller chunks so we'll add it back to our current line
			if ($temp != '')
			{
				$output .= $temp . "\n" . $line; 
			}
			else
			{
				$output .= $line;
			}

			$output .= "\n";
		}

		// Put our markers back
		if (count($unwrap) > 0)
		{	
			foreach ($unwrap as $key => $val)
			{
				$output = str_replace("{{unwrapped".$key."}}", $val, $output);
			}
		}

		// Remove the unwrap tags
		$output = str_replace(array('{unwrap}', '{/unwrap}'), '', $output);

		return $output;	
	}
}


/* End of file text_helper.php */
/* Location: ./system/helpers/text_helper.php */