<?php

$theme = $theme ?? 'light';

?>

<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown mr-md-2">
        <a id="navbarDropdownLanguages" class="nav-link dropdown-toggle not-after" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="flag flag-{{ app()->getLocale() }}"></div>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLanguages">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    <div class="flag flag-{{ $localeCode }}"></div>
                    {{ $properties['native'] }}
                </a>
            @endforeach
        </div>
    </li>

    <!-- Authentication Links -->
    @guest
        <li>
            <a class="{{ $theme === 'light' ? 'text-primary' : 'text-white' }} nav-link" href="{{ route('login') }}" title="{{ t('home.Login') }}">
                <b>{{ t('home.Login') }}</b> <i class="fas fa-sign-in-alt"></i>
            </a>
        </li>
        <li>
            <a class="{{ $theme === 'light' ? 'text-primary' : 'text-white' }} nav-link" href="{{ route('register') }}" title="{{ t('home.Register') }}">
                <i class="fas fa-user-plus"></i>
            </a>
        </li>
    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="{{ $theme === 'light' ? 'text-primary' : 'text-white' }} dropdown-toggle nav-link not-after btn mb-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="fas fa-user"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <span class="dropdown-header">{{ Auth::user()->name }}</span>
                <a class="dropdown-item" href="{{ route('profile.home') }}">
                    {{ t('home.Profile') }}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}">
                    {{ t('home.Logout') }}
                </a>
            </div>
        </li>
    @endguest
</ul>