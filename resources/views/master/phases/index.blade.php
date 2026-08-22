@extends('layouts.app')

@section('title', 'Master Fase Pertumbuhan')

@section('content')

<div class="page-header">

    <h1>Master Fase Pertumbuhan</h1>

    <p>
        Kelola fase pertumbuhan tanaman yang
        digunakan pada jadwal fertigasi.
    </p>

</div>


{{-- TAMBAH FASE --}}

<div class="section">

    <h2>Tambah Fase</h2>

    <form
        method="POST"
        action="{{ route('phases.store') }}"
    >

        @csrf

        <div class="form-grid">

            <div>

                <label>
                    Nama Fase
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Penyerbukan"
                    required
                >

            </div>


            <div>

                <label>
                    Keterangan
                </label>

                <input
                    type="text"
                    name="description"
                    value="{{ old('description') }}"
                    placeholder="Contoh: Masa pembungaan dan penyerbukan"
                >

            </div>


            <div>

                <button
                    class="btn btn-primary"
                    type="submit"
                >

                    + Tambah Fase

                </button>

            </div>

        </div>

    </form>

</div>


{{-- DAFTAR FASE --}}

<div class="section">

    <h2>Daftar Fase</h2>

    <div class="schedule-table-wrapper">

        <table class="schedule-table">

            <thead>

            <tr>
                <th>Nama Fase</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th width="220">Aksi</th>
            </tr>

            </thead>


            <tbody>

            @forelse($phases as $phase)

                <tr>

                    <td>

                        <strong>
                            {{ $phase->name }}
                        </strong>

                    </td>


                    <td>
                        {{ $phase->description ?? '-' }}
                    </td>


                    <td>

                        @if($phase->is_active)

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


                            {{-- STATUS --}}

                            <form
                                method="POST"
                                action="{{ route(
                                    'phases.toggle',
                                    $phase
                                ) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    class="btn btn-success"
                                    type="submit"
                                >

                                    {{
                                        $phase->is_active
                                        ? 'Nonaktifkan'
                                        : 'Aktifkan'
                                    }}

                                </button>

                            </form>


                            {{-- HAPUS --}}

                            <form
                                method="POST"
                                action="{{ route(
                                    'phases.destroy',
                                    $phase
                                ) }}"
                                onsubmit="
                                    return confirm(
                                        'Yakin menghapus fase ini?'
                                    )
                                "
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger"
                                    type="submit"
                                >

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4">
                        Belum ada master fase.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection