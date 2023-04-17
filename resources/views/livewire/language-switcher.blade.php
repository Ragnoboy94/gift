<li class="nav-item">
    <select id="languageDropdown" class="form-select" onchange="location = this.value;">
        <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
        <option value="{{ route('language.switch', 'ru') }}" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>RU</option>
    </select>
</li>
