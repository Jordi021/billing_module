<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ErrorLayout extends Component {
    public $title;

    /**
     * Create a new component instance.
     */
    public function __construct($title = null) {
        $this->title = $title;
    }

    public function render(): View {
        return view('layouts.error');
    }
}
