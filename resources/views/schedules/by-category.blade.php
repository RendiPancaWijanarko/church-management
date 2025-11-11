@extends('layouts.app')

@section('title', 'Jadwal Per Kategori')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2 class="mb-0">
                    <i class="bi bi-collection-fill me-2"></i>Jadwal Per Kategori
                </h2>
                <button type="button" class="btn btn-success" onclick="exportPDF()">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('schedules.by-category') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="month" class="form-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select name="year" class="form-select">
                            @for ($y = now()->year - 1; $y <= now()->year + 2; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sesi</label>
                        <select name="session" class="form-select">
                            <option value="">Semua Sesi</option>
                            <option value="KU1" {{ $session == 'KU1' ? 'selected' : '' }}>KU1</option>
                            <option value="KU2" {{ $session == 'KU2' ? 'selected' : '' }}>KU2</option>
                            <option value="KU3" {{ $session == 'KU3' ? 'selected' : '' }}>KU3</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Grouped by Category -->
    @php
        $monthName = \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F');
    @endphp

    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Periode:</strong> {{ $monthName }} {{ $year }}
        @if ($session)
            - <strong>Sesi:</strong> {{ $session }}
        @endif
    </div>

    @forelse($schedules as $categoryId => $categorySchedules)
        @php
            $category = $categories->find($categoryId);
        @endphp

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-tag-fill me-2"></i>{{ $category->name }}
                    <span class="badge bg-light text-dark ms-2">{{ $categorySchedules->count() }} Jadwal</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Tanggal</th>
                                <th width="15%">Sesi</th>
                                <th width="25%">Pelayan</th>
                                <th width="10%">Waktu</th>
                                <th width="20%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorySchedules as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $schedule->formatted_date }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $schedule->service_session }}</span>
                                    </td>
                                    <td><strong>{{ $schedule->servant->name }}</strong></td>
                                    <td>
                                        @if ($schedule->service_time)
                                            {{ \Carbon\Carbon::parse($schedule->service_time)->format('H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $schedule->notes ?? '-' }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Tidak ada jadwal untuk periode ini
        </div>
    @endforelse
@endsection

@push('scripts')
    <script>
        function exportPDF() {
            const form = document.getElementById('filterForm');
            const url = new URL('{{ route('schedules.export-pdf') }}', window.location.origin);

            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                url.searchParams.append(key, value);
            }

            window.open(url.toString(), '_blank');
        }
    </script>
@endpush
