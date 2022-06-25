<?php
/**
 * Configuration file for CSRF Protector
 * Necessary configurations are (library would throw exception otherwise)
 * ---- logDirectory
 * ---- failedAuthAction
 * ---- jsUrl
 * ---- tokenLength
 */

return array(
	"CSRFP_TOKEN" => "gtoken",
	"logDirectory" => "../log",
	"failedAuthAction" => array(
		"GET" => 0,
		"POST" => 0),
	"errorRedirectionPage" => "",
	"customErrorMessage" => "",
	"jsUrl" => $GLOBALS['base_url']."js/vendor/csrfprotector.js",
	"tokenLength" => 30,
	"cookieConfig" => array(
		"path" => '/geet',
		"domain" => $_SERVER['HTTP_HOST'],
		"secure" => true,
		"expire" => '',
	),
	"disabledJavascriptMessage" => "",
	 "verifyGetFor" => array("*://*/User/*","*://*/Scheme/*","*://*/scheme/*","*://*/DataEntry/*","*://*/Reports/*")
);