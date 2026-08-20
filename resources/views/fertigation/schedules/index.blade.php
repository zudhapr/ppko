@extends('layouts.app')

@section('title', 'Jadwal Fertigasi')

@section('content')

<div class="page-header">
    <h1>Jadwal Fertigasi</h1>
    <p>Pengaturan jadwal penyiraman berdasarkan usia tanaman / HST.</p>
</div>

{{-- INFORMASI TANAMAN --}}
<div class="section">

    <h2>Tanaman Aktif</h2>

    @if($planting)

        <div class="cards">

            <div class="card">
                <small>Nama Penanaman</small>
                <h2>{{ $planting->name }}</h2>
            </div>

            <div class="card">
                <small>Tanggal Tanam</small>
                <h2>
                    {{ $planting->planting_date->format('d-m-Y') }}
                </h2>
            </div>

            <div class="card">
                <small>Usia Tanaman</small>
                <h2>HST {{ $hst }}</h2>
            </div>

            <div class="card">
                <small>Profil Fertigasi</small>
                <h2>
                    {{ $planting->fertigationProfile?->name ?? '-' }}
                </h2>
            </div>

        </div>

    @else
        <p>Belum ada penanaman aktif.</p>
    @endif

</div>


{{-- PILIH PROFIL --}}
<div class="section">

    <h2>Profil Fertigasi</h2>

    <form method="GET"
          action="{{ route('jadwal.index') }}">

        <select name="profile_id"
                onchange="this.form.submit()">

            @foreach($profiles as $item)

                <option value="{{ $item->id }}"
                    @selected($profile?->id == $item->id)>

                    {{ $item->name }}

                </option>

            @endforeach

        </select>

    </form>

</div>


{{-- TAMBAH JADWAL --}}
<div class="section">

    <h2>Tambah Jadwal</h2>

    <form method="POST"
          action="{{ route('jadwal.store') }}">

        @csrf

        <input type="hidden"
               name="fertigation_profile_id"
               value="{{ $profile?->id }}">

        <div class="form-grid">

            <div>
                <label>HST</label>

                <input
                    type="number"
                    name="hst"
                    min="0"
                    max="365"
                    placeholder="Contoh: 14"
                    required
                >
            </div>

            <div>
                <label>Valve</label>

                <select name="valve_id" required>

                    @foreach($valves as $valve)

                        <option value="{{ $valve->id }}">
                            {{ $valve->name }}
                            @if($valve->gpio)
                                - GPIO {{ $valve->gpio }}
                            @endif
                        </option>

                    @endforeach

                </select>
            </div>

            <div>
                <label>Jam Mulai</label>

                <input
                    type="time"
                    name="start_time"
                    required
                >
            </div>

            <div>
                <label>Durasi (menit)</label>

                <input
                    type="number"
                    name="duration_minutes"
                    min="1"
                    value="5"
                    required
                >
            </div>

            <div>
                <button
                    class="btn btn-primary"
                    type="submit">

                    + Tambah Jadwal
                </button>
            </div>

        </div>

    </form>

</div>


{{-- DAFTAR JADWAL --}}
<div class="section">

    <h2>
        Daftar Jadwal
        @if($profile)
            - {{ $profile->name }}
        @endif
    </h2>

    @forelse($schedules as $hstNumber => $items)

        <div class="schedule-group">

            <div class="hst-title">

                HST {{ $hstNumber }}

                @if($hst !== null && $hst == $hstNumber)
                    <span class="today-label">
                        HARI INI
                    </span>
                @endif

            </div>

            <div class="schedule-table-wrapper">

                <table class="schedule-table">

                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Valve</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th width="220">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($items as $schedule)

                        <tr>

                            <td class="schedule-time">
                                {{ substr($schedule->start_time, 0, 5) }}
                            </td>

                            <td class="schedule-valve">

                                {{ $schedule->valve->name }}

                                @if($schedule->valve->gpio)
                                    <small>
                                        GPIO {{ $schedule->valve->gpio }}
                                    </small>
                                @endif

                            </td>

                            <td>
                                {{ $schedule->duration_seconds / 60 }}
                                menit
                            </td>

                            <td>

                                @if($schedule->is_active)

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
                                            'jadwal.toggle',
                                            $schedule
                                        ) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            class="btn btn-success"
                                            type="submit">

                                            {{ $schedule->is_active
                                                ? 'Nonaktifkan'
                                                : 'Aktifkan'
                                            }}

                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'jadwal.destroy',
                                            $schedule
                                        ) }}"
                                        onsubmit="return confirm(
                                            'Yakin menghapus jadwal ini?'
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

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @empty

        <p>
            Belum ada jadwal pada profil ini.
        </p>

    @endforelse

</div>

@endsection