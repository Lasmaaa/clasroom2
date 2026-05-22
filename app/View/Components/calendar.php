<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Calendar extends Component
{
    public $completedDates;
    public $dueDates;           // Jauni: termiņu datumi

    public function __construct($completedDates = [], $dueDates = [])
    {
        $this->completedDates = $completedDates;
        $this->dueDates = $dueDates;
    }

    public function render()
    {
        return view('components.calendar');
    }
}