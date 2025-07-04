@extends('layouts.app')

@section('title', 'Gestion des Questions')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 fw-bold">Gestion des questions</h1>
            <a href="{{ route('admin.exercises.index') }}" class="btn btn-outline-secondary">
                &larr; Retour aux exercices
            </a>
        </div>

        <!-- Barre de recherche et filtres -->
            <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.questions.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par texte..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <select name="exercise_id" class="form-select">
                        <option value="">Tous les exercices</option>
                        @foreach ($exercises as $id => $label)
                            <option value="{{ $id }}" {{ request('exercise_id') == $id ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="format" class="form-select">
                        <option value="">Tous les formats</option>
                        @foreach ($formats as $key => $value)
                            <option value="{{ $key }}" {{ request('format') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Exercice</th>
                        <th>Format</th>
                        <th>Question</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr>
                            <td>{{ $question->id }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    Ex #{{ $question->exercise_id }}
                                </span>
                            </td>
                            <td>
                                @if ($question->format_reponse === 'choix_multiple')
                                    <span class="badge bg-primary">Choix</span>
                                @elseif($question->format_reponse === 'texte_libre')
                                    <span class="badge bg-info">Texte</span>
                                @else
                                    <span class="badge bg-warning">Audio</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($question->texte, 70) }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.questions.show', $question) }}"
                                    class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.questions.edit', $question) }}"
                                    class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-5">
                <ul class="pagination shadow-sm rounded-pill">
                    {{-- Lien précédent --}}
                    @if ($questions->onFirstPage())
                        <li class="page-item disabled"><span class="page-link rounded-pill">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $questions->previousPageUrl() }}">&laquo;</a></li>
                    @endif

                    {{-- Pages --}}
                    @foreach ($questions->getUrlRange(1, $questions->lastPage()) as $page => $url)
                        <li class="page-item {{ $questions->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link rounded-pill" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Lien suivant --}}
                    @if ($questions->hasMorePages())
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $questions->nextPageUrl() }}">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link rounded-pill">&raquo;</span></li>
                    @endif
                </ul>
            </div>
    </div>
    </div>
@endsection
