@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold text-purple-600 mb-4">🧩 Halaman Gamifikasi</h2>

    <p>Daftar asesmen yang aktif:</p>
    <ul class="list-disc list-inside">
        @foreach ($assessments as $assessment)
            <li>{{ $assessment->name }}</li>
        @endforeach
    </ul>

    <p class="mt-4 text-sm text-gray-500">Asesmen yang sudah kamu selesaikan:</p>
    <ul class="list-inside text-sm text-gray-700">
        @foreach ($completedAssessments as $completed)
            <li>ID Asesmen: {{ $completed }}</li>
        @endforeach
    </ul>
</div>
@endsection
