@extends('layout.main')

@section('content')
    <a href="{{ route('robot-create') }}">Új robot felvétele</a>
    <form method="POST" action="{{ route('robot-combat') }}">
        @csrf
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
                            <a href="{{ route('robot-delete', ['id' => $entity->id]) }}">Törlés</a>
                            <label for="robot_id_{{ $entity->id }}">Kijelölés harcra</label>
                            <input type="checkbox" name="id[]" id="robot_id_{{ $entity->id }}"value="{{ $entity->id }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <input type="submit" value="Harcba küldés" />
    </form>
@endsection
