<?php

namespace App\Services;

use App\Models\Registration;
use Carbon\Carbon;

class FestiveDiscountService
{
    /**
     * Check if the festive discount feature is globally enabled in config.
     */
    public static function isEnabled(): bool
    {
        return (bool) config('services.festive_discount.enabled', true);
    }

    /**
     * Check if the festive discount is forced active (for testing or manual override).
     */
    public static function isForceActive(): bool
    {
        return (bool) config('services.festive_discount.force_active', false);
    }

    /**
     * Get the configured start datetime as a Carbon instance.
     */
    public static function getStartDate(): Carbon
    {
        $tz = config('services.festive_discount.timezone', 'Asia/Kolkata');
        $dateStr = config('services.festive_discount.start_date', '2026-11-04 00:00:00');
        return Carbon::parse($dateStr, $tz);
    }

    /**
     * Get the configured end datetime as a Carbon instance.
     */
    public static function getEndDate(): Carbon
    {
        $tz = config('services.festive_discount.timezone', 'Asia/Kolkata');
        $dateStr = config('services.festive_discount.end_date', '2026-11-08 23:59:59');
        return Carbon::parse($dateStr, $tz);
    }

    /**
     * Determine if current date/time is within the active festive window.
     */
    public static function isActive(): bool
    {
        if (!self::isEnabled()) {
            return false;
        }

        if (self::isForceActive()) {
            return true;
        }

        $tz = config('services.festive_discount.timezone', 'Asia/Kolkata');
        $now = Carbon::now($tz);

        return $now->between(self::getStartDate(), self::getEndDate());
    }

    /**
     * Determine if the festive discount applies to a specific registration.
     */
    public static function isActiveForRegistration(?Registration $registration): bool
    {
        if (!$registration) {
            return self::isActive();
        }

        // Only applies to Indian delegates as per current requirement
        if ($registration->delegate_type === 'International') {
            return false;
        }

        // If registration is already approved, preserve whether it was approved with discount
        if ($registration->status === 'Approved') {
            $catFee = $registration->delegateCategory ? (float) $registration->delegateCategory->indian_fee : 0.00;
            return ($catFee > 0 && (float) $registration->delegate_fee < $catFee);
        }

        // For pending/draft registrations, active based on current time window
        return self::isActive();
    }

    /**
     * Get the discount percentage (e.g. 5.0 for 5%).
     */
    public static function getDiscountPercentage(): float
    {
        return (float) config('services.festive_discount.percentage', 5.0);
    }

    /**
     * Calculate discount amount for a given base fee.
     */
    public static function calculateDiscount(float $baseFee): float
    {
        if ($baseFee <= 0) {
            return 0.00;
        }
        return round($baseFee * (self::getDiscountPercentage() / 100), 2);
    }

    /**
     * Get discounted fee after deducting the festive discount.
     */
    public static function getDiscountedFee(float $baseFee): float
    {
        $discount = self::calculateDiscount($baseFee);
        return max(0.00, round($baseFee - $discount, 2));
    }

    /**
     * Get human-readable window dates description.
     */
    public static function getWindowText(): string
    {
        $start = self::getStartDate();
        $end = self::getEndDate();
        return $start->format('jS') . ' to ' . $end->format('jS F Y');
    }
}

