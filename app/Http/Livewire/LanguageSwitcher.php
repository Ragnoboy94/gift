<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\App;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public $locale;

    public function mount()
    {
        $this->locale = app()->getLocale();
    }

    public function switchLanguage($locale)
    {
        app()->setLocale($locale);
        $this->locale = $locale;
        session(['app_locale' => $locale]);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
