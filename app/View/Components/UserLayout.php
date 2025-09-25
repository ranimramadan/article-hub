<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserLayout extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('layouts.user');
    }
}