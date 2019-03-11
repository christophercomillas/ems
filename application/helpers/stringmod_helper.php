<?php 

if ( ! function_exists('alpha_dash_space'))
{
	function alpha_dash_space($str)
	{
		return ( ! preg_match("/^[-_ \p{L}\p{N}]+$/iu", $str)) ? FALSE : TRUE;
	}
}

if ( ! function_exists('sample'))
{
	function sample()
	{
		return 'yeah';
	}
}

if (! function_exists('zeroes'))
{
	function zeroes($num,$zero)
	{
		return sprintf("%0".$zero."d", $num);
	}
}