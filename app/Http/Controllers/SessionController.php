<?php
namespace App\Http\Controllers;

use App\Models\UserAssessmentSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class SessionController extends Controller
{
    public function show(UserAssessmentSession $session)
{
    // Load relasi assessment dan hasil-hasilnya
    $session->load(['assessment', 'results']);

    // Hitung total skor dari relasi results
    $score = $session->results->sum('score');

    // Identifikasi jenis assessment
    // $type = Str::lower($session->assessment->name);
    // $suggestion = '';

    // Buat interpretasi otomatis berdasarkan jenis dan skor

    if ('Minat Bakat') {
    if ($score < 50) {
        $suggestion = 'Hasil asesmen menunjukkan bahwa kamu memiliki potensi yang kuat di bidang non-akademik. Ini bisa mencakup dunia seni seperti musik, tari, lukisan, ataupun kegiatan fisik seperti olahraga dan keterampilan teknis atau vokasional. Dunia ini membutuhkan individu kreatif, ekspresif, dan penuh semangat seperti kamu. Teruslah menggali dan mengasah bakatmu, karena bidang-bidang ini memiliki peran penting dalam kehidupan dan dapat menjadi jalan karier yang sangat membanggakan. 🌟 Jadilah versi terbaik dirimu di dunia non-akademik!';
    } else {
        $suggestion = 'Nilai yang kamu peroleh menunjukkan kecenderungan kuat dalam bidang akademik. Ini berarti kamu memiliki potensi besar untuk berkembang di ranah intelektual seperti sains, matematika, teknologi, atau literasi. Kamu mungkin menikmati tantangan berpikir logis, analisis mendalam, dan eksplorasi pengetahuan baru. Gunakan potensi ini untuk membangun masa depan yang cemerlang dan berkontribusi dalam perubahan positif di masyarakat. ✨ Dunia membutuhkan pemikir hebat sepertimu!';
    }
} elseif ('Kecenderungan Otak (Kanan/Kiri)') {
    if ($score <= 50) {
        $suggestion = 'Berdasarkan hasil asesmen, kamu memiliki dominasi otak kanan, yang membuatmu lebih kreatif, intuitif, dan imajinatif. Kamu cenderung berpikir dengan cara yang unik, ekspresif, dan menyukai kebebasan dalam berkreasi. Dunia seni, desain grafis, storytelling, dan inovasi visual mungkin menjadi ruang di mana kamu bisa bersinar. 🌈 Manfaatkan kelebihan ini untuk menciptakan karya yang bisa menyentuh hati banyak orang. Kreativitasmu adalah kekuatanmu!';
    } else {
        $suggestion = 'Hasil menunjukkan bahwa kamu memiliki dominasi otak kiri, yang berarti kamu cenderung logis, terstruktur, dan analitis. Kamu mungkin lebih nyaman dengan hal-hal yang melibatkan data, angka, strategi, dan pemikiran sistematis. Ini adalah keunggulan besar dalam bidang seperti teknologi, matematika, perencanaan, dan pemecahan masalah. 💼 Gunakan kekuatan berpikirmu untuk menaklukkan tantangan dan berkontribusi secara nyata melalui ide-ide brilianmu!';
    }
} elseif ('Motivasi Belajar') {
    if ($score < 50) {
        $suggestion = 'Saat ini, tingkat motivasi belajarmu masih dapat ditingkatkan. Ini bukan sesuatu yang buruk, justru ini adalah titik awal untuk perubahan yang luar biasa. Mulailah dengan menetapkan tujuan belajar yang jelas, buat jadwal yang nyaman, dan temukan metode belajar yang sesuai dengan dirimu. Dengarkan musik saat belajar, belajar bersama teman, atau gunakan media visual yang menyenangkan. 🚀 Setiap langkah kecil menuju kebiasaan belajar yang positif akan membawa dampak besar di masa depanmu!';
    } else {
        $suggestion = 'Motivasi belajarmu sudah berada dalam kondisi yang sangat baik! Ini adalah fondasi penting untuk mencapai keberhasilan dalam pendidikan maupun kehidupan. Terus jaga semangat belajar ini dengan mengeksplorasi hal-hal baru, mencari tantangan intelektual, dan tetap konsisten dalam usahamu. 💡 Jadilah inspirasi bagi orang di sekitarmu dengan menunjukkan bahwa belajar adalah perjalanan yang menyenangkan dan bermakna. Kamu sedang membangun masa depan gemilang!';
    }
} elseif ('Gaya Belajar') {
    if ($score >= 1 && $score <= 30) {
        $suggestion = 'Kamu termasuk dalam tipe pembelajar **Auditori**, yaitu seseorang yang paling efektif dalam menyerap informasi melalui pendengaran. Kamu mungkin menyukai belajar lewat diskusi, mendengarkan penjelasan verbal, podcast, atau rekaman audio. Suara dan irama membantu kamu memahami dan mengingat informasi dengan lebih baik. 🎧 Cobalah merekam pelajaranmu atau bergabung dengan kelompok belajar agar kamu bisa terus berkembang dengan gaya belajarmu yang unik ini!';
    } elseif ($score >= 31 && $score <= 60) {
        $suggestion = 'Gaya belajarmu adalah **Visual**. Kamu lebih mudah memahami konsep dan informasi melalui tampilan visual seperti diagram, grafik, warna, dan gambar. Kamu cenderung memperhatikan detail visual dan menyukai tampilan yang menarik secara estetika. 🖼️ Untuk mendukung gaya belajar ini, gunakan peta pikiran, catatan berwarna, dan video pembelajaran. Dunia visual adalah panggung terbaikmu untuk menguasai ilmu pengetahuan dengan penuh warna dan imajinasi!';
    } elseif ($score >= 61 && $score <= 100) {
        $suggestion = 'Kamu termasuk pembelajar **Kinestetik**, yaitu seseorang yang belajar paling baik melalui pengalaman langsung dan aktivitas fisik. Kamu menyukai praktik, eksperimen, dan gerakan tubuh dalam proses belajar. ✋🧠 Sesi praktik, roleplay, proyek nyata, atau permainan edukatif akan membantumu memahami materi lebih mendalam. Jangan ragu untuk bergerak dan berinteraksi saat belajar—itulah kekuatanmu!';
    } else {
        $suggestion = 'Skor gaya belajar kamu tampaknya berada di luar rentang yang ditentukan. Mohon periksa kembali input asesmen, atau konsultasikan hasilnya untuk penyesuaian lebih lanjut.';
    }
}


    // Kirim data ke view
    return view('result', [
        'session' => $session,
        'score' => $score,
        'suggestion' => $suggestion,
        'date' => $session->taken_at
    ]);
}



    public function storeFeedback(Request $request)
    {
    Feedback::create([
        'user_id' => Auth::id(),
        'session_id' => $request->input('session_id'),
        'feedback_text' => $request->input('feedback_text'),
    ]);

    return redirect()->back()->with('success', 'Feedback berhasil disimpan!');
    }

    public function showDetailWithChart($id)
    {
        $session = UserAssessmentSession::with(['user', 'results', 'feedback'])->findOrFail($id);

        // Ambil semua sesi yang terkait user ini untuk grafik
        $monthlyScores = UserAssessmentSession::where('user_id', $session->user_id)
            ->whereNotNull('taken_at')
            ->with('results')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->taken_at)->format('Y-m');
            })
            ->map(function ($sessions) {
                $totalScore = 0;
                $count = 0;
                foreach ($sessions as $session) {
                    foreach ($session->results as $result) {
                        $totalScore += $result->score;
                        $count++;
                    }
                }
                return $count > 0 ? round($totalScore / $count, 2) : 0;
            });

        return view('detail-with-chart', [
            'session' => $session,
            'monthlyScores' => $monthlyScores
        ]);
    }




}
