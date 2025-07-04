@extends('layouts.app')

@section('title', 'Gestion des Leçons')

@section('content')
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Gestion des Leçons</h3>
                <a href="{{ route('admin.lessons.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus-circle me-2"></i> Nouvelle leçon
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card-body bg-light border-bottom">
            <form action="{{ route('admin.lessons.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Rechercher par titre..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="level" class="form-select">
                        <option value="">Tous les niveaux</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="grammar" {{ request('type') == 'grammar' ? 'selected' : '' }}>Grammaire</option>
                        <option value="vocabulary" {{ request('type') == 'vocabulary' ? 'selected' : '' }}>Vocabulaire</option>
                        <option value="pronunciation" {{ request('type') == 'pronunciation' ? 'selected' : '' }}>Prononciation</option>
                        <option value="culture" {{ request('type') == 'culture' ? 'selected' : '' }}>Culture</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Niveau</th>
                            <th>Type</th>
                            <th>Créé le</th>
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
                                    <span class="badge bg-secondary text-capitalize">
                                        {{ $lesson->type }}
                                    </span>
                                </td>
                                <td>{{ $lesson->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-book-open fa-2x mb-2"></i><br>
                                    Aucune leçon trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                <ul class="pagination shadow-sm rounded-pill">
                    {{-- Lien précédent --}}
                    @if ($lessons ->onFirstPage())
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
