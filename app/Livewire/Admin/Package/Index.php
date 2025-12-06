<?php

namespace App\Livewire\Admin\Package;

use App\Models\Package;
use Livewire\Component;

class Index extends Component
{
    public $showCreateModal = false;

    // Form properties
    public $package_name;
    public $description;
    public $price_per_kg;
    public $billing_type = 'per_kg';
    public $turnaround_hours = 48;

    public function create()
    {
        $this->reset(['package_name', 'description', 'price_per_kg', 'billing_type', 'turnaround_hours']);
        $this->billing_type = 'per_kg';
        $this->turnaround_hours = 48;
        $this->showCreateModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'package_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price_per_kg' => ['required', 'numeric', 'min:0', 'max:999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'billing_type' => 'required|string|in:per_kg,per_item,paket',
            'turnaround_hours' => 'required|integer|min:1|max:720',
        ]);

        Package::create([
            'package_name' => $this->package_name,
            'description' => $this->description,
            'price_per_kg' => $this->price_per_kg,
            'billing_type' => $this->billing_type,
            'turnaround_hours' => $this->turnaround_hours,
        ]);

        session()->flash('message', 'Package successfully created.');
        $this->closeModal();
    }

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
