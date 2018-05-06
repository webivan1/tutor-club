@extends('layouts.profile')

@section('title', t('Your adverts'))

@section('content')
    @parent

    <h1 class="mb-4">{{ t('Your adverts') }}</h1>

    <div class="card mb-3">
        <div class="card-body text-right">
            <a href="{{ route('cabinet.advert.create') }}" class="btn btn-raised btn-success">
                {{ t('Create new ad') }}
            </a>
        </div>
    </div>

    @if (!empty($models))
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>{{ t('Locale') }}</th>
                    <th>{{ t('Category') }}</th>
                    <th>{{ t('Status') }}</th>
                    <th>{{ t('Control') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($models as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->lang }}</td>
                            <td>{{ t($item->title) }}</td>
                            <td>
                                <span class="badge badge-{{ $item->statusStyles()[$item->status] ?? 'secondary' }}">
                                    {{ $item->statuses()[$item->status] ?? '' }}
                                </span>
                            </td>
                            <td>
                                @if ($item->isEdit())
                                    <a class="btn btn-sm btn-raised btn-info" href="{{ route('cabinet.advert.update', $item) }}">
                                        {{ t('Edit') }}
                                    </a>
                                @endif

                                @if ($item->isActive())
                                    <a onclick="return confirm('{{ t('Are you sure') }}?')" class="btn btn-sm btn-raised btn-dark" href="{{ route('cabinet.advert.close', $item) }}">
                                        {{ t('Close the ad') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {!! $models->links() !!}
    @else
        <div class="alert alert-info">
            {{ t('Not found adverts') }}
        </div>
    @endif
@endsection