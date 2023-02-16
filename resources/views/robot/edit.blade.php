<?php $isNew = null === $robot; ?>

@extends('layout.main')

@section('content')
    <h1 class="mt-5 mb-5">{{ $isNew ? 'Új robot felvétele' : 'Robot szerkesztése' }}</h1>
    <form method="POST" action="{{ route($isNew ? 'robot-store' : 'robot-update') }}">
        @csrf
        @if (false === $isNew)
            <input type="hidden" name="id" value="{{ $robot->id }}" />
        @endif
        @error('id')
            <div class="mt-1 alert alert-danger">{{ $message }}</div>
        @enderror
        <div class="mb-2">
            <label for="name" class="form-label">Robot neve</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ??  $robot->name ?? '' }}">
            @error('name')
                <div class="mt-1 alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-2">
            <label for="type" class="form-label">Robot típusa</label>
            <select class="form-select" id="type" name="type">
                @foreach(\App\Enum\RobotEnum::TYPES as $type)
                    <option value="{{ $type }}" @if((old('type') ?? $robot->type ?? '') === $type) selected @endif>{{ $type }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="mt-1 alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="power" class="form-label">Robot ereje</label>
            <input type="number" class="form-control" id="power" name="power" min="1" max="2147483647" step="1"  value="{{ old('power') ?? $robot->power ?? '' }}">
            @error('power')
                <div class="mt-1 alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <button type="submit" class="btn btn-primary">{{ $isNew ? 'Mentés' : 'Módosítás' }}</button>
        </div>
    </form>
@endsection
