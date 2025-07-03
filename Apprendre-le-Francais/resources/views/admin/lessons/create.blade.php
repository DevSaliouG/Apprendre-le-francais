@extends('layouts.app')

@section('title', 'Créer une Leçon')

@section('content')
    <div class="content-card">
        <div class="card-header">
            <h3>Créer une nouvelle leçon</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lessons.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="level_id" class="form-label">Niveau associé</label>
                    <select class="form-select" id="level_id" name="level_id" required>
                        <option value="" disabled selected>Sélectionnez un niveau</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type de leçon</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" disabled selected>Sélectionnez un type</option>
                        @foreach (App\Models\Lesson::TYPES as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
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
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}"
                        placeholder="Ex: Les verbes du premier groupe" required>
                    @error('title')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Contenu de la leçon</label>
                    <textarea class="form-control" id="content" name="content" rows="8"
                        placeholder="Rédigez le contenu de la leçon..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.lessons.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
