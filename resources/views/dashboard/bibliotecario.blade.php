@extends('layouts.app')

@section('title', 'Dashboard - Biblioteca Virtual')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard - Bibliotecario
            </h1>
            <p class="text-muted">Panel de control de la biblioteca virtual</p>
        </div>
        <div>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Agregar Libro
            </a>
            <a href="{{ route('loans.create') }}" class="btn btn-success ms-2">
                <i class="fas fa-handshake me-2"></i>Nuevo Préstamo
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Libros
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Libros Disponibles
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBooks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Préstamos Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Préstamos Vencidos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $overdueLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Loans -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Préstamos Recientes
                    </h6>
                    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-primary">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    @if($recentLoans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Libro</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Devolución</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLoans as $loan)
                                    <tr>
                                        <td>{{ $loan->user->name }}</td>
                                        <td>{{ $loan->book->title }}</td>
                                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                        <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                        <td>
                                            @if($loan->status === 'active')
                                                @if($loan->isOverdue())
                                                    <span class="badge bg-danger">Vencido</span>
                                                @else
                                                    <span class="badge bg-success">Activo</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Devuelto</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay préstamos recientes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Most Borrowed Books -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Libros Más Prestados
                    </h6>
                </div>
                <div class="card-body">
                    @if($mostBorrowed->count() > 0)
                        @foreach($mostBorrowed as $book)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $book->title }}</h6>
                                <small class="text-muted">{{ $book->author }}</small>
                            </div>
                            <span class="badge bg-primary">{{ $book->loans_count }} préstamos</span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay datos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('books.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>Agregar Libro
                        </a>
                        <a href="{{ route('loans.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-handshake me-2"></i>Nuevo Préstamo
                        </a>
                        <a href="{{ route('reports.overdue') }}" class="btn btn-outline-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Préstamos Vencidos
                        </a>
                        <a href="{{ route('reports.most-borrowed') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-2"></i>Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 