@extends('layouts.app')

@section('title', 'Jadwal Fertigasi')

@section('content')

<div class="page-header">

    <h1>Jadwal Fertigasi</h1>

    <p>
        Pengaturan jadwal fertigasi berdasarkan
        fase pertumbuhan dan rentang HST tanaman.
    </p>

</div>


{{-- ================================================== --}}
{{-- INFORMASI TANAMAN AKTIF --}}
{{-- ================================================== --}}

<div class="section">

    <h2>Tanaman Aktif</h2>

    @if($planting)

        <div class="cards">

            {{-- NAMA --}}
            <div class="card">

                <small>
                    Nama Penanaman
                </small>

                <h2>
                    {{ $planting->name }}
                </h2>

            </div>


            {{-- TANGGAL --}}
            <div class="card">

                <small>
                    Tanggal Tanam
                </small>

                <h2>
                    {{ $planting->planting_date->format('d-m-Y') }}
                </h2>

            </div>


            {{-- HST --}}
            <div class="card">

                <small>
                    Usia Tanaman
                </small>

                <h2>

                    @if($hst !== null && $hst >= 0)

                        HST {{ $hst }}

                    @else

                        Belum Tanam

                    @endif

                </h2>

            </div>


            {{-- PROFIL --}}
            <div class="card">

                <small>
                    Profil Fertigasi
                </small>

                <h2>
                    {{ $planting->fertigationProfile?->name ?? '-' }}
                </h2>

            </div>

        </div>

    @else

        <p>
            Belum ada penanaman aktif.
        </p>

    @endif

</div>


{{-- ================================================== --}}
{{-- PILIH PROFIL --}}
{{-- ================================================== --}}

<div class="section">

    <h2>Profil Fertigasi</h2>

    @if($profiles->count())

        <form
            method="GET"
            action="{{ route('jadwal.index') }}"
        >

            <select
                name="profile_id"
                onchange="this.form.submit()"
            >

                @foreach($profiles as $item)

                    <option
                        value="{{ $item->id }}"
                        @selected($profile?->id == $item->id)
                    >
                        {{ $item->name }}
                    </option>

                @endforeach

            </select>

        </form>

    @else

        <p>
            Belum ada profil fertigasi aktif.
        </p>

    @endif

</div>


{{-- ================================================== --}}
{{-- TAMBAH JADWAL --}}
{{-- ================================================== --}}

<div class="section">

    <h2>Tambah Jadwal</h2>

    @if(!$profile)

        <div class="alert alert-error">
            Buat atau aktifkan Profil Fertigasi terlebih dahulu.
        </div>

    @elseif($phases->isEmpty())

        <div class="alert alert-error">
            Buat Master Fase terlebih dahulu.
        </div>

    @elseif($valves->isEmpty())

        <div class="alert alert-error">
            Buat Master Valve terlebih dahulu.
        </div>

    @else

        <form
            method="POST"
            action="{{ route('jadwal.store') }}"
        >

            @csrf


            {{-- PROFIL --}}
            <input
                type="hidden"
                name="fertigation_profile_id"
                value="{{ $profile->id }}"
            >


            <div class="form-grid">


                {{-- FASE --}}
                <div>

                    <label>
                        Fase Pertumbuhan
                    </label>

                    <select
                        name="growth_phase_id"
                        required
                    >

                        <option value="">
                            -- Pilih Fase --
                        </option>

                        @foreach($phases as $phase)

                            <option
                                value="{{ $phase->id }}"
                                @selected(
                                    old('growth_phase_id')
                                    == $phase->id
                                )
                            >

                                {{ $phase->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- HST MULAI --}}
                <div>

                    <label>
                        HST Mulai
                    </label>

                    <input
                        type="number"
                        name="hst_start"
                        min="0"
                        max="365"
                        value="{{ old('hst_start') }}"
                        placeholder="Contoh: 0"
                        required
                    >

                </div>


                {{-- HST SELESAI --}}
                <div>

                    <label>
                        HST Selesai
                    </label>

                    <input
                        type="number"
                        name="hst_end"
                        min="0"
                        max="365"
                        value="{{ old('hst_end') }}"
                        placeholder="Contoh: 7"
                        required
                    >

                </div>


                {{-- VALVE --}}
                <div>

                    <label>
                        Valve
                    </label>

                    <select
                        name="valve_id"
                        required
                    >

                        <option value="">
                            -- Pilih Valve --
                        </option>

                        @foreach($valves as $valve)

                            <option
                                value="{{ $valve->id }}"
                                @selected(
                                    old('valve_id')
                                    == $valve->id
                                )
                            >

                                {{ $valve->name }}

                                @if($valve->gpio)
                                    - GPIO {{ $valve->gpio }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- JAM --}}
                <div>

                    <label>
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        name="start_time"
                        value="{{ old('start_time') }}"
                        required
                    >

                </div>


                {{-- DURASI --}}
                <div>

                    <label>
                        Durasi (menit)
                    </label>

                    <input
                        type="number"
                        name="duration_minutes"
                        min="1"
                        max="1440"
                        value="{{ old('duration_minutes', 5) }}"
                        required
                    >

                </div>


                {{-- BUTTON --}}
                <div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        + Tambah Jadwal
                    </button>

                </div>

            </div>

        </form>

    @endif

</div>


{{-- ================================================== --}}
{{-- DAFTAR JADWAL --}}
{{-- ================================================== --}}

<div class="section">

    <h2>

        Daftar Jadwal

        @if($profile)
            - {{ $profile->name }}
        @endif

    </h2>


    <div class="schedule-table-wrapper">

        <table class="schedule-table">

            <thead>

                <tr>
                    <th>Fase</th>
                    <th>Rentang HST</th>
                    <th>Jam</th>
                    <th>Valve</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th width="230">Aksi</th>
                </tr>

            </thead>


            <tbody>

                @forelse($schedules as $schedule)

                    <tr>


                        {{-- FASE --}}
                        <td>

                            @if($schedule->growthPhase)

                                <strong>
                                    {{ $schedule->growthPhase->name }}
                                </strong>

                                @if($schedule->growthPhase->description)

                                    <small
                                        style="
                                            display:block;
                                            color:#6b7280;
                                            margin-top:4px;
                                        "
                                    >
                                        {{ $schedule->growthPhase->description }}
                                    </small>

                                @endif

                            @else

                                <span style="color:#9ca3af">
                                    Belum dipilih
                                </span>

                            @endif

                        </td>


                        {{-- HST --}}
                        <td>

                            <strong>
                                HST {{ $schedule->hst_start }}
                                -
                                {{ $schedule->hst_end }}
                            </strong>


                            @if(
                                $hst !== null
                                &&
                                $hst >= $schedule->hst_start
                                &&
                                $hst <= $schedule->hst_end
                            )

                                <br>

                                <span class="today-label">
                                    HARI INI
                                </span>

                            @endif

                        </td>


                        {{-- JAM --}}
                        <td class="schedule-time">

                            {{ substr($schedule->start_time, 0, 5) }}

                        </td>


                        {{-- VALVE --}}
                        <td class="schedule-valve">

                            @if($schedule->valve)

                                <strong>
                                    {{ $schedule->valve->name }}
                                </strong>

                                @if($schedule->valve->gpio)

                                    <small>
                                        GPIO {{ $schedule->valve->gpio }}
                                    </small>

                                @endif

                            @else

                                -

                            @endif

                        </td>


                        {{-- DURASI --}}
                        <td>

                            {{ $schedule->duration_seconds / 60 }}
                            menit

                        </td>


                        {{-- STATUS --}}
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


                        {{-- AKSI --}}
                        <td>

                            <div class="actions">


                                {{-- TOGGLE --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'jadwal.toggle',
                                        $schedule
                                    ) }}"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                    >

                                        {{ $schedule->is_active
                                            ? 'Nonaktifkan'
                                            : 'Aktifkan'
                                        }}

                                    </button>

                                </form>


                                {{-- HAPUS --}}
                                <form
                                    method="POST"
                                    action="{{ route(
                                        'jadwal.destroy',
                                        $schedule
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Yakin menghapus jadwal ini?'
                                    )"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            style="text-align:center; padding:30px;"
                        >
                            Belum ada jadwal pada profil ini.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection