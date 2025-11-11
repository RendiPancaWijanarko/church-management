@extends('layouts.app')

@section('title', 'Detail Pelayan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Detail Pelayan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="30%">Nama Lengkap</th>
                                <td>: {{ $servant->name }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>: <span class="badge bg-primary">{{ $servant->category->name }}</span></td>
                            </tr>
                            <tr>
                                <th>Nomor Telepon</th>
                                <td>: {{ $servant->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $servant->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $servant->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>:
                                    <span class="badge {{ $servant->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $servant->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat pada</th>
                                <td>: {{ $servant->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir diupdate</th>
                                <td>: {{ $servant->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('servants.edit', $servant) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a href="{{ route('servants.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <form action="{{ route('servants.destroy', $servant) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
