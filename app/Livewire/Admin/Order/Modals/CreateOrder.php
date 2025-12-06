<?php

namespace App\Livewire\Admin\Order\Modals;

use Livewire\Component;

class CreateOrder extends Component
{
    public bool $show = false;

    protected $listeners = [
        'open-create-modal' => 'open',
        'order-created' => 'handleOrderCreated',
    ];

    public function open(): void
    {
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        // Reset the child component state if needed, primarily handled by child's mount/render
    }

    public function handleOrderCreated($id, $message = null): void
    {
        $this->close();
        // The Index component also listens to 'order-created' to refresh the list, 
        // so we don't need to do anything else here other than closing the modal.
    }

    public function render()
    {
        return view('livewire.admin.order.modals.create-order');
    }
}
