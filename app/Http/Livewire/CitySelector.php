<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CitySelector extends Component
{
    public $city_id;

    public function mount()
    {
        $this->city_id = session('city_id');
    }

    public function setCity($city_id)
    {
        $this->city_id = $city_id;
        session(['city_id' => $city_id]);
    }

    public function render()
    {
        return view('livewire.city-selector');
    }
}
