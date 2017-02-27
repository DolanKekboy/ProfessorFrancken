<?php

declare(strict_types=1);

namespace Francken\Tests\Domain\Members;

use Francken\Domain\Members\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_stores_an_email()
    {
        $email = new Email('markredeman@gmail.com');

        $this->assertEquals('markredeman@gmail.com', (string) $email);
    }

    /** @test */
    public function it_accepts_an_email_with_gmail_aliases()
    {
        $email = new Email('markredeman+123@gmail.com');

        $this->assertEquals('markredeman+123@gmail.com', (string) $email);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_does_not_store_invalid_emails()
    {
        $email = new Email('markredeman.com');
    }
}
