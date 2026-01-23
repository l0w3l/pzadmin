<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Rcon;

use Lowel\LaravelServiceMaker\Services\AbstractService;
use xPaw\SourceQuery\Exception\AuthenticationException;
use xPaw\SourceQuery\Exception\InvalidArgumentException;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;
use xPaw\SourceQuery\SourceQuery;

class RconService extends AbstractService implements RconServiceInterface
{
    private SourceQuery $client;

    /**
     * @throws InvalidPacketException
     * @throws AuthenticationException
     * @throws SocketException
     * @throws InvalidArgumentException
     */
    public function __construct(string $host, int $port, #[\SensitiveParameter] string $password)
    {
        $this->client = new SourceQuery;

        $this->client->Connect($host, $port);

        $this->client->SetRconPassword($password);
    }

    /**
     * @throws InvalidPacketException
     * @throws SocketException
     * @throws AuthenticationException
     */
    public function quite(): void
    {
        $this->client->Rcon('quit');
    }

    /**
     * @throws SocketException
     * @throws InvalidPacketException
     * @throws AuthenticationException
     */
    public function save(): void
    {
        $this->client->Rcon('save');
    }

    public function __destruct()
    {
        $this->client->Disconnect();
    }
}
