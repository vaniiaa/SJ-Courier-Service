<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PageHeader extends Component
{
    /**
     * Judul halaman yang akan ditampilkan.
     *
     * @var string
     */
    public string $title;

    /**
     * Create a new component instance.
     *
     * @param  string  $title Judul halaman, defaultnya 'Halaman'.
     * @return void
     */
    public function __construct(string $title = 'Halaman')
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.page-header');
    }
}
