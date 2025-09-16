<?php
class RouterosAPI
{
    var $debug = false;

    var $connected = false;
    var $ip;
    var $port;
    var $socket;
    var $timeout = 3;
    var $attempts = 3;

    function connect($ip, $username, $password, $port = 8728)
    {
        $this->ip = $ip;
        $this->port = $port;
        for ($i = 1; $i <= $this->attempts; $i++) {
            $this->socket = @fsockopen($ip, $port, $errno, $errstr, $this->timeout);
            if ($this->socket) {
                break;
            }
            sleep(1);
        }
        if (!$this->socket) {
            return false;
        }
        fwrite($this->socket, chr(0x00));
        $this->write('/login', false);
        $this->write('=name=' . $username, false);
        $this->write('=password=' . $password, true);

        $response = $this->read(false);
        if (isset($response[0]) && $response[0] == '!done') {
            $this->connected = true;
            return true;
        }

        fclose($this->socket);
        return false;
    }

    function write($command, $param = true)
    {
        fwrite($this->socket, $command . ($param ? chr(0x00) : ""));
    }

    function read($parse = true)
    {
        $response = [];
        while (!feof($this->socket)) {
            $line = fgets($this->socket, 1024);
            if ($line == false || $line == "") break;
            $line = trim($line);
            if ($line != "") $response[] = $line;
            if ($line == "!done") break;
        }
        return $response;
    }

    function disconnect()
    {
        fclose($this->socket);
        $this->connected = false;
    }
}
