<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoadingSpinner extends Component {
    public $color;
    public $size;

    /**
     * Create a new component instance.
     *
     * @param string $color
     * @param string $size
     */
    public function __construct($color = 'indigo-500', $size = '4') {
        $this->color = $color;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    function render(): View|Closure|string {
        return view('components.loading-spinner');
    }
}
