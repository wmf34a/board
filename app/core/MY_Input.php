<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Input extends CI_Input {

	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the GET array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @param	string
	* @return	string
	*/
	function get($index = NULL, $xss_clean = FALSE, $default = "")
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_GET))
		{
			$get = array();

			// loop through the full _GET array
			foreach (array_keys($_GET) as $key)
			{
				$get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
			}
			return $get;
		}

		$var = $this->_fetch_from_array($_GET, $index, $xss_clean);

		if(!$var AND $default)
		{
			$_GET[$index] = $default;
			return $_GET[$index];
		}

		return $var;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the POST array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function post($index = NULL, $xss_clean = FALSE, $default = "")
	{
		// hooking for IE XdomainRequest
		if (sizeof($_POST) == 0)
		{
			$body = @file_get_contents('php://input');
			if ($body) {
				$spld_array1 = explode('&', $body);
				foreach ($spld_array1 as $v) {
					list($key, $value) = explode("=", $v);
					$_POST[$key] = urldecode($value);
				}
			}
		}

		// Check if a field has been provided
		if ($index === NULL AND ! empty($_POST))
		{
			$post = array();

			// Loop through the full _POST array and return it
			foreach (array_keys($_POST) as $key)
			{
				$post[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
			}
			return $post;
		}

		$var = $this->_fetch_from_array($_POST, $index, $xss_clean);

		if(!$var AND $default)
		{
			$_POST[$index] = $default;
			return $_POST[$index];
		}

		return $var;
	}


	// --------------------------------------------------------------------

	/**
	* Fetch an item from either the GET array or the POST
	*
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	function get_post($index = '', $xss_clean = FALSE, $default = "")
	{
		if ( ! isset($_POST[$index]) AND ! isset($_GET[$index]) AND $default )
		{
			$_GET[$index] = $default;
			$_POST[$index] = $default;
			return $default;
		}
		else
		{
			if ( ! isset($_POST[$index]) )
			{
				return $this->get($index, $xss_clean);
			}
			else
			{
				return $this->post($index, $xss_clean);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from either the REQUEST
	*
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	function request($index = '', $xss_clean = FALSE, $default = "")
	{
		// Check if a field has been provided
		if ($index == '' AND ! empty($_REQUEST))
		{
			$request = array();
			// Loop through the full _POST array and return it
			foreach (array_keys($_REQUEST) as $key)
			{
				$request[$key] = $this->_fetch_from_array($_REQUEST, $key, $xss_clean);
			}
			return $request;
		}

		$var = $this->_fetch_from_array($_REQUEST, $index, $xss_clean);

		if(!$var AND $default)
		{
			$_REQUEST[$index] = $default;
			return $_REQUEST[$index];
		}
		return $var;
	}

	function escape($index = '', $xss_clean = FALSE, $default = "")
	{
		// Check if a field has been provided
		if ($index == '' AND ! empty($_REQUEST))
		{
			$request = array();
			// Loop through the full _POST array and return it
			foreach (array_keys($_REQUEST) as $key)
			{
				$request[$key] = $this->_fetch_from_array($_REQUEST, $key, $xss_clean);
			}
			return $this->escape_str($request);
		}

		$var = $this->_fetch_from_array($_REQUEST, $index, $xss_clean);

		if(!$var AND $default)
		{
			$_REQUEST[$index] = $default;
			return $this->escape_str($_REQUEST[$index]);
		}
		return $this->escape_str($var);
	}

	function escape_str($str, $like = FALSE)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = $this->escape_str($val, $like);
			}
			return $str;
		}
		if (function_exists('mysql_real_escape_string'))
		{
			$str = mysql_real_escape_string($str);
		}
		elseif (function_exists('mysql_escape_string'))
		{
			$str = mysql_escape_string($str);
		}
		else
		{
			$str = addslashes($str);
		}
		// escape LIKE condition wildcards
		if ($like === TRUE)
		{
			$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
		}
		return $str;
	}
}

/* End of file Input.php */
/* Location: ./app/core/MY_Input.php */

