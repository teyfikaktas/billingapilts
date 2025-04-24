<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Payment;

class PaymentService
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function payBill(string $subscriberNo, int $month, int $year)
    {
        // Önce faturayı al veya oluştur
        $bill = $this->billingService->getBill($subscriberNo, $month, $year);
        
        // Kalan tutarı hesapla
        $remainingAmount = $bill->total_amount - $bill->paid_amount;
        
        if ($remainingAmount <= 0) {
            return [
                'status' => 'Error',
                'message' => 'Bill is already paid'
            ];
        }
        
        // Ödeme kaydı oluştur
        $payment = Payment::create([
            'subscriber_no' => $subscriberNo,
            'month' => $month,
            'year' => $year,
            'amount' => $remainingAmount,
            'status' => 'Successful'
        ]);
        
        // Faturayı güncelle
        $bill->paid_amount += $remainingAmount;
        $bill->is_paid = true;
        $bill->save();
        
        return [
            'status' => 'Successful',
            'payment' => $payment,
            'bill' => $bill
        ];
    }
}