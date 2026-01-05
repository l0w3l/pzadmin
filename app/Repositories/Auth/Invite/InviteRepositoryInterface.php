<?php

declare(strict_types=1);

namespace App\Repositories\Auth\Invite;

use App\Data\Auth\InviteData;
use App\Exceptions\Auth\InviteLimitException;
use App\Exceptions\Auth\InviteNotFoundException;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface InviteRepositoryInterface extends RepositoryInterface
{
    public function create(int $limit): InviteData;

    /**
     * @throws InviteNotFoundException
     */
    public function find(string $hash): InviteData;

    /**
     * @throws InviteLimitException
     * @throws InviteLimitException
     */
    public function decLimit(InviteData $inviteData): InviteData;
}
