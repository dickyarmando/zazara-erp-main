<?php

namespace App\Http\Livewire\Example;

use Livewire\Component;

class Input extends Component
{
    public $my_text = '';
    public $red_color = '';

    public function render()
    {
        return view('livewire.example.input');
    }
}
