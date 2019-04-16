<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 13.12.2018
 * Time: 22:15
 */

namespace Core\Classes;


class Response
{
    private $headers = array();
    private $level = 0;

    public function addHeader(string $header) : void
    {
        $this->headers[] = $header;
    }

    public function setCompression(int $level) : void
    {
        $this->level = $level;
    }

    public function compress(string $data, int $level = 0) : string
    {
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])
                && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
            $encoding = 'gzip';
        }

        if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])
                && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
            $encoding = 'x-gzip';
        }

        if (!isset($encoding) || ($level < -1 || $level > 9)) {
            return $data;
        }

        if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
            return $data;
        }

        if (headers_sent()) {
            return $data;
        }

        if (connection_status()) {
            return $data;
        }

        $this->addHeader('Content-Encoding: ' . $encoding);

        return gzencode($data, (int)$level);
    }

    public function display(string $data) : void
    {
        $data = $this->level ? $this->compress($data, $this->level) : $data;

        if (!headers_sent()) {
            foreach ($this->headers as $header) {
                header($header, true);
            }
        }

        echo $data;
    }
}