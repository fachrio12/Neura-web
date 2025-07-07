@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8 bg-gradient-to-br from-purple-50 via-blue-50 to-white shadow-xl rounded-2xl border border-purple-200">
    <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">📄 Riwayat Asesmen Saya</h2>

    @forelse ($sessions as $session)
    <div class="bg-white p-6 rounded-xl shadow-inner mb-6 border-l-4 border-blue-400">
        <p class="text-gray-700"><strong>Tanggal:</strong> {{ $session->taken_at->format('d M Y') }}</p>

        @forelse ($session->results as $result)
            <div class="mt-2 p-4 bg-purple-50 rounded-lg border border-purple-200">
                <p class="text-gray-800"><strong>Kategori Assement</strong> {{ $session->assessment->name }}</p>
                <p class="text-gray-800"><strong>Kategori:</strong> {{ $result->result_category }}</p>
                <p class="text-gray-800"><strong>Skor:</strong> {{ $result->score }}</p>
                <p class="text-gray-800"><strong>Interpretasi:</strong> {{ $result->interpretation }}</p>
            </div>
        @empty
            <p class="text-gray-600 italic mt-2">Belum ada hasil untuk sesi ini.</p>
        @endforelse


        <div class="bg-white p-6 rounded-xl shadow-inner mt-4 border-l-4 border-blue-500">
            <h3 class="text-xl font-semibold text-blue-700 mb-3">💬 Feedback</h3>
            @forelse ($session->feedbacks as $feedback)
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-gray-800">📝 {{ $feedback->feedback_text }}</p>
                    <p class="text-sm text-gray-500">{{ $feedback->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-gray-600 italic">Belum ada feedback.</p>
            @endforelse
        </div>
    </div>
@empty
    <p class="text-gray-600 text-center italic">Belum ada sesi asesmen yang kamu ikuti.</p>
@endforelse


   <form method="GET" class="mb-6">
    <label for="assessment" class="block text-sm font-semibold text-indigo-700 mb-2">
        🔍 Filter berdasarkan Asesmen:
    </label>
    <div class="relative inline-block w-64">
        <select name="assessment" id="assessment" onchange="this.form.submit()"
            class="block appearance-none w-full bg-white border border-indigo-300 text-indigo-800 py-2 px-4 pr-8 rounded-lg shadow-sm leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500">
            <option value="">Semua</option>
            @foreach (collect($availableAssessments)->filter()->unique() as $name)
                <option value="{{ $name }}" {{ request('assessment') == $name ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-indigo-500">
            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                <path d="M5.516 7.548a.75.75 0 011.06 0L10 10.972l3.424-3.424a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" />
            </svg>
        </div>
    </div>
</form>


    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Script to render the chart --}}
    @if ($monthlyScores && count($monthlyScores) > 0)
    <div class="mt-10 bg-white p-6 rounded-xl shadow-inner border-l-4 border-indigo-400" style="height: 400px;">
        <h3 class="text-xl font-semibold text-indigo-700 mb-4 text-center">📈 Grafik Rata-Rata Skor per Bulan</h3>
        <canvas id="assessmentChart" style="max-height: 300px;"></canvas>
    </div>


    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let chartRendered = false;

            document.addEventListener("DOMContentLoaded", function () {
                if (chartRendered) return;
                chartRendered = true;

                const ctx = document.getElementById('assessmentChart')?.getContext('2d');

                if (!ctx) return;

                if (window.assessmentChartInstance) {
                    window.assessmentChartInstance.destroy();
                }

                window.assessmentChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthlyScores->keys()) !!},
                        datasets: [{
                            label: 'Rata-rata Skor',
                            data: {!! json_encode($monthlyScores->values()) !!},
                            fill: true,
                            borderColor: '#6366F1',
                            backgroundColor: 'rgba(99, 102, 241, 0.2)',
                            tension: 0.3,
                            pointBackgroundColor: '#9333EA',
                            pointBorderColor: '#ffffff',
                            pointRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Skor'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Bulan'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endonce
@endif


    <div class="text-center mt-10">
    <a href="{{ route('user.assessments') }}"
       class="inline-block bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out">
        ⬅ Kembali ke Dashboard
    </a>
    </div>

</div>
@endsection
