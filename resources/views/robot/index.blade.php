@extends('layout.main')

@section('content')
    <a href="{{ route('robot-create') }}">Új robot felvétele</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Típus</th>
                <th>Erő</th>
                <th>Opciók</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entities as $entity)
                <tr>
                    <td>{{ $entity->id }}</td>
                    <td>{{ $entity->name }}</td>
                    <td>{{ $entity->type }}</td>
                    <td>{{ $entity->power }}</td>
                    <td>
                        <a href="{{ route('robot-edit', ['id' => $entity->id]) }}">Szerkesztés</a>
                        <form method="POST" action="{{ route('robot-delete') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $entity->id }}" />
                            <input type="submit" value="Törlés" />
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
