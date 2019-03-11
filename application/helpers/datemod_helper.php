<?php

if ( ! function_exists('todays_date'))
{
	function todays_date()
	{
		$timezone = "Asia/Manila";
		if(function_exists('date_default_timezone_set')){ date_default_timezone_set($timezone);}

		$todays_date = date("Y-m-d");

		return $todays_date;

	}
}

if ( ! function_exists('todays_time'))
{
	function todays_time()
	{
		$timezone = "Asia/Manila";
		if(function_exists('date_default_timezone_set')){ date_default_timezone_set($timezone);}

		$todays_time = date("G:i:s");

		return $todays_time;
	}
}

if ( ! function_exists('todays_time_24hours'))
{
	function todays_time_24hours()
	{
		$timezone = "Asia/Manila";
		if(function_exists('date_default_timezone_set')){ date_default_timezone_set($timezone);}

		$todays_time = date("H:i:s");

		return $todays_time;
	}
}

if ( ! function_exists('_dateFormat'))
{
	function _dateFormat($date)
	{
		$date = date_create($date);
		return date_format($date, 'F d, Y');	
	}
}

if ( ! function_exists('_getTimestamp'))
{
	function _getTimestamp()
	{
		$date = new DateTime();
		$currentTime = $date->getTimestamp();
		return $currentTime;
	}
}

if ( ! function_exists('_dateFormatoSql'))
{
	function _dateFormatoSql($date_to_format){
		$date_to_format = date_create($date_to_format);
		return date_format($date_to_format, 'Y-m-d');
	}
}

