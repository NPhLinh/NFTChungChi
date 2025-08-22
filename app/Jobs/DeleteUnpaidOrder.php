<?php

namespace App\Jobs;

use App\Models\DonHang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteUnpaidOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        try {
            $donHang = DonHang::find($this->orderId);
            if ($donHang && $donHang->is_thanh_toan == 0) {
                $donHang->delete();
            }
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa đơn hàng chưa thanh toán ID {$this->orderId}: " . $e->getMessage());
        }
    }
}

