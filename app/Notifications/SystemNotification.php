<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public $message;
    public $url;

    // Kita terima Pesan dan Link Tujuan saat notifikasi dibuat
    public function __construct($message, $url)
    {
        $this->message = $message;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database saja
    }

    // Format data yang disimpan ke database
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => $this->url,
            'icon' => 'bi-info-circle', // Ikon default
        ];
    }
}
