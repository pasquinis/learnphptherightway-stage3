<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\EmailService;
use PHPUnit\Framework\TestCase;
use App\Services\InvoiceService;
use App\Services\PaymentGatewayService;
use App\Services\SalesTaxService;
use PharIo\Manifest\Email;

class InvoiceServiceTest extends TestCase
{
    private SalesTaxService $salesTaxServiceMock;
    private PaymentGatewayService $gatewayServiceMock;
    private EmailService $emailServiceMock;

    protected function setUp(): void
    {
        $this->salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $this->gatewayServiceMock = $this->createMock(PaymentGatewayService::class);
        $this->emailServiceMock = $this->createMock(EmailService::class);
    }

    public function testShouldReturnTrueWhenProcessIsCalled(): void
    {

        $this->gatewayServiceMock->method('charge')->willReturn(true);

        $invoiceService = new InvoiceService(
            $this->salesTaxServiceMock,
            $this->gatewayServiceMock,
            $this->emailServiceMock
        );

        $customer = [
            'name' => 'Simone'
        ];
        $amount = 200;

        $this->assertTrue($invoiceService->process($customer, $amount));
    }

    public function testShouldCallEmailSendWhenChargeIsSuccessful(): void
    {
        $this->gatewayServiceMock
            ->method('charge')
            ->willReturn(true);

        $this->emailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with(['name' => 'Simone'], 'receipt');

        $invoiceService = new InvoiceService(
            $this->salesTaxServiceMock,
            $this->gatewayServiceMock,
            $this->emailServiceMock
        );

        $customer = [
            'name' => 'Simone'
        ];
        $amount = 200;

        $this->assertTrue($invoiceService->process($customer, $amount));
    }
}