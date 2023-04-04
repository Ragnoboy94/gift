<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CitySelector extends Component
{
    public $city;

    public function mount()
    {
        $this->city = session('city');
    }

    public function setCity($city)
    {
        $this->city = $city;
        session(['city' => $city]);
    }

    public function render()
    {
        return view('livewire.city-selector');
    }
}
