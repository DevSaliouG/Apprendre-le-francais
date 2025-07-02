@extends('layouts.app')

@section('content')
<div class="main-content">
    <div>
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Détails de l'exercice</h1>
                <div class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('admin.exercises.index') }}" class="text-primary hover:text-primary-light flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour aux exercices
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte de l'exercice -->
        <div class="card mb-8">
            <div class="card-body">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold mb-4 text-primary">{{ $exercise->lesson->title }}</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-32 text-gray-600 font-medium">Type:</div>
                                <div class="px-4 py-1 bg-primary-light bg-opacity-10 rounded-full text-primary font-medium">
                                    {{ $exercise->type }}
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-32 text-gray-600 font-medium">Difficulté:</div>
                                <div class="flex items-center">
                                    @for($i = 0; $i < $exercise->difficulty; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                    @for($i = $exercise->difficulty; $i < 5; $i++)
                                        <i class="far fa-star text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des questions -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4" style="margin-bottom: 30px">
                <h2 class="text-xl font-bold text-gray-800"  style="margin-bottom: 15px">Questions associées</h2>
                <a href="{{ route('admin.exercises.questions.create', $exercise) }}" 
                   class="btn-primary-lg flex items-center self-start md:self-auto transition-transform hover:scale-[1.02]">
                    <i class="fas fa-plus mr-2"></i> Ajouter une question
                </a>
            </div>
            
            @if($exercise->questions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($exercise->questions as $question)
                <div class="card transition-all duration-300 hover:shadow-lg hover:border-primary-light group" style="margin-bottom: 70px">
                    <div class="card-body flex flex-col h-full">
                        <!-- En-tête de la carte -->
                        <div class="flex justify-between items-start mb-4">
                         <div class="px-3 py-1 rounded-full text-sm font-medium">
    Question #{{ $loop->iteration }}
</div>              </div>
                        
                        <!-- Contenu de la question -->
                        <div class="flex-grow flex flex-col items-center text-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 transition-colors group-hover:text-primary">
                                {{ Str::limit($question->texte, 70) }}
                            </h3>
                            
                            <div class="text-sm text-gray-500 mb-3 flex items-center">
                                <i class="fas fa-list-ul mr-2"></i>
                                {{ count($question->clean_choix) }} options
                            </div>
                            
                           
                        </div>
                        
                        <!-- Actions -->
                       <div class="flex justify-center space-x-3" style="margin-left: 40%">
    <a href="{{ route('admin.questions.show', $question) }}" 
       class="btn-view flex items-center px-4 py-2 rounded-full transition-all"
       style="background: linear-gradient(135deg, #7CE0D6, #5BC0DE); 
              color: white;
              box-shadow: 0 4px 10px rgba(92, 225, 230, 0.3);">
        <i class="fas fa-eye mr-2"></i> Voir
    </a>
    <a href="{{ route('admin.questions.edit', $question) }}" 
       class="btn-edit flex items-center px-4 py-2 rounded-full transition-all"
       style="background: linear-gradient(135deg, #FFD166, #FFB84D); 
              color: #8C5E19;
              box-shadow: 0 4px 10px rgba(255, 184, 77, 0.3);">
        <i class="fas fa-edit mr-2"></i> Modifier
    </a>
</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-8">
                        <div class="mx-auto w-16 h-16 rounded-full bg-primary bg-opacity-10 flex items-center justify-center mb-4">
                            <i class="fas fa-question-circle text-primary text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune question trouvée</h3>
                        <p class="text-gray-500 mb-4">Commencez par ajouter des questions à cet exercice</p>
                        <a href="{{ route('admin.exercises.questions.create', $exercise) }}" 
                           class="btn-primary-lg inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i> Créer votre première question
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Boutons avec meilleur contraste et conformité WCAG AA */
    .btn-view {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 2px 6px rgba(108, 99, 255, 0.25);
    }
    
    .btn-view:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 99, 255, 0.3);
    }
    
    .btn-edit {
        background-color: var(--secondary);
        color: white;
        box-shadow: 0 2px 6px rgba(255, 101, 132, 0.25);
    }
    
    .btn-edit:hover {
        background-color: var(--secondary-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 101, 132, 0.3);
    }
    
    /* Animations subtiles */
    .card {
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(46, 42, 71, 0.1);
    }
    
    /* Uniformisation des vignettes */
    .card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    /* Disposition responsive */
    @media (max-width: 768px) {
        .grid-cols-1 {
            grid-template-columns: 1fr;
        }
        
        .card {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endsection