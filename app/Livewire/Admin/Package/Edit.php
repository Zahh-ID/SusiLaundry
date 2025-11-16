<?php

namespace App\Livewire\Admin\Package;

use App\Models\Package;
use Livewire\Component;

class Edit extends Component
{
    public Package $package;
    public $package_name;
    public $description;
    public $price_per_kg;
    public $billing_type;
    public $turnaround_hours;

    public function mount(Package $package)
    {
        $this->package = $package;
        $this->package_name = $package->package_name;
        $this->description = $package->description;
        $this->price_per_kg = $package->price_per_kg;
        $this->billing_type = $package->billing_type;
        $this->turnaround_hours = $package->turnaround_hours;
    }

    public function update()
    {
        $this->validate([
            'package_name' => 'required|string',
            'description' => 'required|string',
            'price_per_kg' => 'required|numeric',
            'billing_type' => 'required|string',
            'turnaround_hours' => 'required|integer|min:1',
        ]);

        $this->package->update([
            'package_name' => $this->package_name,
            'description' => $this->description,
            'price_per_kg' => $this->price_per_kg,
            'billing_type' => $this->billing_type,
            'turnaround_hours' => $this->turnaround_hours,
        ]);

        session()->flash('message', 'Package successfully updated.');

        return redirect()->to('/admin/packages');
    }

    public function render()
    {
        return view('livewire.admin.package.edit')
            ->layout('layouts.admin', ['title' => 'Edit Paket Laundry']);
    }
}
