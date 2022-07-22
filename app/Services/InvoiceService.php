<?php

declare(strict_types = 1);

namespace App\Services;

class InvoiceService
{
    public function __construct(
        private SalesTaxService $salesTaxService,
        private PaymentGatewayService $paymentGatewayService,
        private EmailService $emailService  
    ) {
    }

    public function process(array $customer, float $amount): bool
    {

        // 1. calculate sales tax
        $tax = $this->salesTaxService->calculate($amount, $customer);

        // 2. process invoice
        if (! $this->paymentGatewayService->charge($customer, $amount, $tax)) {
            return false;
        }

        // 3. send receipt
        $this->emailService->send($customer, 'receipt');

        echo 'Invoice Sent' . PHP_EOL;
        return true;
    }
}
