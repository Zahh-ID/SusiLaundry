<?php

namespace App\Livewire;

use App\Models\Package;
use Livewire\Component;

class ShowPackages extends Component
{
    public function render()
    {
        $packages = Package::orderBy('price_per_kg')->get();

        return view('livewire.show-packages', [
            'packages' => $packages,
        ])->layout('layouts.site', [
            'title' => 'Daftar Paket Laundry',
        ]);
    }
}
