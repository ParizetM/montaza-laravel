<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select_id_role extends Component
{
    public $entites;
    public $class;
    /**
     * Create a new component instance.
     */
    public function __construct(array $entites, $class = null)
    {
        $this->entites = $entites;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-id_role');
    }
}
