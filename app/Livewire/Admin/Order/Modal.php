<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;

class Modal extends Component
{
    public $show = false;
    public $title;
    public $content;
    public $buttons;

    protected $listeners = ['openModal' => 'openModal'];

    public function openModal($title, $content, $buttons)
    {
        $this->title = $title;
        $this->content = $content;
        $this->buttons = $buttons;
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    public function handleAction($action)
    {
        $this->dispatch($action);
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.order.modal');
    }
}
