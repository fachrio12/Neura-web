@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Asesmen Gamifikasi')

@section('content')
<div class="bg-white/30 backdrop-blur-lg shadow-xl rounded-2xl p-6 mb-8 border border-white/50 animate-fade-in">
    <div class="mb-6 text-center">
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 bg-clip-text text-transparent drop-shadow-md animate-pulse">
            Dashboard Admin
        </h1>
        <p class="mt-2 text-lg text-gray-800 font-medium animate-fade-in-down">
            Selamat datang, {{ $username }}!
        </p>
    </div>
</div>




<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Total Pengguna</h2>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Total Asesmen</h2>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAssessments }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Sesi Selesai Bulan Ini</h2>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCompletedSessions }}</p>
            </div>
        </div>
    </div>
</div>


<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Sesi Asesmen Terkini</h2>


    <div class="mb-4">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-3 bg-white/30 backdrop-blur-md p-4 rounded-xl shadow-md border border-white/50 animate-fade-in">
        <label for="month" class="text-gray-800 font-medium text-base">📅 Filter Bulan:</label>
        <select name="month" id="month" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:ring-2 focus:ring-blue-400 focus:outline-none transition duration-300 ease-in-out">
            <option value="">Semua Bulan</option>
            @foreach($availableMonths as $key => $month)
                <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                    {{ $month }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-5 py-2 rounded-lg shadow hover:shadow-lg transition duration-300">
            Terapkan
        </button>
    </form>
</div>


@if($recentSessions->count() > 0)
    <div class="overflow-x-auto bg-white/40 backdrop-blur-lg p-4 rounded-xl shadow-md border border-gray-200 animate-fade-in-down">
        <table class="min-w-full text-sm text-gray-700">
            <thead>
                <tr class="bg-gradient-to-r from-blue-100 to-purple-100 text-gray-700">
                    <th class="py-3 px-4 text-left font-semibold uppercase tracking-wide border-b">👤 Pengguna</th>
                    <th class="py-3 px-4 text-left font-semibold uppercase tracking-wide border-b">📋 Asesmen</th>
                    <th class="py-3 px-4 text-left font-semibold uppercase tracking-wide border-b">📅 Tanggal</th>
                    <th class="py-3 px-4 text-left font-semibold uppercase tracking-wide border-b">⚙️ Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recentSessions as $session)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="py-3 px-4">
                            <div class="font-semibold text-gray-900">{{ $session->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $session->user->email }}</div>
                        </td>
                        <td class="py-3 px-4">{{ $session->assessment->name }}</td>
                        <td class="py-3 px-4">{{ $session->taken_at->format('d M Y, H:i') }}</td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('session.show', $session->id) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg shadow transition hover:scale-105 duration-200">
                                    💬 Feedback
                                </a>
                              <a href="{{ route('session.detail', ['id' => $session->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow transition hover:scale-105 duration-200"">
                                    🔍 Detail
                                </a>


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-gray-600 text-sm italic">Belum ada sesi asesmen yang tercatat.</p>
@endif



<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Daftar Data Pengguna</h2>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center bg-white/30 backdrop-blur-md p-4 rounded-xl shadow-md border border-white/50">
            <div class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="🔍 Cari nama pengguna..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300">
                
                <button type="submit" 
                    class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold px-5 py-2 rounded-lg shadow hover:shadow-lg transition duration-300">
                    🔍 Cari
                </button>
            </div>
            
            <!-- Tombol Reset -->
            <a href="{{ route('admin.dashboard') }}" 
               class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold px-5 py-2 rounded-lg shadow hover:shadow-lg transition duration-300 whitespace-nowrap">
                🔄 Reset
            </a>
        </div>
        
        @if(request('search'))
            <div class="mt-2 text-sm text-gray-600">
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                    Menampilkan hasil pencarian: "<strong>{{ request('search') }}</strong>"
                </span>
            </div>
        @endif
    </form>

    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <th class="py-3 px-4 border-b text-left font-medium">Nama</th>
                        <th class="py-3 px-4 border-b text-left font-medium">Email</th>
                        <th class="py-3 px-4 border-b text-left font-medium">Tanggal Lahir</th>
                        <th class="py-3 px-4 border-b text-left font-medium">Jenis Kelamin</th>
                        <th class="py-3 px-4 border-b text-left font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                @if($user->role_id == 2)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="py-3 px-4 border-b font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="py-3 px-4 border-b text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="py-3 px-4 border-b text-sm text-gray-500">
                            {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d M Y') : '-' }}
                        </td>
                        <td class="py-3 px-4 border-b text-sm text-gray-500">{{ $user->gender ?? '-' }}</td>
                        <td class="py-3 px-4 border-b text-sm text-gray-500">
                            <a href="{{ route('user.progress.show', ['id' => $user->id]) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow transition hover:scale-105 duration-200">
                            🔍 Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endif
            @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600 italic">Belum ada pengguna yang terdaftar.</p>
    @endif
</div>


<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('admin.assessments') }}" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 p-4 rounded-lg flex items-center transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"></path>
            </svg>
            Kelola Asesmen
        </a>

        <a href="{{ route('admin.assessments.create') }}" class="bg-green-100 hover:bg-green-200 text-green-700 p-4 rounded-lg flex items-center transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Asesmen Baru
        </a>
    </div>
</div>
@endsection
