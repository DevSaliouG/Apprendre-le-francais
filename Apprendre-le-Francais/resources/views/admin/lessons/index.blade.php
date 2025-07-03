@extends('layouts.app')

@section('title', 'Gestion des Leçons')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Liste des Leçons</h3>
                <a href="{{ route('admin.lessons.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-2"></i> Nouvelle leçon
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Niveau</th>
                            <th>Type</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->id }}</td>
                                <td>{{ $lesson->title }}</td>
                                <td>{{ $lesson->level->name }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst($lesson->type) }}
                                    </span>
                                </td>
                                <td>{{ $lesson->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                            class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-book-open fa-2x mb-3"></i>
                                    <p>Aucune leçon disponible</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <ul class="pagination shadow-sm rounded-pill">
                    {{-- Lien précédent --}}
                    @if ($lessons->onFirstPage())
                        <li class="page-item disabled"><span class="page-link rounded-pill">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $lessons->previousPageUrl() }}">&laquo;</a></li>
                    @endif

                    {{-- Pages --}}
                    @foreach ($lessons->getUrlRange(1, $lessons->lastPage()) as $page => $url)
                        <li class="page-item {{ $lessons->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link rounded-pill" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Lien suivant --}}
                    @if ($lessons->hasMorePages())
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $lessons->nextPageUrl() }}">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link rounded-pill">&raquo;</span></li>
                    @endif
                </ul>
            </div>

        </div>
    </div>
@endsection
