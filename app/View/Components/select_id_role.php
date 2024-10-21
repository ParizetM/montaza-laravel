<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select_id_role extends Component
{
    public $entites;

    public $class;

    public $selected_role;

    public $onchange;

    /**
     * Create a new component instance.
     */
    public function __construct(array $entites, $class = null, $selected_role = null, $onchange = null)
    {
        $this->entites = $entites;
        $this->class = $class;
        $this->selected_role = $selected_role;
        $this->onchange = $onchange;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-id_role');
    }
}
