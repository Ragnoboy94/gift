<div class="d-flex justify-content-end">
    <div class="dropdown">
        <button class="btn btn-primary text-black dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ strtoupper(app()->getLocale()) }}
        </button>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
            @foreach (config('app.available_locales') as $locale)
                <a class="dropdown-item" href="{{ route('language.switch', $locale) }}">{{ strtoupper($locale) }}</a>
            @endforeach
        </div>
    </div>
</div>
