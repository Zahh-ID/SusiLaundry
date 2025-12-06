<?php

namespace App\Livewire\Admin\Order\Modals;

use Livewire\Component;

class ExportOrders extends Component
{
    public bool $show = false;
    public array $filters = [];

    protected $listeners = ['open-export-modal' => 'open'];

    public function open(array $filters): void
    {
        $this->filters = $filters;
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function export()
    {
        $params = array_filter([
            'status' => ($this->filters['status'] ?? 'all') !== 'all' ? $this->filters['status'] : null,
            'service_type' => ($this->filters['service_type'] ?? 'all') !== 'all' ? $this->filters['service_type'] : null,
            'date_from' => $this->filters['date_from'] ?? null,
            'date_to' => $this->filters['date_to'] ?? null,
        ], fn($value) => $value !== null);

        $this->close();
        return redirect()->route('admin.reports.orders.export', $params);
    }

    public function render()
    {
        return view('livewire.admin.order.modals.export-orders');
    }
}
