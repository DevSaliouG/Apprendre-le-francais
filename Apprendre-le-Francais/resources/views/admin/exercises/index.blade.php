@extends('layouts.app')

@section('title', 'Gestion des Exercices')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Gestion des exercices</h1>
            <p class="text-gray-600">Liste complète des exercices disponibles</p>
        </div>
        <a href="{{ route('admin.exercises.create') }}" 
           class="btn-primary flex items-center self-start md:self-auto px-4 py-2 rounded-full transition-all">
            <i class="fas fa-plus mr-2"></i> Nouvel exercice
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Leçon</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulté</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($exercises as $exercise)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $exercise->id }}
                            </td>
                            <!-- Titre de l'exercice -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800">{{ $exercise->title }}</div>
                            </td>
                            <!-- Leçon -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800">{{ $exercise->lesson->title }}</div>
                            </td>
                            <!-- Niveau de la leçon -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($exercise->lesson->level)
                                    <span class="px-2 inline-flex text-xs font-semibold leading-5 rounded-full bg-gray-100 text-gray-800">
                                        {{ strtoupper($exercise->lesson->level->name) }}
                                    </span>
                                @endif
                            </td>
                            <!-- Type -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm font-medium rounded-full
                                    {{ $exercise->type === 'choix_multiple' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $exercise->type === 'texte_libre' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $exercise->type === 'vrai_faux' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($exercise->type)) }}
                                </span>
                            </td>
                            <!-- Difficulté -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @for($i = 0; $i < $exercise->difficulty; $i++)
                                        <i class="fas fa-star text-sm text-yellow-500"></i>
                                    @endfor
                                    @for($i = $exercise->difficulty; $i < 5; $i++)
                                        <i class="far fa-star text-sm text-gray-300"></i>
                                    @endfor
                                </div>
                            </td>
                            <!-- Créé le -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $exercise->created_at->format('d/m/Y') }}
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.exercises.show', $exercise) }}" 
                                       class="btn-view flex items-center px-3 py-1 rounded-full transition-all">
                                        <i class="fas fa-eye mr-1"></i>
                                    </a>
                                    <a href="{{ route('admin.exercises.edit', $exercise) }}" 
                                       class="btn-edit flex items-center px-3 py-1 rounded-full transition-all">
                                        <i class="fas fa-edit mr-1"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                @if ($exercises->hasPages())
                    {{ $exercises->onEachSide(1)->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>
</div>
<style>
    /* Boutons principaux */
.btn-primary {
  background: linear-gradient(135deg, #6C63FF, #4A42D6);
  color: #ffffff;
  box-shadow: 0 4px 10px rgba(108, 99, 255, 0.3);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(108, 99, 255, 0.4);
}

/* Bouton Voir (œil) */
.btn-view {
  background: linear-gradient(135deg, #7CE0D6, #5BC0DE);
  color: #ffffff;
  box-shadow: 0 4px 10px rgba(92, 225, 230, 0.3);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-view:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(92, 225, 230, 0.4);
}

/* Bouton Modifier (crayon) */
.btn-edit {
  background: linear-gradient(135deg, #FFD166, #FFB84D);
  color: #8C5E19;
  box-shadow: 0 4px 10px rgba(255, 184, 77, 0.3);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn-edit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(255, 184, 77, 0.4);
}

/* Carte englobante */
.card {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(46, 42, 71, 0.1);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

/* Corps de la carte */
.card-body {
  padding: 0;
  flex: 1;
}

/* Tableaux */
table {
  width: 100%;
  table-layout: fixed;
  border-collapse: collapse;
}

thead {
  background-color: #F9FAFB;
}

th, td {
  padding: 0.75rem 1.5rem;
  text-align: left;
  vertical-align: middle;
}

tbody tr:hover {
  background-color: #F3F4F6;
  transition: background-color 0.2s ease;
}

/* Badges de niveau et de type */
.inline-flex {
  display: inline-flex;
}

.text-xs {
  font-size: 0.75rem;
}

.leading-5 {
  line-height: 1.25rem;
}

.rounded-full {
  border-radius: 9999px;
}

.bg-gray-100 {
  background-color: #F3F4F6;
}

.text-gray-800 {
  color: #1F2937;
}

.bg-blue-100 {
  background-color: #DBEAFE;
}

.text-blue-800 {
  color: #1E40AF;
}

.bg-green-100 {
  background-color: #D1FAE5;
}

.text-green-800 {
  color: #065F46;
}

.bg-yellow-100 {
  background-color: #FEF9C3;
}

.text-yellow-800 {
  color: #78350F;
}

/* Pagination (Bootstrap 5 override) */
.pagination .page-item .page-link {
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin: 0 4px;
  font-weight: 500;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.pagination .page-item .page-link {
  color: #374151;
  background-color: #F3F4F6;
}

.pagination .page-item .page-link:hover {
  background-color: #6C63FF;
  color: #ffffff;
}

.pagination .page-item.active .page-link {
  background-color: #6C63FF;
  color: #ffffff;
}

    </style>
@endsection
