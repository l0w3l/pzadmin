<?php

declare(strict_types=1);

namespace App\Repositories\Auth\Invite;

use App\Data\Auth\InviteData;
use App\Exceptions\Auth\InviteLimitException;
use App\Exceptions\Auth\InviteNotFoundException;
use App\Models\Auth\Invite;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class InviteRepository extends AbstractRepository implements InviteRepositoryInterface
{
    public function create(int $limit): InviteData
    {
        $inviteModel = Invite::factory()->create(['limit' => $limit]);

        return InviteData::from($inviteModel);
    }

    public function find(string $hash): InviteData
    {
        $inviteModel = Invite::whereHash($hash)->first() ?? throw new InviteNotFoundException($hash);

        return InviteData::from($inviteModel);
    }

    public function decLimit(InviteData $inviteData): InviteData
    {
        /** @var Invite $inviteModel */
        $inviteModel = Invite::whereId($inviteData->id)->first();

        if ($inviteModel->limit <= 0) {
            throw new InviteLimitException($inviteData);
        }

        $inviteModel->limit--;
        $inviteModel->save();

        return InviteData::from($inviteModel);
    }
}
