<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $name;
    public $value;
    public $type;
    public $wrap;
    public $label;
    public $class;
    public $image;

    public function __construct( $name="", $value="", $type = "text", $wrap = "v", $label = "", $class = "", $image = false )
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->wrap = $wrap;
        $this->label = $label;
        $this->class = $class;
        $this->image = $image;
    }

    public function render()
    {
        return view('components.input');
    }
}
