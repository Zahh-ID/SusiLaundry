<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class TrackOrder extends Component
{
    public $order_code;
    public $order;
    public $errorMessage;

    public function mount(): void
    {
        $code = request()->query('code');

        if ($code) {
            $this->order_code = $code;
            $this->track();
        }
    }

    public function track()
    {
        $this->validate([
            'order_code' => 'required|string',
        ]);

        $this->errorMessage = null;
        $this->order = $this->queryOrder($this->order_code);

        if (! $this->order) {
            $this->errorMessage = 'Kode tidak ditemukan, silakan cek kembali.';
        }
    }

    public function refreshStatus(): void
    {
        if (! $this->order_code || ! $this->order) {
            return;
        }

        $this->order = $this->queryOrder($this->order_code);
    }

    protected function queryOrder(string $code): ?Order
    {
        return Order::where('order_code', $code)
            ->with(['customer', 'package', 'payments' => fn ($query) => $query->latest()])
            ->first();
    }

    public function render()
    {
        return view('livewire.track-order')
            ->layout('layouts.site', ['title' => 'Tracking Pesanan']);
    }
}
