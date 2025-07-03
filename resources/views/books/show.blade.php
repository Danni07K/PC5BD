@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Detalle del Libro</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $book->title }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Autor: {{ $book->author }}</h6>
            <p class="card-text">Categoría: {{ $book->category }}</p>
            <p class="card-text">Descripción: {{ $book->description }}</p>
            <p class="card-text">ISBN: {{ $book->isbn }}</p>
            <p class="card-text">Cantidad: {{ $book->quantity }}</p>
            <p class="card-text">Disponibles: {{ $book->available_quantity }}</p>
        </div>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
</div>
@endsection 