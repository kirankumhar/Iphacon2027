<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'gst_amount')) {
                $table->decimal('gst_amount', 10, 2)->default(0.00)->after('delegate_fee');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'gst_amount')) {
                $table->decimal('gst_amount', 10, 2)->default(0.00)->after('delegate_category_fee');
            }
        });

        // Recalculate delegate_fee and gst_amount for existing registrations
        $registrations = DB::table('registrations')->get();
        foreach ($registrations as $reg) {
            $total = (float) $reg->total_amount;
            if ($total > 0) {
                if ($reg->delegate_type === 'International') {
                    $delFee = $total;
                    $gstAmt = 0.00;
                } else {
                    $delFee = round($total / 1.18, 2);
                    $gstAmt = round($total - $delFee, 2);
                }
                DB::table('registrations')->where('id', $reg->id)->update([
                    'delegate_fee' => $delFee,
                    'gst_amount' => $gstAmt,
                ]);
            }
        }

        // Recalculate for existing payments
        $payments = DB::table('payments')->get();
        foreach ($payments as $pmt) {
            $total = (float) $pmt->total_amount;
            if ($total > 0) {
                if ($pmt->currency === 'USD') {
                    $delFee = $total;
                    $gstAmt = 0.00;
                } else {
                    $delFee = round($total / 1.18, 2);
                    $gstAmt = round($total - $delFee, 2);
                }
                DB::table('payments')->where('id', $pmt->id)->update([
                    'delegate_category_fee' => $delFee,
                    'gst_amount' => $gstAmt,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'gst_amount')) {
                $table->dropColumn('gst_amount');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'gst_amount')) {
                $table->dropColumn('gst_amount');
            }
        });
    }
};
