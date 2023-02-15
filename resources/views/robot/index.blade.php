@extends('layout.main')

@section('content')
    <h1 class="mt-5 mb-5">Robotok</h1>
    @error('id')
    <div class="mt-1 alert alert-danger">{{ $message }}</div>
    @enderror
    @if (null !== $sessionMessage = Session::get('message'))
        <div class="mt-1 alert alert-success">{{ $sessionMessage }}</div>
    @endif
    <a class="btn btn-primary mb-3" href="{{ route('robot-create') }}">Új robot felvétele</a>
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
                            <a class="btn btn-primary" href="{{ route('robot-edit', ['id' => $entity->id]) }}">Szerkesztés</a>
                            <a class="btn btn-danger" href="{{ route('robot-delete', ['id' => $entity->id]) }}">Törlés</a>
                            <input type="checkbox" class="btn-check" name="id[]" id="robot_id_{{ $entity->id }}" value="{{ $entity->id }}" />
                            <label class="btn btn-outline-primary" for="robot_id_{{ $entity->id }}">Kijelölés harcra</label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <input class="btn btn-primary" type="submit" value="Harcba küldés" />
    </form>
@endsection
