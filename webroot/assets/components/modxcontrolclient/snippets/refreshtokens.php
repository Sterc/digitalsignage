<?php

/**
 * UpgradeMODXWidget snippet for UpgradeMODX extra
 *
 * Copyright 2015 by Bob Ray <http://bobsguides.com>
 * Created on 08-16-2015
 *
 * UpgradeMODX is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * UpgradeMODX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * UpgradeMODX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package upgrademodx
 */


/*
 * This package was inspired by the work of a number of people and I have borrowed some of their code.
 * Dmytro Lukianenko (dmi3yy) is the original author of the MODX install script. Susan Sottwell,
 * Sharapov, Bumkaka, Inreti, Zaigham Rana, frischnetz, and AgelxNash, also contributed and I'd
 * like to thank all of them for laying the groundwork.
  */

error_reporting(0);
ini_set('display_errors', 0);
set_time_limit(0);
ini_set('max_execution_time', 0);
header('Content-Type: text/html; charset=utf-8');

if (extension_loaded('xdebug')) {
    ini_set('xdebug.max_nesting_level', 100000);
}


class MODXInstaller
{
    static public function downloadFile($url, $path, $method)
    {
        $newfname = $path;
        if (file_exists($path)) {
            unlink($path);
        }
        $newf = null;
        $file = null;
        if ($method == 'fopen') {
            try {
                $file = fopen($url, "rb");
                if ($file) {
                    $newf = fopen($newfname, "wb");
                    if ($newf) {
                        set_time_limit(0);
                        while (!feof($file)) {
                            fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                        }
                    } else {
                        return ('Could not open ' . $newf . ' for writing');
                    }
                } else {
                    return ('fopen failed to open ' . $url);
                }
            } catch (Exception $e) {
                return 'ERROR:Download ' . $e->getMessage();
            }
            if ($file) {
                fclose($file);
            }
            if ($newf) {
                fclose($newf);
            }

        } elseif ($method == 'curl') {
            $newf = fopen($path, "wb");
            if ($newf) {
                set_time_limit(0);
                $ch = curl_init(str_replace(" ", "%20", $url));
                curl_setopt($ch, CURLOPT_TIMEOUT, 180);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0)');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FILE, $newf);
                if (filter_var(ini_get('open_basedir'),
                               FILTER_VALIDATE_BOOLEAN) === false && filter_var(ini_get('safe_mode'),
                                                                                FILTER_VALIDATE_BOOLEAN) === false
                ) {
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                } else {
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                    $rch = curl_copy_handle($ch);
                    $newurl = $url;
                    curl_setopt($rch, CURLOPT_URL, $newurl);
                    $header = curl_exec($rch);
                    if (curl_errno($rch)) {
                        $code = 0;
                    } else {
                        $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
                        if ($code == 301 || $code == 302) {
                            preg_match('/Location:(.*?)\n/i', $header, $matches);
                            $newurl = trim(array_pop($matches));
                        }
                        curl_close($rch);
                        curl_setopt($ch, CURLOPT_URL, $newurl);
                    }
                }
                $retVal = curl_exec($ch);
                if ($retVal === false) {
                    return ('cUrl download of modx.zip failed ');
                }
                curl_close($ch);
            } else {
                return ('Cannot open ' . $path . ' for writing');
            }
        } else {
            return 'Invalid method in call to downloadFile()';
        }

        return true;
    }

    static public function removeFolder($path, $removeRoot = true)
    {
        $dir = realpath($path);
        if (!is_dir($dir)) {
            return;
        }
        $it = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        if ($removeRoot) {
            rmdir($dir);
        }
    }

    static public function copyFolder($src, $dest)
    {

        $path = realpath($src);
        $dest = realpath($dest);
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path),
                                                 RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $name => $object) {
            $startsAt = substr(dirname($name), strlen($path));
            self::mmkDir($dest . $startsAt, true);
            if ($object->isDir()) {
                self::mmkDir($dest . substr($name, strlen($path)));
            }

            if (is_writable($dest . $startsAt) and $object->isFile()) {
                copy((string)$name, $dest . $startsAt . DIRECTORY_SEPARATOR . basename($name));
            }
        }
    }

    static public function normalize($paths)
    {
        if (is_array($paths)) {
            foreach ($paths as $k => $v) {
                $v = str_replace('\\', '/', rtrim($v, '/\\'));
                $paths[$k] = $v;
            }
        } else {
            $paths = str_replace('\\', '/', rtrim($paths, '/\\'));
        }
        return $paths;
    }

    static public function getDirectories($directories = array())
    {
        if (empty($directories)) {
            $directories = array(
                'setup' => MODX_BASE_PATH . 'setup',
                'core' => MODX_CORE_PATH,
                'manager' => MODX_MANAGER_PATH,
                'connectors' => MODX_CONNECTORS_PATH,
            );
        }
        /* See if we need to do processors path */
        $modxProcessorsPath = MODXInstaller::normalize(MODX_PROCESSORS_PATH);
        if (strpos(MODX_PROCESSORS_PATH, 'core/model/modx/processors') === false) {
            $directories['core/model/modx/processors'] = $modxProcessorsPath;
        }

        /* Normalize directory paths */
        $directories = MODXInstaller::normalize($directories);

        return $directories;

    }

    static public function copyFiles($sourceDir, $directories)
    {

        /* Normalize directory paths */
        MODXInstaller::normalize($directories);
        MODXInstaller::normalize($sourceDir);

        /* Copy directories */
        foreach ($directories as $source => $target) {
            MODXInstaller::mmkDir($target);
            set_time_limit(0);
            MODXInstaller::copyFolder($sourceDir . '/' . $source, $target);
        }

    }

    static public function mmkDir($folder, $perm = 0755)
    {
        if (!is_dir($folder)) {
            $oldumask = umask(0);
            mkdir($folder, $perm, true);
            umask($oldumask);
        }
    }

    static public function unZip($corePath, $source, $destination, $forcePclZip = false)
    {
        $status = true;
        if ((!$forcePclZip) && class_exists('ZipArchive', false)) {
            $zip = new ZipArchive;
            if ($zip instanceof ZipArchive) {
                $open = $zip->open($source, ZIPARCHIVE::CHECKCONS);

                if ($open == true) {
                    $result = $zip->extractTo($destination);
                    if ($result === false) {
                        /* Yes, this is fucking nuts, but it's necessary on some platforms */
                        $result = $zip->extractTo($destination);
                        if ($result === false) {
                            $msg = $zip->getStatusString();
                            MODXInstaller::quit($msg);
                        }
                    }
                    $zip->close();
                } else {
                    $status = 'Could not open ZipArchive ' . $source . ' ' . $zip->getStatusString();
                }

            } else {
                $status = 'Could not instantiate ZipArchive';
            }
        } else {
            $zipClass = $corePath . 'xpdo/compression/pclzip.lib.php';
            if (file_exists($zipClass)) {
                include $corePath . 'xpdo/compression/pclzip.lib.php';
                $archive = new PclZip($source);
                if ($archive->extract(PCLZIP_OPT_PATH, $destination) == 0) {
                    $status = 'Extraction with PclZip failed - Error : ' . $archive->errorInfo(true);
                }
            } else {
                $status = 'Neither ZipArchive, nor PclZip were available to unzip the archive';
            }
        }
        return $status;
    }


    /**
     * Get name of downloaded MODX directory (e.g., modx-3.4.0-pl).
     *
     * @param $tempDir string - temporary download directory
     * @return string - Name of directory
     */
    public static function getModxDir($tempDir)
    {
        $handle = opendir($tempDir);
        if ($handle !== false) {
            while (false !== ($name = readdir($handle))) {
                if ($name != "." && $name != "..") {
                    $dir = $name;
                }
            }
            closedir($handle);
        } else {
            MODXInstaller::quit('Unable to read directory contents or directory is empty: ' . dirname(__FILE__) . '/temp');
        }

        if (empty($dir)) {
            MODXInstaller::quit('Unknown error reading /temp directory');
        }

        return $dir;
    }

    public static function quit($msg)
    {
        $begin = '<div style="margin:auto;margin-top:100px;width:40%;height:80px;padding:30px;color:red;border:3px solid darkgray;text-align:center;background-color:rgba(160, 233, 174, 0.42);border-radius:15px;box-shadow: 10px 10px 5px #888888;"><p style="font-size: 14pt;">';
        $end = '</p><p style="margin-bottom:120px;"><a href="' . MODX_MANAGER_URL . '">Back to Manager</a></p></div>';
        MODXInstaller::quit($begin . $msg . $end);
    }
}

/* Do not touch the following comments! You have been warned!  */
/** @var $forcePclZip bool - force the use of PclZip instead of ZipArchive */
/* [[+ForcePclZip]] */
/* [[+ForceFopen]] */
/* [[+InstallData]] */

$method = 0;
if (extension_loaded('curl') && (!$forceFopen)) {
    $method = 'curl';
} elseif (ini_get('allow_url_fopen')) {
    $method = 'fopen';
}

/* Next two lines for running in debugger  */
// if (true || !empty($_GET['modx']) && is_scalar($_GET['modx']) && isset($InstallData[$_GET['modx']])) {
//      $rowInstall = $InstallData['revo2.4.1-pl'];
// Comment our the two lines below to run in debugger.

if (!empty($method)) {

    //get first key in InstallData Array (latest version)
    reset($InstallData);
    $key = key($InstallData);
    $rowInstall = $InstallData[$key];

    if (!defined(MODX_CORE_PATH)) {
        define('MODX_API_MODE', true); // Gotta set this one constant.

        include('config.core.php');

        define(MODX_CORE_PATH, 'core');

    }

    if (file_exists('config.core.php')) {
        @include 'config.core.php';
    }

    if (!defined('MODX_CORE_PATH')) {
        MODXInstaller::quit('Could not read config.core.php');
    }

    @include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';

    if (!defined('MODX_CONNECTORS_PATH')) {
        MODXInstaller::quit('Could not read main config file');
    }


    /* run unzip and install */
    $source = dirname(__FILE__) . "/modx.zip";
    $url = $rowInstall['link'];
    set_time_limit(0);
    $success = MODXInstaller::downloadFile($url, $source, $method);

    /* Make sure we have the downloaded file */

    if ($success !== true) {
        MODXInstaller::quit($success);
    } elseif (!file_exists($source)) {
        MODXInstaller::quit('Missing file: ' . $source);
    } elseif (filesize($source) < 1) {
        MODXInstaller::quit('File: ' . $source . ' is empty -- download failed');
    }

    $tempDir = realPath(dirname(__FILE__)) . '/temp';
    MODXInstaller::mmkdir($tempDir);
    clearstatcache();

    $destination = $tempDir;

    if (!file_exists($tempDir)) {
        MODXInstaller::quit('Unable to create directory: ' . $tempDir);
    }

    if (!is_readable($tempDir)) {
        MODXInstaller::quit('Unable to read from /temp directory');
    }
    set_time_limit(0);
    $success = MODXInstaller::unZip(MODX_CORE_PATH, $source, $destination, $forcePclZip);
    if ($success !== true) {
        MODXInstaller::quit($success);
    }


    $directories = MODXInstaller::getDirectories();

    $directories = MODXInstaller::normalize($directories);

    $sourceDir = $tempDir . '/' . MODXInstaller::getModxDir($tempDir);
    $sourceDir = MODXInstaller::normalize($sourceDir);

    MODXInstaller::copyFiles($sourceDir, $directories);

    unlink($source);

    if (!is_dir(MODX_BASE_PATH . 'setup')) {
        MODXInstaller::quit('File Copy Failed');
    }

    MODXInstaller::removeFolder($tempDir, true);

    /* Clear cache files but not cache folder */

    $path = MODX_CORE_PATH . 'cache';
    if (is_dir($path)) {
        MODXInstaller::removeFolder($path, false);
    }

    unlink(basename(__FILE__));
    header('Location: ' . $rowInstall['location']);

}

?>