<?php

declare(strict_types=1);

namespace Francken\Domain\Members;

use Broadway\Serializer\SerializableInterface;
use Francken\Domain\Serializable;

final class Gender implements SerializableInterface
{
    use Serializable;

    const FEMALE = 'female';
    const MALE = 'male';

    private $gender;

    private function __construct(string $gender)
    {
        $this->gender = $gender;
    }

    public static function fromString(string $gender) : Gender
    {
        if ( ! in_array($gender, [
            self::FEMALE, self::MALE
        ])) {
            throw new \InvalidArgumentException("{$gender} is not a valid gender");
        }

        return new self($gender);
    }

    public function __toString() : string
    {
        return $this->gender;
    }
}
