<div class="mb-5">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            {{ Html::link(route('profile.lesson.list.active'), t('Lessons is active'), [
                'class' => 'nav-link ' . (!Request::routeIs('profile.lesson.list.active') ?: 'active')
            ]) }}
        </li>
        <li class="nav-item">
            {{ Html::link(route('profile.lesson.list.pending'), t('Lessons is pending'), [
                'class' => 'nav-link ' . (!Request::routeIs('profile.lesson.list.pending') ?: 'active')
            ]) }}
        </li>
        <li class="nav-item">
            {{ Html::link(route('profile.lesson.list.closed'), t('Lessons is closed'), [
                'class' => 'nav-link ' . (!Request::routeIs('profile.lesson.list.closed') ?: 'active')
            ]) }}
        </li>
        <li class="nav-item">
            {{ Html::link(route('profile.lesson.list.disabled'), t('Lessons is disabled'), [
                'class' => 'nav-link ' . (!Request::routeIs('profile.lesson.list.disabled') ?: 'active')
            ]) }}
        </li>
    </ul>
</div>