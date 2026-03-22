<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Test extends Component
{
    public $count = 0;


    #[On('p')]
    public function p()
    {
        $this->count++;
    }

    #[On('d')]
    public function d()
    {

        if ($this->count <= 0) {
            $this->count = 0;
        } else {

            $this->count--;
        }
    }

    public function render()
    {
        return view('livewire.test');
    }
}
