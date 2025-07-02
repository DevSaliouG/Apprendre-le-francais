@if(auth()->user()->learningStreak->last_activity_date !== now()->format('Y-m-d'))
<div class="modal fade" id="streakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="streak-fire mx-auto">
                        <i class="fas fa-fire text-warning"></i>
                        <span class="streak-count">{{ auth()->user()->learningStreak->current_streak + 1 }}</span>
                    </div>
                </div>
                <h4 class="mb-3">Maintenez votre série!</h4>
                <p class="text-muted mb-4">
                    Complétez une activité aujourd'hui pour gagner 
                    <strong>{{ min(100, 10 + ((auth()->user()->learningStreak->current_streak + 1) * 2) )}} points</strong>
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Plus tard
                    </button>
                    <a href="{{ route('lessons.index') }}" class="btn btn-warning">
                        <i class="fas fa-play me-2"></i> Commencer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var streakModal = new bootstrap.Modal(document.getElementById('streakModal'));
        streakModal.show();
    });
</script>
@endpush

@endif