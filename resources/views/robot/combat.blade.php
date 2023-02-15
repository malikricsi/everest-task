@extends('layout.main')

@section('content')
    <h1 class="mt-5 mb-5">Harc eredménye</h1>
    <div class="card mb-5">
        <div class="card-body">
            <h4 class="mb-3">Győztes robot adatai:</h4>
            <div class="mb-1">
                ID: {{ $winner->id }}
            </div>
            <div class="mb-1">
                Név: {{ $winner->name }}
            </div>
            <div class="mb-1">
                Típus: {{ $winner->type }}
            </div>
            <div class="mb-1">
                Erő: {{ $winner->power }}
            </div>
        </div>
    </div>
    <a href="{{ route('robot-index') }}">Vissza a robotokhoz</a>
@endsection
