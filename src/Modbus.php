<?php

/**
 * @see https://npowest.ru
 *
 * @license Shareware
 * @copyright (c) 2019-2024 NPOWest
 */

declare(strict_types=1);

namespace Npowest\Modbus;

use Socket;

use function chr;
use function in_array;
use function ord;
use function sprintf;
use function strlen;

use const AF_INET;
use const PHP_BINARY_READ;
use const SO_RCVTIMEO;
use const SO_SNDTIMEO;
use const SOCK_STREAM;
use const SOL_SOCKET;
use const SOL_TCP;

final class Modbus
{
    // Максимальное количество попыток соединения
    private const MAX_RETRIES = 5;

    // Таймаут соединения в секундах
    private const TIMEOUT_SEC = 5;

    private false|Socket $socket;

    private bool $readySocket = false;

    private string $error = '';

    private string $host = '';

    private int $port = 0;

    private int $addrModbus = 0;

    private string $msg = '';

    public function setAddress(string $host, int $port, int $addrModbus): void
    {
        $this->host = $host;
        $this->port = $port;

        $this->addrModbus = $addrModbus;
    }//end setAddress()

    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }//end setMsg()

    public function getError(): string
    {
        return $this->error;
    }//end getError()

    public function app(): bool|string
    {
        if (! $this->connect())
        {
            return false;
        }

        $result = false;
        if ($this->msg)
        {
            for ($i = 0; $i < self::MAX_RETRIES; ++$i)
            {
                $result = $this->send();
                if (! $result)
                {
                    $this->error = 'Failed to send message';

                    break;
                }

                $result = $this->listen();

                if ($result)
                {
                    break;
                }

                // Пауза перед повторной попыткой
                sleep(10);
            }
        }//end if

        $this->disconnect();

        return $result;
    }//end app()

    private function connect(): bool
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (! $this->socket)
        {
            $this->error = 'Socket creation failed: '.socket_strerror(socket_last_error());

            return false;
        }

        socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 1, 'usec' => 0]);
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 1, 'usec' => 0]);

        $startTime   = microtime(true);
        $elapsedTime = 0;

        while ($elapsedTime < self::TIMEOUT_SEC)
        {
            if (@socket_connect($this->socket, $this->host, $this->port))
            {
                $this->readySocket = true;

                return true;
            }

            $elapsedTime = microtime(true) - $startTime;
        }

        $this->error = 'Connection timeout.';

        return false;
    }//end connect()

    private function disconnect(): void
    {
        if ($this->readySocket)
        {
            socket_close($this->socket);
            $this->readySocket = false;
        }
    }//end disconnect()

    public function sendCommand(string $command, int $address, int $len, string $data = ''): false|string
    {
        if (! in_array($command, ['set', 'get']))
        {
            $this->error = 'Invalid command';

            return false;
        }

        $this->msg = ('set' === $command) ? sprintf('10%04x%04x%02x%s', $address, $len * 2, $len, $data) : sprintf('03%04x%04x', $address, $len);

        return $this->getResult();
    }//end sendCommand()

    private function isValidResponse(string $result): bool
    {
        $expectedAddress = sprintf('%02x', $this->addrModbus);

        return substr($result, 0, 2) === $expectedAddress;
    }//end isValidResponse()

    private function getResult(): false|string
    {
        if (! $this->connect())
        {
            return false;
        }

        for ($i = 0; $i < self::MAX_RETRIES; ++$i)
        {
            $this->send();
            $result = $this->listen();

            if ($result && $this->isValidResponse($result))
            {
                return $this->parserResult($result);
            }

            sleep(2);
            // Пауза перед повторной попыткой
        }

        $this->disconnect();

        return false;
    }//end getResult()

    private function parserResult(string $result): string
    {
        $answer = substr($result, 2, 2);
        switch ($answer)
        {
            case '03':
                // Get response
                return substr($result, 6, -4);

            case '10':
                // Set response
                return substr($result, 4, -4);
            default:
                $this->error = 'Invalid response: '.$result;

                return '';
        }
    }//end parserResult()

    private function send(): bool
    {
        if ($this->readySocket && $this->msg)
        {
            $msg          = $this->prepareMsg();
            $bytesWritten = socket_write($this->socket, $msg, strlen($msg));
            if (false === $bytesWritten)
            {
                $this->error = 'Socket write error: '.socket_strerror(socket_last_error($this->socket));

                return false;
            }

            return true;
        }

        return false;
    }//end send()

    private function prepareMsg(): string
    {
        if (! $this->msg)
        {
            return '';
        }

        $msg = str_split($this->msg, 2);

        $result = chr($this->addrModbus);
        foreach ($msg as $str)
        {
            $result .= chr(hexdec($str));
        }

        $crc16 = $this->crc16($result);

        $result .= chr($crc16 & 0xFF);
        $result .= chr($crc16 >> 8);

        return $result;
    }//end prepareMsg()

    private function listen(): false|string
    {
        if (! $this->readySocket)
        {
            return false;
        }

        $out = socket_read($this->socket, 1_024, PHP_BINARY_READ);

        return false !== $out ? $this->printPacket($out) : false;
    }//end listen()

    private function crc16(string $data): int
    {
        $crc = 0xFF_FF;
        $len = strlen($data);
        for ($i = 0; $i < $len; ++$i)
        {
            $crc ^= ord($data[$i]);
            for ($j = 0; $j < 8; ++$j)
            {
                $crc = ($crc & 1) ? ($crc >> 1) ^ 0xA0_01 : ($crc >> 1);
            }
        }

        return $crc;
    }//end crc16()

    private function printPacket(string $packet): string
    {
        return implode('', array_map(static fn ($byte) => sprintf('%02x', ord($byte)), str_split($packet)));
    }//end printPacket()
}//end class
