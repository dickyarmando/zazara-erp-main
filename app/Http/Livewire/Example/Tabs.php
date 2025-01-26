<?php

namespace App\Http\Livewire\Example;

use Livewire\Component;

class Tabs extends Component
{
    public $tab_active = '1';

    public function render()
    {
        return view('livewire.example.tabs');
    }

    public function select($index)
    {
        $this->tab_active = $index;
    }
}
