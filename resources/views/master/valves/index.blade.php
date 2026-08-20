@extends('layouts.app')

@section('title', 'Master Valve')

@section('content')

<div class="page-header">
    <h1>Master Valve</h1>
    <p>Kelola valve yang terhubung ke relay dan ESP32.</p>
</div>

<div class="section">

    <h2>Tambah Valve</h2>

    <form method="POST"
          action="{{ route('valves.store') }}">

        @csrf

        <div class="form-grid">

            <div>
                <label>Nama Valve</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Contoh: Valve 1"
                    required
                >
            </div>

            <div>
                <label>GPIO ESP32</label>

                <input
                    type="text"
                    name="gpio"
                    placeholder="Contoh: 25"
                >
            </div>

            <div>
                <label>Deskripsi</label>

                <input
                    type="text"
                    name="description"
                    placeholder="Contoh: Zona tanaman kiri"
                >
            </div>

            <div>
                <button
                    class="btn btn-primary"
                    type="submit">

                    + Tambah Valve
                </button>
            </div>

        </div>

    </form>

</div>


<div class="section">

    <h2>Daftar Valve</h2>

    <div class="schedule-table-wrapper">

        <table class="schedule-table">

            <thead>
                <tr>
                    <th>Nama Valve</th>
                    <th>GPIO</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($valves as $valve)

                <tr>

                    <td>
                        <strong>
                            {{ $valve->name }}
                        </strong>
                    </td>

                    <td>
                        {{ $valve->gpio ?? '-' }}
                    </td>

                    <td>
                        {{ $valve->description ?? '-' }}
                    </td>

                    <td>

                        @if($valve->is_active)

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
                                    'valves.toggle',
                                    $valve
                                ) }}">

                                @csrf
                                @method('PATCH')

                                <button
                                    class="btn btn-success"
                                    type="submit">

                                    {{ $valve->is_active
                                        ? 'Nonaktifkan'
                                        : 'Aktifkan'
                                    }}

                                </button>

                            </form>

                            <form
                                method="POST"
                                action="{{ route(
                                    'valves.destroy',
                                    $valve
                                ) }}"
                                onsubmit="return confirm(
                                    'Yakin menghapus valve ini?'
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
                    <td colspan="5">
                        Belum ada data valve.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection