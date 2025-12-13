@extends('layouts.app')

@section('title', 'Buat Reservasi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Buat Reservasi Meja
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.reservations.store') }}" method="POST" id="reservationForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Personal
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-calendar-check me-2"></i>Detail Reservasi
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="guests" class="form-label">Jumlah Tamu <span class="text-danger">*</span></label>
                                    <select class="form-select @error('guests') is-invalid @enderror" id="guests" name="guests" required>
                                        <option value="">Pilih jumlah tamu</option>
                                        @for($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>{{ $i }} orang</option>
                                        @endfor
                                    </select>
                                    @error('guests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                           id="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="time" class="form-label">Waktu <span class="text-danger">*</span></label>
                                    <select class="form-select @error('time') is-invalid @enderror" id="time" name="time" required>
                                        <option value="">Pilih waktu</option>
                                        @for($hour = 11; $hour <= 21; $hour++)
                                            @for($minute = 0; $minute < 60; $minute += 30)
                                                @php
                                                    $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                                    $timeDisplay = date('H:i', strtotime($timeValue));
                                                @endphp
                                                <option value="{{ $timeValue }}" {{ old('time') == $timeValue ? 'selected' : '' }}>
                                                    {{ $timeDisplay }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Table Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-chair me-2"></i>Pilih Meja <span class="text-danger">*</span>
                                </h5>
                                @if($availableTables->count() > 0)
                                    <div class="row" id="tableSelection">
                                        @foreach($availableTables as $table)
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="card table-card {{ old('table_id') == $table->id ? 'border-primary' : '' }}"
                                                     data-capacity="{{ $table->capacity }}">
                                                    <div class="card-body text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input table-radio" type="radio"
                                                                   name="table_id" id="table{{ $table->id }}"
                                                                   value="{{ $table->id }}"
                                                                   {{ old('table_id') == $table->id ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="table{{ $table->id }}">
                                                                <h5 class="card-title mb-2">Meja {{ $table->table_number }}</h5>
                                                                <p class="card-text text-muted mb-1">
                                                                    <i class="fas fa-users me-1"></i>{{ $table->capacity }} orang
                                                                </p>
                                                                <small class="text-muted">{{ $table->location }}</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('table_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Maaf, tidak ada meja yang tersedia saat ini.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan Tambahan</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"
                                              placeholder="Permintaan khusus, alergi makanan, dll.">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('customer.reservations.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-calendar-check me-2"></i>Buat Reservasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.table-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.table-card.border-primary {
    border-width: 2px;
}

.table-radio {
    display: none;
}

.table-radio:checked + label .card {
    border-color: #007bff;
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const guestsSelect = document.getElementById('guests');
    const tableCards = document.querySelectorAll('.table-card');

    // Filter tables based on guest count
    guestsSelect.addEventListener('change', function() {
        const guestCount = parseInt(this.value);

        tableCards.forEach(card => {
            const capacity = parseInt(card.dataset.capacity);
            if (guestCount > capacity) {
                card.style.opacity = '0.5';
                card.style.pointerEvents = 'none';
            } else {
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
            }
        });
    });

    // Table selection visual feedback
    document.querySelectorAll('.table-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.table-card').forEach(card => {
                card.classList.remove('border-primary');
            });
            if (this.checked) {
                this.closest('.table-card').classList.add('border-primary');
            }
        });
    });

    // Form validation
    const form = document.getElementById('reservationForm');
    form.addEventListener('submit', function(e) {
        const tableSelected = document.querySelector('input[name="table_id"]:checked');
        if (!tableSelected) {
            e.preventDefault();
            alert('Silakan pilih meja terlebih dahulu.');
            return false;
        }
    });
});
</script>
@endpush
@endsection
