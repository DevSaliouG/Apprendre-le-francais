@extends('layouts.app')

@section('title', 'Modifier la Leçon')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3>Modifier la leçon: {{ $lesson->title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="level_id" class="form-label">Niveau associé</label>
                    <select class="form-select" id="level_id" name="level_id" required>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ $lesson->level_id == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type de leçon</label>
                    <select class="form-select" id="type" name="type" required>
                        @foreach (App\Models\Lesson::TYPES as $type)
                            <option value="{{ $type }}" {{ old('type', $lesson->type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la leçon</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title', $lesson->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Contenu de la leçon</label>
                    <textarea class="form-control" id="content" name="content" rows="8" required>{{ old('content', $lesson->content) }}</textarea>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.lessons.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt me-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialiser un éditeur de texte si nécessaire (ex: TinyMCE)
                // tinymce.init({ selector: '#content' });
            });
        </script>
    @endpush
@endsection
