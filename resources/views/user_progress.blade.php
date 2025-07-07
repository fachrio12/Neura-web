@extends('layouts.app')

@section('title', 'Progress Pengguna')

@section('content')
<div class="min-h-screen py-10 px-6 bg-gradient-to-b from-indigo-200 to-purple-200">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-semibold mb-4">📊 Progress Pengguna: {{ $user->name }}</h2>

        <!-- Filter dropdown -->
        <form method="GET" action="{{ url()->current() }}" class="mb-6">
            <label for="assessment" class="block text-sm font-medium text-gray-700">Filter berdasarkan assessment:</label>
            <select name="assessment" id="assessment" onchange="this.form.submit()" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                <option value="">Semua Assessment</option>
                @foreach ($availableAssessments as $name)
                    <option value="{{ $name }}" {{ $selectedAssessment == $name ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </form>

        <!-- Grafik -->
        <div class="mb-8">
            <canvas id="scoreChart"></canvas>
        </div>

        <!-- Rata-rata Skor per Jenis Assessment -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3">📌 Rata-rata Skor per Jenis Assessment</h3>
            @php
                $grouped = $sessions->groupBy('assessment.name');
            @endphp
            <ul class="list-disc ml-5 text-gray-800">
                @foreach ($grouped as $assessmentName => $sessionGroup)
                    @php
                        $total = 0;
                        $count = 0;
                        foreach ($sessionGroup as $session) {
                            foreach ($session->results as $result) {
                                $total += $result->score;
                                $count++;
                            }
                        }
                        $avg = $count > 0 ? round($total / $count, 2) : 0;
                    @endphp
                    <li><strong>{{ $assessmentName }}:</strong> {{ $avg }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Detail Hasil per Sesi -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-4">📄 Detail Hasil Assessment</h3>
            @forelse($sessions as $session)
                <div class="mb-6 p-4 bg-white rounded shadow border border-indigo-300">
                    <p><strong>Tanggal:</strong> {{ $session->taken_at->format('d M Y') }}</p>
                    <p><strong>Assessment:</strong> {{ $session->assessment->name }}</p>

                    @php
                        $total = $session->results->sum('score');
                        $count = $session->results->count();
                        $average = $count > 0 ? round($total / $count, 2) : 0;
                    @endphp

                    <p><strong>Skor Rata-rata Sesi:</strong> {{ $average }}</p>

                    <ul class="list-disc ml-6 mt-2 text-sm text-gray-800">
                        @foreach($session->results as $result)
                            <li class="mb-1">
                                <strong>Jenis:</strong> {{ $result->category }}<br>
                                <strong>Skor:</strong> {{ $result->score }}<br>
                                <strong>Interpretasi:</strong> {{ $result->interpretation }}
                            </li>
                        @endforeach
                    </ul>

                    @if ($session->feedbacks && $session->feedbacks->isNotEmpty())
                        <div class="mt-4">
                            <h4 class="text-sm font-semibold mb-1 text-indigo-600">📝 Feedback Sesi:</h4>
                            @foreach($session->feedbacks as $feedback)
                                <div class="mb-2 p-3 bg-blue-50 rounded shadow-sm">
                                    <div class="text-xs text-gray-600">{{ $feedback->created_at->format('d M Y H:i') }}</div>
                                    <p class="text-gray-800 mt-1">{{ $feedback->feedback_text }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 italic">Belum ada hasil asesmen yang tersedia.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('scoreChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyScores->keys()) !!},
            datasets: [{
                label: 'Rata-rata Skor Bulanan',
                data: {!! json_encode($monthlyScores->values()) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endsection
