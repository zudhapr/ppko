@extends('layouts.app')

@section('title', 'Master Profil Fertigasi')

@section('content')

<div class="page-header">
    <h1>Master Profil Fertigasi</h1>
    <p>Kelola profil fertigasi yang digunakan pada jadwal tanaman.</p>
</div>

<div class="section">

    <h2>Tambah Profil</h2>

    <form method="POST"
          action="{{ route('profiles.store') }}">

        @csrf

        <div class="form-grid">

            <div>
                <label>Nama Profil</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Contoh: Melon Standar"
                    required
                >
            </div>

            <div>
                <label>Deskripsi</label>

                <input
                    type="text"
                    name="description"
                    placeholder="Keterangan profil"
                >
            </div>

            <div>
                <button
                    class="btn btn-primary"
                    type="submit">

                    + Tambah Profil
                </button>
            </div>

        </div>

    </form>

</div>


<div class="section">

    <h2>Daftar Profil</h2>

    <div class="schedule-table-wrapper">

        <table class="schedule-table">

            <thead>
                <tr>
                    <th>Nama Profil</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($profiles as $profile)

                <tr>

                    <td>
                        <strong>
                            {{ $profile->name }}
                        </strong>
                    </td>

                    <td>
                        {{ $profile->description ?? '-' }}
                    </td>

                    <td>

                        @if($profile->is_active)

                            <span class="badge active">
                                Aktif
                            </span>

                        @else

                            <span class="badge inactive">
                                Nonaktif
                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="actions">

                            <form
                                method="POST"
                                action="{{ route(
                                    'profiles.toggle',
                                    $profile
                                ) }}">

                                @csrf
                                @method('PATCH')

                                <button
                                    class="btn btn-success"
                                    type="submit">

                                    {{ $profile->is_active
                                        ? 'Nonaktifkan'
                                        : 'Aktifkan'
                                    }}

                                </button>

                            </form>

                            <form
                                method="POST"
                                action="{{ route(
                                    'profiles.destroy',
                                    $profile
                                ) }}"
                                onsubmit="return confirm(
                                    'Yakin menghapus profil ini?'
                                )">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger"
                                    type="submit">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        Belum ada data profil.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection