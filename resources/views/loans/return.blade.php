@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Devolver Libro</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('loans.return', $loan->id) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" class="form-control" value="{{ $loan->user->name }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Libro</label>
            <input type="text" class="form-control" value="{{ $loan->book->title }}" disabled>
        </div>
        <div class="mb-3">
            <label for="return_date" class="form-label">Fecha de Devolución</label>
            <input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date') }}" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notas</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Devolver Libro</button>
        <a href="{{ route('loans.index') }}" class="btn btn-secondary ms-2">Volver al listado</a>
    </form>
</div>
@endsection 