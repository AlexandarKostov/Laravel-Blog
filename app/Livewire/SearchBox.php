<?php

namespace App\Livewire;

use Livewire\Component;

class SearchBox extends Component
{
    public string $search = '';


    public function updatedSearch(): void
    {
        $this->dispatch('search', search:$this->search);
    }

    public function render()
    {
        return view('livewire.search-box');
    }
}
