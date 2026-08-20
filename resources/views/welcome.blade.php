@extends('layouts.app')

@section('title', 'Dashboard Fertigasi')

@section('content')

<h1>Dashboard Fertigasi</h1>

<div class="cards">

    <div class="card">
        <small>Tanaman Aktif</small>
        <h2>
            {{ $planting?->name ?? '-' }}
        </h2>
    </div>

    <div class="card">
        <small>Tanggal Tanam</small>
        <h2>
            {{ $planting
                ? $planting->planting_date->format('d-m-Y')
                : '-'
            }}
        </h2>
    </div>

    <div class="card">
        <small>Usia Tanaman</small>
        <h2>
            {{ $hst !== null ? 'HST '.$hst : '-' }}
        </h2>
    </div>

    <div class="card">
        <small>Valve Aktif</small>
        <h2>
            {{ $valveCount }}
        </h2>
    </div>

</div>


<div class="section">

    <h2>Jadwal Hari Ini</h2>

    @if($todaySchedules->count())

        <table>

            <thead>
                <tr>
                    <th>Jam</th>
                    <th>Valve</th>
                    <th>Durasi</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @foreach($todaySchedules as $schedule)

                <tr>

                    <td>
                        {{ substr($schedule->start_time, 0, 5) }}
                    </td>

                    <td>
                        {{ $schedule->valve->name }}
                    </td>

                    <td>
                        {{ floor($schedule->duration_seconds / 60) }}
                        menit
                    </td>

                    <td>
                        <span class="badge active">
                            Terjadwal
                        </span>
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    @else

        <p>Belum ada jadwal untuk HST hari ini.</p>

    @endif

</div>


<div class="section">

    <h2>Menu Cepat</h2>

    <div class="menu-grid">

        <a href="{{ route('jadwal.index') }}"
           class="menu-card">

            <strong>Jadwal Fertigasi</strong>
            Atur jadwal berdasarkan HST.

        </a>

        <a href="{{ route('profiles.index') }}"
           class="menu-card">

            <strong>Profil Fertigasi</strong>
            Kelola master profil tanaman.

        </a>

        <a href="{{ route('valves.index') }}"
           class="menu-card">

            <strong>Master Valve</strong>
            Kelola valve dan GPIO.

        </a>

    </div>

</div>

@endsection