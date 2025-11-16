<?php

namespace App\Livewire\Admin\Package;

use App\Models\Package;
use Livewire\Component;

class Index extends Component
{
    public function delete($id): void
    {
        $package = Package::find($id);

        if ($package) {
            $package->delete();
            session()->flash('message', 'Package successfully deleted.');
        }
    }

    public function render()
    {
        $packages = Package::all();
        return view('livewire.admin.package.index', ['packages' => $packages])
            ->layout('layouts.admin', ['title' => 'Kelola Paket Laundry']);
    }
}
