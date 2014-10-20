<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 25/06/14.06.2014 13:13
 */

namespace Mindy\Storage;


use Mindy\Exception\Exception;
use Mindy\Helper\Alias;

class MimiBoxStorage extends Storage
{
    /**
     * @var string
     */
    public $mimiboxUrl = "http://mimi-box.com";
    /**
     * @var string
     */
    public $apiKey = '';
    /**
     * @var string
     */
    public $username = '';

    protected function openInternal($name, $mode)
    {
        // TODO: Implement openInternal() method.
    }

    protected function saveInternal($name, $content)
    {
        $file = tempnam(Alias::get('application.runtime'), 'POST_MIMIBOX_');
        $saved = file_put_contents($file, $content);
        if($saved === false) {
            throw new Exception("File not saved");
        }

        $ch = curl_init($this->mimiboxUrl . '/' . dirname($name));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->apiKey,
            'X-USERNAME: ' . $this->username
        ]);

        if(PHP_VERSION > 5.4) {
            $file = new \CurlFile($file, null, basename($name));
        } else {
            $file = "@" . $file . ';filename=' . basename($name);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'files' => $file
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        unlink($file);
        return $result;
    }

    public function path($name)
    {
        return rtrim($this->mimiboxUrl, '/') . '/' . $this->username  . '/' . $name;
    }

    public function url($name)
    {
        return $this->path($name);
    }

    public function delete($name)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $name
     * @throws \Mindy\Exception\Exception
     * @return bool
     */
    public function exists($name)
    {
        // TODO: Implement exists() method.
    }

    /**
     * Retrieves the list of files and directories from storage py path
     * @param $path
     */
    public function dir($path = null)
    {
        // TODO: Implement dir() method.
    }

    /**
     * Retrieves the list of files and directories from storage py path
     * @param $path
     */
    public function mkDir($path)
    {
        // TODO: Implement mkDir() method.
    }
}

