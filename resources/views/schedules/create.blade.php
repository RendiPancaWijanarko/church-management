@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Jadwal Pelayanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('schedules.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="service_date" class="form-label">
                                Tanggal Pelayanan <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('service_date') is-invalid @enderror"
                                id="service_date" name="service_date" value="{{ old('service_date') }}" required>
                            @error('service_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="service_session" class="form-label">
                                Sesi Ibadah <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('service_session') is-invalid @enderror" id="service_session"
                                name="service_session" required>
                                <option value="">Pilih Sesi</option>
                                <option value="KU1" {{ old('service_session') == 'KU1' ? 'selected' : '' }}>
                                    Ibadah Minggu Sesi 1 (KU1)
                                </option>
                                <option value="KU2" {{ old('service_session') == 'KU2' ? 'selected' : '' }}>
                                    Ibadah Minggu Sesi 2 (KU2)
                                </option>
                                <option value="KU3" {{ old('service_session') == 'KU3' ? 'selected' : '' }}>
                                    Ibadah Minggu Sesi 3 (KU3)
                                </option>
                            </select>
                            @error('service_session')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="service_time" class="form-label">Waktu Ibadah</label>
                            <input type="time" class="form-control @error('service_time') is-invalid @enderror"
                                id="service_time" name="service_time" value="{{ old('service_time') }}">
                            <small class="text-muted">Contoh: 08:00 untuk KU1, 10:00 untuk KU2</small>
                            @error('service_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="servant_id" class="form-label">
                                Pelayan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('servant_id') is-invalid @enderror" id="servant_id"
                                name="servant_id" required>
                                <option value="">Pilih Pelayan</option>
                                @foreach ($servants as $servant)
                                    <option value="{{ $servant->id }}" data-category="{{ $servant->category_id }}"
                                        {{ old('servant_id') == $servant->id ? 'selected' : '' }}>
                                        {{ $servant->name }} ({{ $servant->category->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('servant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan
                            </button>
                            <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Filter pelayan berdasarkan kategori yang dipilih
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const servantSelect = document.getElementById('servant_id');
            const options = servantSelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }

                const servantCategory = option.getAttribute('data-category');
                if (categoryId === '' || servantCategory === categoryId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            servantSelect.value = '';
        });
    </script>
@endpush
