<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewEmisiNotification extends Notification
{
    use Queueable;

    protected $emisi;

    public function __construct($emisi)
    {
        $this->emisi = $emisi;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'emisi',
            'kode_emisi' => $this->emisi->kode_emisi_karbon,
            'kategori' => $this->emisi->kategori_emisi_karbon,
            'jumlah_ton' => number_format($this->emisi->kadar_emisi_karbon / 1000, 3),
            'message' => 'Pengajuan emisi karbon baru memerlukan persetujuan',
            'url' => route('admin.emisi.show', $this->emisi->kode_emisi_karbon)
        ];
    }
} 