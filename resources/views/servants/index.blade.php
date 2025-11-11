@extends('layouts.app')

@section('title', 'Daftar Pelayan')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2 class="mb-0"><i class="bi bi-people-fill me-2"></i>Daftar Pelayan</h2>
                <a href="{{ route('servants.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Pelayan
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('servants.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama pelayan..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Servants Grid -->
    <div class="row g-4">
        @forelse($servants as $servant)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $servant->name }}</h5>
                            <span class="badge {{ $servant->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $servant->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>

                        <p class="text-muted mb-2">
                            <i class="bi bi-tag-fill me-2"></i>{{ $servant->category->name }}
                        </p>

                        @if ($servant->phone)
                            <p class="mb-2">
                                <i class="bi bi-telephone-fill me-2"></i>{{ $servant->phone }}
                            </p>
                        @endif

                        @if ($servant->email)
                            <p class="mb-2">
                                <i class="bi bi-envelope-fill me-2"></i>{{ $servant->email }}
                            </p>
                        @endif

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('servants.show', $servant) }}"
                                class="btn btn-sm btn-info text-white flex-fill">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('servants.edit', $servant) }}" class="btn btn-sm btn-warning flex-fill">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('servants.destroy', $servant) }}" method="POST" class="flex-fill"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>Tidak ada data pelayan.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $servants->links() }}
    </div>
@endsection
