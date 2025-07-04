@extends('layouts.app')

@section('title', 'Créer une Question')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Ajouter une question</h1>
        <a href="{{ route('admin.exercises.show', $exercise) }}" class="btn btn-outline-secondary">
            &larr; Annuler
        </a>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p class="text-muted mb-4">Exercice parent: #{{ $exercise->id }} - {{ $exercise->lesson->title }}</p>
            
            <form action="{{ route('admin.exercises.questions.store', $exercise) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Formulaire partagé -->
                @include('admin.questions._form')
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        Ajouter la question
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les sections pour la création
        const formatSelect = document.getElementById('format_reponse');
        const optionsSection = document.getElementById('options-section');
        const texteSection = document.getElementById('texte-section');
        const audioSection = document.getElementById('audio-section');

        function toggleSections() {
            const selectedFormat = formatSelect.value;

            optionsSection.classList.add('d-none');
            texteSection.classList.add('d-none');
            audioSection.classList.add('d-none');

            if (selectedFormat === 'choix_multiple') {
                optionsSection.classList.remove('d-none');
            } else if (selectedFormat === 'texte_libre') {
                texteSection.classList.remove('d-none');
            } else if (selectedFormat === 'audio') {
                audioSection.classList.remove('d-none');
            }
        }

        formatSelect.addEventListener('change', toggleSections);
        toggleSections();
    });
</script>
@endpush