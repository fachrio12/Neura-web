@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8 bg-gradient-to-br from-purple-50 via-blue-50 to-white shadow-xl rounded-2xl border border-purple-200">
    <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-8">📄 Detail Sesi Asesmen</h2>

    <div class="bg-white p-6 rounded-xl shadow-inner mb-6 border-l-4 border-blue-400">
        <h3 class="text-xl font-semibold text-blue-600 mb-3">👤 Informasi Pengguna</h3>
        <p class="text-gray-700"><strong>Nama:</strong> {{ $session->user->name }}</p>
        <p class="text-gray-700"><strong>Email:</strong> {{ $session->user->email }}</p>
        <p class="text-gray-700"><strong>Tanggal:</strong> {{ $session->taken_at->format('d M Y') }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-inner mb-6 border-l-4 border-purple-500">
        <h3 class="text-xl font-semibold text-purple-700 mb-3">📊 Hasil Asesmen</h3>
        @forelse ($session->results as $result)
            <div class="mb-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                <p class="text-gray-800"><strong>Kategori:</strong> {{ $result->result_category }}</p>
                <p class="text-gray-800"><strong>Skor:</strong> {{ $result->score }}</p>
                <p class="text-gray-800"><strong>Interpretasi:</strong> {{ $result->interpretation }}</p>
                <p class="text-gray-800"><strong>Kategori Assement</strong> {{  $session->assessment->name }}</p>
            </div>
        @empty
            <p class="text-gray-600 italic">Belum ada hasil asesmen.</p>
        @endforelse
    </div>

    <div class="bg-white p-6 rounded-xl shadow-inner mb-6 border-l-4 border-blue-500">
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

    <div class="mt-10 bg-white p-6 rounded-xl shadow-inner border-l-4 border-indigo-400">
    <h3 class="text-xl font-semibold text-indigo-700 mb-4 text-center">📈 Grafik Skor Asesmen per Bulan</h3>
    <canvas id="assessmentChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('assessmentChart').getContext('2d');
    const chart = new Chart(ctx, {
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
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#4B5563',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
</script>


    <div class="text-center">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-block bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white font-semibold py-3 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out">
           ⬅ Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
