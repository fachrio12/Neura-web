@extends('layouts.app')

@section('title', 'Daftar Asesmen')

@section('content')
<div class="min-h-screen bg-gradient-to-tr from-indigo-100 via-blue-100 to-purple-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl font-extrabold text-center text-purple-800 mb-10 drop-shadow-md tracking-wide">
            🎯 Daftar Asesmen
        </h2>

        @if(session('message'))
            <div class="bg-purple-200 border-l-4 border-purple-600 text-purple-900 px-4 py-3 rounded mb-6 shadow">
                {{ session('message') }}
            </div>
        @endif

        @if($assessments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($assessments as $assessment)
                    <div class="bg-white border border-purple-200 shadow-xl rounded-xl overflow-hidden hover:shadow-purple-400 transition duration-300 relative">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-indigo-800 line-clamp-2">
                                    {{ $assessment->name }}
                                </h3>
                                @if(in_array($assessment->id, $completedAssessments))
                                    <span class="bg-green-200 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded-full shadow">
                                        ✅ Selesai
                                    </span>
                                @else
                                    <span class="bg-yellow-200 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded-full shadow">
                                        🕒 Belum
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $assessment->description }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $assessment->questions_count ?? 0 }} Pertanyaan
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($assessment->date_created)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="flex justify-center">
                                @if(in_array($assessment->id, $completedAssessments))
                                    <button disabled class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg cursor-not-allowed font-semibold tracking-wide">
                                        ✔️ Sudah Dikerjakan
                                    </button>
                                @else
                                    <a href="{{ route('user.assessments.take', $assessment->id) }}"
                                       class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-2 rounded-full hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow font-semibold tracking-wide inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        Mulai Asesmen
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow-lg rounded-xl p-10 text-center mt-10">
                <svg class="w-20 h-20 mx-auto text-purple-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-purple-800 mb-2">Tidak Ada Asesmen 😔</h3>
                <p class="text-gray-600">Belum ada asesmen yang tersedia. Yuk cek kembali nanti!</p>
            </div>
        @endif
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
