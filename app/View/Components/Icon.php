<?php

namespace App\View\Components;

use Illuminate\View\Component;


class Icon extends Component
{
    public $size;
    public $type;
    public $class;
    /**
     * Summary of __construct
     * @param mixed $size
     * @param mixed $type
     * @param mixed $class
     */
    public function __construct($size = '6',$type = 'error_icon',$class = null)
    {
        $this->size = $size; // Taille par défaut à 6
        $this->type = $type;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.icon');
    }
}
