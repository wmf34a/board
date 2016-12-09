<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 문자열 비교 후 일치시 원하는 문자열 반환
 *
 * @param	String	$base_value			기준 문자열
 * @param	String	$compare_value		비교 대상 문자열
 * @param	String	$equal_value		기준과 비교 대상이 같을 경우 출력할 문자열
 * @param	String	$not_equal_value	기준과 비교 대상이 다를 경우 출력할 문자열
 */
if ( ! function_exists('equal2print'))
{
	function equal2print($base_value = '', $compare_value = '', $equal_value = '', $not_equal_value = '', $alter_value = '')
	{
		$base_str = (gettype($base_value) === 'string') ? $base_value : (string)$base_value;
		$compare_str = (gettype($compare_value) === 'string') ? $compare_value : (string)$compare_value;
		$alter_str = (gettype($alter_value) === 'string') ? $alter_value : (string)$alter_value;

		if ($base_str === $compare_str)
		{
			return $equal_value;
		}
		else
		{
			if ($alter_str && ($base_str === $alter_str))
			{
				return $equal_value;
			}

			return $not_equal_value;
		}
	}
}

/* End of file MY_form_helper.php */
/* Location: ./app/helpers/My_form_helper.php */
