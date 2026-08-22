@extends('layouts.app')

@section('title', 'Dashboard Fertigasi')

@section('content')

<h1>Dashboard Fertigasi</h1>
<div class="section">

    <h2>Status Perangkat ESP</h2>

    @if($device)

        <table>

            <tr>
                <th>Nama Device</th>
                <td>{{ $device->name }}</td>
            </tr>

            <tr>
                <th>Device Code</th>
                <td>{{ $device->device_code }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>

                    @if($device->is_online)

                        <span class="badge active">
                            ONLINE
                        </span>

                    @else

                        <span class="badge inactive">
                            OFFLINE
                        </span>

                    @endif

                </td>
            </tr>

            <tr>
                <th>Mode</th>
                <td>{{ $device->mode }}</td>
            </tr>

            <tr>
                <th>HST Device</th>
                <td>
                    {{ $device->current_hst ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>IP Address</th>
                <td>
                    {{ $device->ip_address ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Firmware</th>
                <td>
                    {{ $device->firmware_version ?? '-' }}
                </td>
            </tr>

            <tr>
                <th>Last Seen</th>
                <td>
                    {{ $device->last_seen
                        ? $device->last_seen->format(
                            'd-m-Y H:i:s'
                        )
                        : '-'
                    }}
                </td>
            </tr>

        </table>

    @else

        <p>
            Belum ada perangkat ESP yang terdaftar.
        </p>

    @endif

</div>


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