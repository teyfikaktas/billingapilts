<?php

namespace App\Services;

use App\Models\Bill;
use Carbon\Carbon;

class BillingService
{
    protected $usageService;
    protected $subscriberService;

    public function __construct(UsageService $usageService, SubscriberService $subscriberService)
    {
        $this->usageService = $usageService;
        $this->subscriberService = $subscriberService;
    }

    public function calculateBill(string $subscriberNo, int $month, int $year)
    {
        // Abone varsa kullan, yoksa oluştur
        $this->subscriberService->createIfNotExists($subscriberNo);
        
        // Önce bu abone için bu ay içindeki kullanımı al
        $usage = $this->usageService->getMonthlyUsage($subscriberNo, $month, $year);
        
        // Telefon ücretini hesapla - 1000 dakika ücretsiz, sonraki her 1000 dakika 10$
        $phoneAmount = 0;
        $phoneUsage = $usage['phone'];
        if ($phoneUsage > 1000) {
            $phoneAmount = ceil(($phoneUsage - 1000) / 1000) * 10;
        }
        
        // İnternet ücretini hesapla - 20GB'a kadar 50$, sonraki her 10GB için 10$
        $internetAmount = 0;
        $internetUsage = $usage['internet']; // MB cinsinden
        $internetGb = $internetUsage / 1024; // GB'a çevir
        
        if ($internetGb > 0) {
            $internetAmount = 50; // Baz ücret (20GB'a kadar)
            
            if ($internetGb > 20) {
                $internetAmount += ceil(($internetGb - 20) / 10) * 10;
            }
        }
        
        $totalAmount = $phoneAmount + $internetAmount;
        
        // Mevcut faturayı güncelle veya yeni fatura oluştur
        $bill = Bill::updateOrCreate(
            [
                'subscriber_no' => $subscriberNo,
                'month' => $month,
                'year' => $year
            ],
            [
                'total_amount' => $totalAmount,
                'phone_amount' => $phoneAmount,
                'internet_amount' => $internetAmount
            ]
        );
        
        return $bill;
    }

    public function getBill(string $subscriberNo, int $month, int $year)
    {
        $bill = Bill::where('subscriber_no', $subscriberNo)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
            
        if (!$bill) {
            // Fatura yoksa hesapla
            $bill = $this->calculateBill($subscriberNo, $month, $year);
        }
        
        return $bill;
    }

    public function getBillsPaginated(string $subscriberNo, int $month, int $year, int $perPage = 10)
    {
        return Bill::where('subscriber_no', $subscriberNo)
            ->where('month', $month)
            ->where('year', $year)
            ->paginate($perPage);
    }
}