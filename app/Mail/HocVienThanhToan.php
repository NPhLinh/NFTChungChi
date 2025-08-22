<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HocVienThanhToan extends Mailable
{
    use Queueable, SerializesModels;

    public $bien_1;
    public $bien_2;

    public function __construct($bien_1, $bien_2)
    {
        $this->bien_1   = $bien_1;
        $this->bien_2   = $bien_2;
    }

    public function build()
    {
        return $this->subject("Thanh Toán Để Cấp Chứng Chỉ NFT ")
                    ->view('mail_hoc_vien_thanh_toan', ['bien_1' => $this->bien_1, 'bien_2' => $this->bien_2]);
    }
}
