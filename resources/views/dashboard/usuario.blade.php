@extends('layouts.app')

@section('title', 'Dashboard - Biblioteca Virtual')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>
                Mi Dashboard
            </h1>
            <p class="text-muted">Bienvenido a la biblioteca virtual</p>
        </div>
        <div>
            <a href="{{ route('books.index') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Buscar Libros
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mis Préstamos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Préstamos Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeUserLoans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Préstamos Vencidos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $overdueUserLoans }}</div>
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
        <!-- My Recent Loans -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Mis Préstamos Recientes
                    </h6>
                    <a href="{{ route('loans.history') }}" class="btn btn-sm btn-primary">
                        Ver Historial
                    </a>
                </div>
                <div class="card-body">
                    @if($recentUserLoans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Libro</th>
                                        <th>Fecha Préstamo</th>
                                        <th>Fecha Devolución</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUserLoans as $loan)
                                    <tr>
                                        <td>
                                            <strong>{{ $loan->book->title }}</strong><br>
                                            <small class="text-muted">{{ $loan->book->author }}</small>
                                        </td>
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
                                        <td>
                                            @if($loan->status === 'active')
                                                <a href="{{ route('loans.return-form', $loan->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-undo me-1"></i>Devolver
                                                </a>
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
                            <p class="text-muted">No tienes préstamos recientes</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Buscar Libros
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Books -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-book me-2"></i>Libros Disponibles
                    </h6>
                </div>
                <div class="card-body">
                    @if($availableBooks->count() > 0)
                        @foreach($availableBooks as $book)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $book->title }}</h6>
                                <small class="text-muted">{{ $book->author }}</small>
                            </div>
                            <span class="badge bg-success">{{ $book->available_quantity }} disponibles</span>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-sm">
                                Ver Todos los Libros
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No hay libros disponibles</p>
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
                        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i>Buscar Libros
                        </a>
                        <a href="{{ route('loans.history') }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-2"></i>Mi Historial
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-2"></i>Mi Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 