@extends('layouts.app')

@section('title', 'Gestion des Exercices')

@section('content')
<div class="container py-5">
    <!-- En-tête -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-primary mb-1">Gestion des exercices</h1>
            <p class="text-muted mb-0">Liste complète des exercices disponibles</p>
        </div>
        <a href="{{ route('admin.exercises.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nouvel exercice
        </a>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.exercises.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par leçon..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <select name="lesson_id" class="form-select">
                        <option value="">Toutes les leçons</option>
                        @foreach ($lessons as $id => $title)
                            <option value="{{ $id }}" {{ request('lesson_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="choix_multiple" {{ request('type') === 'choix_multiple' ? 'selected' : '' }}>Choix multiple</option>
                        <option value="texte_libre" {{ request('type') === 'texte_libre' ? 'selected' : '' }}>Texte libre</option>
                        <option value="vrai_faux" {{ request('type') === 'vrai_faux' ? 'selected' : '' }}>Vrai/Faux</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Leçon</th>
                        <th>Niveau</th>
                        <th>Type</th>
                        <th>Difficulté</th>
                        <th>Créé le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exercises as $exercise)
                        <tr>
                            <td>#{{ $exercise->id }}</td>
                            <td>{{ $exercise->lesson->title }}</td>
                            <td>
                                @if ($exercise->lesson->level)
                                    <span class="badge bg-secondary text-uppercase">{{ $exercise->lesson->level->name }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    {{ $exercise->type === 'écrit' ? 'bg-primary' : '' }}
                                    {{ $exercise->type === 'oral' ? 'bg-success' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($exercise->type)) }}
                                </span>
                            </td>
                            <td>
                                @for ($i = 0; $i < $exercise->difficulty; $i++)
                                    <i class="fas fa-star text-warning"></i>
                                @endfor
                                @for ($i = $exercise->difficulty; $i < 5; $i++)
                                    <i class="far fa-star text-muted"></i>
                                @endfor
                            </td>
                            <td>{{ $exercise->created_at->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.exercises.show', $exercise) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.exercises.edit', $exercise) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.exercises.destroy', $exercise) }}" method="POST" onsubmit="return confirm('Supprimer cet exercice ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucun exercice trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

                   <div class="d-flex justify-content-center mt-5">
                <ul class="pagination shadow-sm rounded-pill">
                    {{-- Lien précédent --}}
                    @if ($exercises ->onFirstPage())
                        <li class="page-item disabled"><span class="page-link rounded-pill">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $exercises->previousPageUrl() }}">&laquo;</a></li>
                    @endif

                    {{-- Pages --}}
                    @foreach ($exercises->getUrlRange(1, $exercises->lastPage()) as $page => $url)
                        <li class="page-item {{ $exercises->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link rounded-pill" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Lien suivant --}}
                    @if ($exercises->hasMorePages())
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $exercises->nextPageUrl() }}">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link rounded-pill">&raquo;</span></li>
                    @endif
                </ul>
            </div>
    </div>
</div>
@endsection
