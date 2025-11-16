<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class TrackOrder extends Component
{
    public $order_code;
    public $order;
    public $errorMessage;

    public function track()
    {
        $this->validate([
            'order_code' => 'required|string',
        ]);

        $this->errorMessage = null;
        $this->order = Order::where('order_code', $this->order_code)
            ->with(['customer', 'package', 'payments' => fn ($query) => $query->latest()])
            ->first();

        if (! $this->order) {
            $this->errorMessage = 'Kode tidak ditemukan, silakan cek kembali.';
        }
    }

    public function render()
    {
        return view('livewire.track-order')
            ->layout('layouts.site', ['title' => 'Tracking Pesanan']);
    }
}
