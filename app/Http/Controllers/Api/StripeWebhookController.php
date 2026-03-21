<?php

namespace App\Http\Controllers\Api;

use App\Services\TenantBillingService;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhook;


class StripeWebhookController extends CashierWebhook
{
    public function __construct(
        protected TenantBillingService $billing
    ) {}

    // STRIPE CHAMA ESTE MÉTODO AUTOMATICAMENTE VIA CASHIER
    public function handleCheckoutSessionCompleted(array $payload): void
    {
        $this->billing->handleCheckoutCompleted($payload);
    }

    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        $this->billing->handleSubscriptionCanceled($payload);
    }
}
