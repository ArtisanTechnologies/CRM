<?php
/**
 * Created by PhpStorm.
 * User: dawoudio
 * Date: 11/27/2016
 * Time: 9:33 AM.
 */

namespace ChurchCRM\dto;

class SystemURLs
{
    private static $rootPath;
    private static $urls;
    private static $documentRoot;
    private static $CSPNonce;
    private static $supportURL = "https://github.com/ChurchCRM/CRM/wiki";

    public static function init($rootPath, $urls, $documentRoot)
    {
        // Avoid consecutive slashes when $sRootPath = '/'
        if ($rootPath == '/') {
            $rootPath = '';
        }
        self::$rootPath = $rootPath;
        self::$urls = $urls;
        self::$documentRoot = $documentRoot;
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 16; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        self::$CSPNonce = base64_encode( implode($pass));
    }

    public static function getRootPath()
    {
        if (self::isValidRootPath()) {
            return self::$rootPath;
        }
        throw new \Exception("Please check the value for '\$sRootPath' in <b>`Include\\Config.php`</b>, the following is not valid [".self::$rootPath.']');
    }

    public static function getDocumentRoot()
    {
        return self::$documentRoot;
    }
    
    public static function getImagesRoot()
    {
      return self::$documentRoot."/Images";
    }

    public static function getURLs()
    {
        return self::$urls;
    }

    public static function getSupportURL()
    {
        return self::$supportURL;
    }

  public static function getURL($index = 0)
    {
        return self::$urls[$index];
    }

    private static function isValidRootPath()
    {
        //if (stripos(self::$rootPath, "http") !== true ) {
        //    return false;
        //}
        return true;
    }

    // check if bLockURL is set and if so if the current page is accessed via an allowed URL
    // including the desired protocol, hostname, and path.
    // An array of authorized URL's is specified in Config.php in the $URL array
    public static function checkAllowedURL($bLockURL, $URL)
    {
        if (isset($bLockURL) && ($bLockURL === true)) {
            // get the URL of this page
            $currentURL = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            // chop off the query string
            $currentURL = explode('?', $currentURL)[0];

            // check if this matches any one of teh whitelisted login URLS
            $validURL = false;
            foreach ($URL as $value) {
                $base = substr($value, 0, -strlen('/'));
                if (strpos($currentURL, $value) === 0) {
                    $validURL = true;
                    break;
                }
            }

            // jump to the first whitelisted url (TODO: maybe pick a ranodm URL?)
            if (!$validURL) {
                header('Location: '.$URL[0]);
                exit;
            }
        }
    }
    
    public static function getCSPNonce()
    {
      return self::$CSPNonce;
    }
}
