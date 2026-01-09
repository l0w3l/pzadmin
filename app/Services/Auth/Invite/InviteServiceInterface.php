<?php

declare(strict_types=1);

namespace App\Services\Auth\Invite;

use App\Data\Auth\InviteData;
use App\Exceptions\Auth\InviteLimitException;
use App\Exceptions\Auth\InviteNotFoundException;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface InviteServiceInterface extends ServiceInterface
{
    public function createInvite(int $limit): InviteData;

    /**
     * @throws InviteNotFoundException
     * @throws InviteLimitException
     */
    public function verifyInviteHash(string $hash): InviteData;

    /**
     * @throws InviteNotFoundException
     * @throws InviteLimitException
     */
    public function decLimit(InviteData $inviteData): InviteData;
}
