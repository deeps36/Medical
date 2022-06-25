<?php
namespace FES\GEET\ClassLib\InterfacesLibrary;

interface ActionInterface
{
	function register($action);
	function unregister($action);
	function listall();
}