@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h2 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Daftar Kategori</h2>
                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $category->name }}</h5>
                            <span class="badge bg-info">{{ $category->servants_count }} Pelayan</span>
                        </div>

                        <p class="text-muted">{{ $category->description ?? 'Tidak ada deskripsi' }}</p>

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning flex-fill">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-fill"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua pelayan dalam kategori ini akan ikut terhapus.')">
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
                    <i class="bi bi-info-circle me-2"></i>Tidak ada kategori.
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
@endsection
