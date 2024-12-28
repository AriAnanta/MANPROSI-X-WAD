<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewKompensasiNotification extends Notification
{
    use Queueable;

    protected $kompensasi;

    public function __construct($kompensasi)
    {
        $this->kompensasi = $kompensasi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'kompensasi',
            'kode_kompensasi' => $this->kompensasi->kode_kompensasi,
            'jumlah_ton' => number_format($this->kompensasi->jumlah_kompensasi / 1000, 3),
            'message' => 'Kompensasi emisi baru memerlukan persetujuan',
            'url' => route('admin.kompensasi.show', $this->kompensasi->kode_kompensasi)
        ];
    }
} 