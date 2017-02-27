<?php

declare(strict_types=1);

namespace Francken\Domain\Members\Events;

use Broadway\Serializer\SerializableInterface;
use BroadwaySerialization\Serialization\Serializable;
use Francken\Domain\Members\MemberId;

final class MemberJoinedFrancken implements SerializableInterface
{
    use Serializable;

    private $memberId;
    private $first_name;
    private $last_name;

    public function __construct(MemberId $id, $first_name, $last_name)
    {
        $this->memberId = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    public function memberId()
    {
        return $this->memberId;
    }

    public function firstName()
    {
        return $this->first_name;
    }

    public function lastName()
    {
        return $this->last_name;
    }

    protected static function deserializationCallbacks()
    {
        return [
            'memberId' => [MemberId::class, 'deserialize']
        ];
    }
}
