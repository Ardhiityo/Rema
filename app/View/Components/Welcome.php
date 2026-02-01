<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Welcome extends Component
{
    public string $home;
    public string $repositories;

    /**
     * Create a new component instance.
     */
    public function __construct(string $home = '#home', string $repositories = '#repositories')
    {
        $this->home = $home;
        $this->repositories = $repositories;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.welcome');
    }
}
