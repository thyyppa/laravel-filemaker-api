<?php

namespace Hyyppa\Filemaker\Support;

use Hyyppa\Filemaker\Contracts\ConfigInterface;

class Config implements ConfigInterface
{
    public $host;
    public $file;
    public $username;
    public $password;
    public $token;
    public $ignore_ssl_errors;

    public function __construct(array $options = [])
    {
        $this->host = $options['host'] ?? config('filemaker.host');
        $this->file = $options['file'] ?? config('filemaker.file');
        $this->username = $options['username'] ?? config('filemaker.username');
        $this->password = $options['password'] ?? config('filemaker.password');
        $this->ignore_ssl_errors = $options['ignore-ssl-errors'] ?? config('filemaker.ignore-ssl-errors', true);
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function setToken(string $token): string
    {
        return $this->token = $token;
    }
}
