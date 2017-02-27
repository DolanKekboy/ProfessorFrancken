<?php

declare(strict_types=1);

namespace Francken\Domain\Committees\Events;

use Broadway\Serializer\SerializableInterface;
use BroadwaySerialization\Serialization\Serializable;
use Francken\Domain\Committees\CommitteeId;

final class CommitteePageChanged implements SerializableInterface
{
    use Serializable;

    private $committeeId;
    private $page;

    public function __construct(CommitteeId $committeeId, string $page)
    {
        $this->committeeId = $committeeId;
        $this->page = $page;
    }

    public function committeeId() : CommitteeId
    {
        return $this->committeeId;
    }

    public function page() : string
    {
        return $this->page;
    }

    protected static function deserializationCallbacks()
    {
        return ['committeeId' => [CommitteeId::class, 'deserialize']];
    }
}
