@extends('layouts.app')

@section('title', 'Pengelolaan Asesmen')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-100">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-8 text-center">📊 Pengelolaan Asesmen</h2>

        <div class="flex justify-between items-center mb-6">
    <div class="flex items-center space-x-3">
        <label for="month" class="text-gray-700 font-medium">🗓️ Filter Bulan:</label>
        <form method="GET" action="{{ route('admin.assessments') }}">
            <select name="month" id="month" onchange="this.form.submit()"
                class="border border-purple-300 bg-white rounded-lg px-4 py-2 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-purple-500 transition">
                <option value="">Semua Bulan</option>
                @foreach ($months as $key => $label)
                    <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>


        @if($assessments->isEmpty())
            <div class="text-center text-gray-600 text-lg">
                Belum ada asesmen yang dibuat.
            </div>
        @else
            <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Asesmen</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Jumlah Pertanyaan</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($assessments as $assessment)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $assessment->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assessment->description }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900">{{ $assessment->questions_count }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($assessment->date_created)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($assessment->is_active)
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-sm relative">
                                <div class="inline-block text-left">
                                    <button type="button" class="px-4 py-2 bg-purple-100 text-purple-800 font-semibold text-sm rounded-lg hover:bg-purple-200 transition"
                                        onclick="toggleDropdown({{ $assessment->id }})">
                                        Aksi ⌄
                                    </button>
                                    <div id="dropdown-{{ $assessment->id }}"
                                        class="hidden absolute right-0 z-10 mt-2 w-44 bg-white border border-gray-200 rounded-md shadow-lg overflow-hidden">
                                        <form method="POST" action="{{ route('admin.assessments.update', $assessment->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_active" value="1">
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-purple-700 hover:bg-purple-100 transition">
                                                ✅ Aktifkan
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.assessments.update', $assessment->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_active" value="0">
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-sm text-purple-700 hover:bg-purple-100 transition">
                                                ❌ Nonaktifkan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById('dropdown-' + id);
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(drop => {
            if (!drop.contains(event.target) && !event.target.closest('button')) {
                drop.classList.add('hidden');
            }
        });
    });
</script>
@endsection
