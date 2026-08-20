@extends('layouts.app')

@section('title', 'Mode Demo')

@section('content')

<div class="page-header">
    <h1>Mode Demo</h1>
    <p>
        Pengujian buka dan tutup valve secara manual.
    </p>
</div>


{{-- STATUS ESP --}}

<div class="section">

    <h2>Status ESP</h2>

    @if($device)

        <div class="cards">

            <div class="card">
                <small>Device</small>
                <h2>{{ $device->name }}</h2>
            </div>

            <div class="card">
                <small>Status</small>

                <h2>
                    @if($device->is_online)

                        <span class="badge active">
                            ONLINE
                        </span>

                    @else

                        <span class="badge inactive">
                            OFFLINE
                        </span>

                    @endif
                </h2>
            </div>

            <div class="card">
                <small>Mode</small>
                <h2>
                    {{ $device->mode }}
                </h2>
            </div>

            <div class="card">
                <small>Last Seen</small>
                <h2 style="font-size:16px">

                    {{ $device->last_seen
                        ? $device->last_seen->format('H:i:s')
                        : '-'
                    }}

                </h2>
            </div>

        </div>

    @else

        <p>
            Belum ada ESP yang terdaftar.
        </p>

    @endif

</div>


{{-- TEST VALVE --}}

<div class="section">

    <h2>Test Valve</h2>

    @if($device)

        @if(!$device->is_online)

            <div class="alert alert-error">
                ESP sedang offline.
                Perintah yang dikirim akan kedaluwarsa
                dalam 30 detik jika ESP belum terhubung.
            </div>

        @endif


        <div class="valve-demo-grid">

            @foreach($valves as $valve)

                <div class="valve-demo-card">

                    <h3>
                        {{ $valve->name }}
                    </h3>

                    <p>
                        GPIO:
                        {{ $valve->gpio ?? '-' }}
                    </p>

                    <div class="actions">

                        {{-- 5 DETIK --}}

                        <form
                            method="POST"
                            action="{{ route('demo.command') }}">

                            @csrf

                            <input
                                type="hidden"
                                name="device_id"
                                value="{{ $device->id }}">

                            <input
                                type="hidden"
                                name="valve_id"
                                value="{{ $valve->id }}">

                            <input
                                type="hidden"
                                name="command"
                                value="TEST_OPEN">

                            <input
                                type="hidden"
                                name="duration_seconds"
                                value="5">

                            <button
                                class="btn btn-primary"
                                type="submit">

                                Buka 5 Detik

                            </button>

                        </form>


                        {{-- 10 DETIK --}}

                        <form
                            method="POST"
                            action="{{ route('demo.command') }}">

                            @csrf

                            <input
                                type="hidden"
                                name="device_id"
                                value="{{ $device->id }}">

                            <input
                                type="hidden"
                                name="valve_id"
                                value="{{ $valve->id }}">

                            <input
                                type="hidden"
                                name="command"
                                value="TEST_OPEN">

                            <input
                                type="hidden"
                                name="duration_seconds"
                                value="10">

                            <button
                                class="btn btn-success"
                                type="submit">

                                Buka 10 Detik

                            </button>

                        </form>


                        {{-- 30 DETIK --}}

                        <form
                            method="POST"
                            action="{{ route('demo.command') }}">

                            @csrf

                            <input
                                type="hidden"
                                name="device_id"
                                value="{{ $device->id }}">

                            <input
                                type="hidden"
                                name="valve_id"
                                value="{{ $valve->id }}">

                            <input
                                type="hidden"
                                name="command"
                                value="TEST_OPEN">

                            <input
                                type="hidden"
                                name="duration_seconds"
                                value="30">

                            <button
                                class="btn btn-secondary"
                                type="submit">

                                Buka 30 Detik

                            </button>

                        </form>


                        {{-- TUTUP --}}

                        <form
                            method="POST"
                            action="{{ route('demo.command') }}">

                            @csrf

                            <input
                                type="hidden"
                                name="device_id"
                                value="{{ $device->id }}">

                            <input
                                type="hidden"
                                name="valve_id"
                                value="{{ $valve->id }}">

                            <input
                                type="hidden"
                                name="command"
                                value="CLOSE">

                            <button
                                class="btn btn-danger"
                                type="submit">

                                Tutup

                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>


{{-- RIWAYAT COMMAND --}}

<div class="section">

    <h2>Riwayat Demo</h2>

    <div class="schedule-table-wrapper">

        <table class="schedule-table">

            <thead>
            <tr>
                <th>Waktu</th>
                <th>Valve</th>
                <th>Perintah</th>
                <th>Durasi</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody>

            @forelse($commands as $command)

                <tr>

                    <td>
                        {{ $command->created_at
                            ->format('H:i:s') }}
                    </td>

                    <td>
                        {{ $command->valve->name }}
                    </td>

                    <td>
                        {{ $command->command }}
                    </td>

                    <td>

                        @if($command->duration_seconds)

                            {{ $command->duration_seconds }}
                            detik

                        @else
                            -
                        @endif

                    </td>

                    <td>

                        @if($command->status === 'completed')

                            <span class="badge active">
                                COMPLETED
                            </span>

                        @elseif($command->status === 'pending')

                            <span class="badge"
                                  style="background:#fef3c7;
                                  color:#92400e">
                                PENDING
                            </span>

                        @elseif($command->status === 'running')

                            <span class="badge"
                                  style="background:#dbeafe;
                                  color:#1e40af">
                                RUNNING
                            </span>

                        @else

                            <span class="badge inactive">
                                {{ strtoupper($command->status) }}
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5">
                        Belum ada aktivitas demo.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection