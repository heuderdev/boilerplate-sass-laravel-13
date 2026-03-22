<?php

namespace App\Http\Controllers\Api;

use App\Models\Log as ModelLog;
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
        ModelLog::query()->create(['string_logs' => json_encode($payload)]);
        $this->billing->handleCheckoutCompleted($payload);
    }

    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        ModelLog::query()->create(['string_logs' => json_encode($payload)]);
        $this->billing->handleSubscriptionCanceled($payload);
    }
}
