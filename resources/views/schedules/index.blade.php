@extends('layouts.app')

@section('title', 'Jadwal Pelayanan')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2 class="mb-0">
                    <i class="bi bi-calendar-check-fill me-2"></i>Jadwal Pelayanan
                </h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Jadwal
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportPDF()">
                        <i class="bi bi-file-pdf me-1"></i>Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('schedules.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Bulan</label>
                        <select name="month" class="form-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select name="year" class="form-select">
                            @for ($y = now()->year - 1; $y <= now()->year + 2; $y++)
                                <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sesi</label>
                        <select name="session" class="form-select">
                            <option value="">Semua Sesi</option>
                            <option value="KU1" {{ request('session') == 'KU1' ? 'selected' : '' }}>KU1</option>
                            <option value="KU2" {{ request('session') == 'KU2' ? 'selected' : '' }}>KU2</option>
                            <option value="KU3" {{ request('session') == 'KU3' ? 'selected' : '' }}>KU3</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Sesi</th>
                            <th>Kategori</th>
                            <th>Pelayan</th>
                            <th>Waktu</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->formatted_date }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $schedule->service_session }}</span>
                                </td>
                                <td>{{ $schedule->category->name }}</td>
                                <td>
                                    <strong>{{ $schedule->servant->name }}</strong>
                                </td>
                                <td>
                                    @if ($schedule->service_time)
                                        {{ \Carbon\Carbon::parse($schedule->service_time)->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($schedule->notes), 30) }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Tidak ada jadwal untuk periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportPDF() {
            const form = document.getElementById('filterForm');
            const url = new URL('{{ route('schedules.export-pdf') }}', window.location.origin);

            // Ambil nilai filter
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                url.searchParams.append(key, value);
            }

            window.open(url.toString(), '_blank');
        }
    </script>
@endpush
