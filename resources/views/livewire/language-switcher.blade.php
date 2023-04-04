<li class="nav-item dropdown me-3">
    <a id="languageDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ strtoupper(app()->getLocale()) }}
    </a>
    <div class="dropdown-menu" aria-labelledby="languageDropdown">
        <a class="dropdown-item" href="{{ route('language.switch', 'en') }}">EN</a>
        <a class="dropdown-item" href="{{ route('language.switch', 'ru') }}">RU</a>

    </div>
</li>
