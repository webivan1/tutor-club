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
        <li><a class="nav-link" href="{{ route('login') }}">{{ t('home.Login') }}</a></li>
        <li><a class="nav-link" href="{{ route('register') }}">{{ t('home.Register') }}</a></li>
    @else
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="dropdown-toggle nav-link not-after btn mb-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="material-icons">face</i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <span class="dropdown-header">{{ Auth::user()->name }}</span>
                <a class="dropdown-item" href="{{ route('cabinet.home') }}">
                    {{ t('home.Cabinet') }}
                </a>
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