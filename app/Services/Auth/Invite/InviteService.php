<?php

declare(strict_types=1);

namespace App\Services\Auth\Invite;

use App\Data\Auth\InviteData;
use App\Exceptions\Auth\InviteLimitException;
use App\Repositories\Auth\Invite\InviteRepositoryInterface;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class InviteService extends AbstractService implements InviteServiceInterface
{
    public function __construct(
        private readonly InviteRepositoryInterface $inviteRepository
    ) {}

    public function createInvite(int $limit): InviteData
    {
        return $this->inviteRepository->create($limit);
    }

    public function verifyInviteHash(string $hash): InviteData
    {
        $invite = $this->inviteRepository->find($hash);

        if ($invite->limit <= 0) {
            throw new InviteLimitException($invite);
        }

        return $invite;
    }

    public function decLimit(InviteData $inviteData): InviteData
    {
        return $this->inviteRepository->decLimit($inviteData);
    }
}
