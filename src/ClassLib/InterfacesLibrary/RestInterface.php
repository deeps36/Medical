<?php
namespace FES\GEET\ClassLib\InterfacesLibrary;

interface RestInterface
{
	// To handle all put request
	function put($params);
	// To handle all get request
	function get();
	// To handle all post request
	function post($params, $mobile);
	// To handle all delete request
	function delete();
}