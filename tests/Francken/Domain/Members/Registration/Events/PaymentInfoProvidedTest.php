<?php

declare(strict_types=1);

namespace Francken\Tests\Domain\Members\Registration\Events;

use Francken\Domain\Members\PaymentInfo;
use Francken\Domain\Members\Registration\Events\PaymentInfoProvided;
use Francken\Domain\Members\Registration\RegistrationRequestId;
use Francken\Tests\Domain\EventTestCase as Testcase;

class PaymentInfoProvidedTest extends TestCase
{
    /** @test */
    public function it_holds_payment_information()
    {
        $id = RegistrationRequestId::generate();
        $paymentInfo = new PaymentInfo(true, true);
        $event = new PaymentInfoProvided($id, $paymentInfo);

        $this->assertEquals($paymentInfo, $event->paymentInfo());
    }

    protected function createInstance()
    {
        $id = RegistrationRequestId::generate();
        $paymentInfo = new PaymentInfo(true, true);
        return new PaymentInfoProvided($id, $paymentInfo);
    }
}
