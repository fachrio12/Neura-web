@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-gradient-to-br from-purple-100 via-blue-100 to-white rounded-3xl shadow-lg mt-8 border border-purple-200">
    <h2 class="text-3xl font-extrabold text-purple-700 mb-6 text-center">🎉 Hasil Asesmen  🎉</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow text-purple-800 font-semibold border border-purple-200">
            <span class="block text-sm text-gray-500">Nama</span>
            {{ $session->user->name }}
        </div>
        <div class="bg-white p-4 rounded-xl shadow text-blue-800 font-semibold border border-blue-200">
            <span class="block text-sm text-gray-500">Jenis Asesmen</span>
            {{ $session->assessment->name }}
        </div>
        <div class="bg-white p-4 rounded-xl shadow text-purple-800 font-semibold border border-purple-200">
        <span class="block text-sm text-gray-500">Tanggal</span>
        {{ $session->taken_at ? $session->taken_at->format('d M Y') : '-' }}
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-blue-800 font-semibold border border-blue-200">
        <span class="block text-sm text-gray-500">Skor</span>
        {{ $score }}
        </div>

    </div>

    <a href="{{ route('session.show', $session->id) }}" class="btn btn-primary">Lihat</a>

    <form method="POST" action="{{ route('session.store_something', $session->id) }}" class="bg-white p-5 rounded-xl border border-purple-200 shadow-inner mt-4">
    @csrf

    <input type="hidden" name="session_id" value="{{ $session->id }}">

    <label for="feedback_text" class="block text-lg font-semibold text-purple-700 mb-2">📝 Saran Otomatis</label>
    <textarea id="feedback_text" name="feedback_text" rows="5"
        class="w-full p-3 border-2 border-blue-200 focus:border-purple-400 focus:ring-2 focus:ring-purple-300 rounded-lg text-gray-800 font-medium bg-blue-50 placeholder-gray-400"
        placeholder="Saran ini bisa kamu edit atau hapus sesuai keinginan ya 😊">{{ old('feedback_text', $suggestion ?? '') }}</textarea>

    <div class="text-right mt-4">
        <button type="submit"
            class="bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all duration-300 ease-in-out">
            💾 Simpan Saran
        </button>
    </div>
</form>



</div>
@endsection
