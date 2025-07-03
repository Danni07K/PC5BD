@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Libros Más Prestados</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Veces Prestado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mostBorrowed as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->loans_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay datos de préstamos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 