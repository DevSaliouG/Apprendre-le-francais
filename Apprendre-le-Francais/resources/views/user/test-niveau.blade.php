@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Question {{ $current_index }} sur {{ $question_count }}
                    <div class="float-end">{{ $progress }}% complété</div>
                </div>

                <div class="card-body">
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $progress }}%;" 
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>

                    <h5 class="card-title mb-4">{{ $question->texte }}</h5>

                    @if ($question->exercise->type === 'écrit')
                        <form method="POST" action="{{ route('test.submitAnswer') }}">
                            @csrf
                            <div class="form-group">
                                @foreach ($question->clean_choix as $key => $option)
                                    <div class="form-check my-2">
                                        <input class="form-check-input" type="radio" name="reponse"
                                            id="option{{ $key }}" value="{{ $option }}" required>
                                        <label class="form-check-label" for="option{{ $key }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-right me-1"></i> Suivant
                            </button>
                        </form>
                    @else
                        <div class="mb-4">
                            @if($question->fichier_audio)
                                <audio controls class="w-100">
                                    <source src="{{ asset('storage/' . $question->fichier_audio) }}" type="audio/mpeg">
                                    Votre navigateur ne supporte pas l'élément audio.
                                </audio>
                            @else
                                <p class="text-muted">Aucun audio disponible</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('test.submitAnswer') }}">
                            @csrf
                            <div class="form-group">
                                <label for="reponse">Votre réponse :</label>
                                <input type="text" class="form-control" name="reponse" id="reponse" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-right me-1"></i> Suivant
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection