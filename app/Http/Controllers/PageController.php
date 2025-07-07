<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\UserAssessmentSession;
use App\Models\AssessmentResult;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Feedback;


class PageController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function loginPage()
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.assessments');
            }
        }
        return view('login');
    }

    public function registerPage()
    {
        return view('userReview.Register');
    }

    public function adminDashboard(Request $request)
{
    $username = $request->query('username', Auth::user()->name);
    $month = $request->query('month');
    $search = $request->query('search'); // Tambahkan parameter search

    $totalUsers = User::where('role_id', 2)->count();
    $totalAssessments = Assessment::count();

    $availableMonths = UserAssessmentSession::selectRaw('DATE_FORMAT(taken_at, "%Y-%m") as month')
        ->distinct()
        ->pluck('month')
        ->mapWithKeys(function ($item) {
            return [$item => Carbon::createFromFormat('Y-m', $item)->translatedFormat('F Y')];
        });

    $query = UserAssessmentSession::with(['user', 'assessment'])->orderBy('taken_at', 'desc');
    if ($month) {
        $query->whereRaw("DATE_FORMAT(taken_at, '%Y-%m') = ?", [$month]);
    }

    $totalCompletedSessions = $query->count();
    $recentSessions = $query->take(5)->get();

    $usersQuery = User::where('role_id', 2)->orderBy('created_at', 'desc');

    if ($search) {
        $usersQuery->where('name', 'like', '%' . $search . '%');
    }

    $users = $usersQuery->get();

    return view('dashboard', compact(
        'username', 'totalUsers', 'totalAssessments', 'totalCompletedSessions',
        'recentSessions', 'users', 'availableMonths' , 'usersQuery'
    ));
}

    public function adminProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateAdminProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $user->name = $request->name;
        $user->birth_date = $request->birth_date;
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function assessmentManagement(Request $request)
{
    $month = $request->query('month');

    $query = Assessment::withCount('questions')->orderBy('date_created', 'desc');

    if ($month) {
        $query->whereMonth('date_created', $month);
    }

    $assessments = $query->get();

    // Daftar bulan (opsional bisa gunakan helper Carbon)
    $months = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    return view('pengelolaanassessment', compact('assessments', 'months'));
}



    public function createAssessment()
    {
        return view('createassessment');
    }

    public function editAssessment(Assessment $assessment)
    {
        return view('admin.assessments.edit', compact('assessment'));
    }

    public function manageQuestions(Assessment $assessment)
    {
        $questions = $assessment->questions()->with('options')->get();
        return view('questions', compact('assessment', 'questions'));
    }

    public function userAssessments()
    {
        $assessments = Assessment::withCount('questions')->where('is_active', true)->get();
        $completedAssessments = UserAssessmentSession::where('user_id', Auth::id())
            ->pluck('assessment_id')
            ->toArray();

        return view('userReview/daftarassessmen', compact('assessments', 'completedAssessments'));
    }

    public function takeAssessment(Assessment $assessment)
    {
        $hasCompleted = UserAssessmentSession::where('user_id', Auth::id())
            ->where('assessment_id', $assessment->id)
            ->exists();

        if ($hasCompleted) {
            return redirect()->route('user.progress')
                ->with('message', 'Anda telah menyelesaikan asesmen ini sebelumnya.');
        }

        $questions = $assessment->questions()->with('options')->get();
        return view('userReview.userassessmen', compact('assessment', 'questions'));
    }

    public function userProfile()
    {
        $user = Auth::user();
        return view('userReview.profileUser', compact('user'));
    }

    public function updateUserProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        $user->name = $request->name;
        $user->birth_date = $request->birth_date;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function userProgress(Request $request)
    {
        $assessmentName = $request->query('assessment');

        $sessions = UserAssessmentSession::with(['assessment', 'results'])
            ->where('user_id', Auth::id())
            ->when($assessmentName, function ($query) use ($assessmentName) {
                $query->whereHas('assessment', function ($q) use ($assessmentName) {
                    $q->where('name', $assessmentName);
                });
            })
            ->orderBy('taken_at', 'desc')
            ->get();

        $assessmentData = [];
        foreach ($sessions as $session) {
            $name = $session->assessment->name;
            if (!isset($assessmentData[$name])) {
                $assessmentData[$name] = [];
            }
            $maxScore = $session->results->max('score');
            $assessmentData[$name][] = [
                'date' => $session->taken_at->translatedFormat('d F Y'),
                'score' => $maxScore
            ];
        }

        $monthlyScores = $sessions->groupBy(function ($item) {
            return Carbon::parse($item->taken_at)->format('F Y');
        })->map(function ($group) {
            $total = 0;
            $count = 0;
            foreach ($group as $session) {
                foreach ($session->results as $result) {
                    $total += $result->score;
                    $count++;
                }
            }
            return $count > 0 ? round($total / $count, 2) : 0;
        });

        $availableAssessments = Assessment::whereIn('id', $sessions->pluck('assessment_id')->unique())->pluck('name');

        return view('userReview.monitoring', compact('sessions', 'assessmentData', 'monthlyScores', 'availableAssessments', 'assessmentName'));
    }

    public function storeSomething(Request $request, $sessionId)
    {
        $request->validate([
            'feedback_text' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'session_id' => $sessionId,
            'feedback_text' => $request->input('feedback_text'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Feedback berhasil disimpan.');
    }

    public function showSessionDetail($id)
    {
        $session = UserAssessmentSession::with(['user', 'results', 'feedbacks'])->findOrFail($id);

        $allSessions = UserAssessmentSession::with('results')
            ->where('user_id', $session->user_id)
            ->get();

        $monthlyScores = $allSessions->groupBy(function ($item) {
            return Carbon::parse($item->taken_at)->format('F Y');
        })->map(function ($group) {
            $total = 0;
            $count = 0;
            foreach ($group as $session) {
                foreach ($session->results as $result) {
                    $total += $result->score;
                    $count++;
                }
            }
            return $count > 0 ? round($total / $count, 2) : 0;
        });

        return view('detail', [
            'session' => $session,
            'monthlyScores' => $monthlyScores,
        ]);
    }

    public function showUserProgress(Request $request, $id)
{
    $user = User::findOrFail($id);
    $assessmentName = $request->query('assessment');

    $sessions = UserAssessmentSession::with(['assessment', 'results', 'feedbacks']) // plural feedbacks
        ->where('user_id', $id)
        ->whereNotNull('taken_at')
        ->when($assessmentName, function ($query) use ($assessmentName) {
            $query->whereHas('assessment', function ($q) use ($assessmentName) {
                $q->where('name', $assessmentName);
            });
        })
        ->orderBy('taken_at', 'desc')
        ->get();

    $monthlyScores = $sessions->groupBy(function ($item) {
        return \Carbon\Carbon::parse($item->taken_at)->format('Y-m');
    })->map(function ($group) {
        $total = 0;
        $count = 0;
        foreach ($group as $session) {
            foreach ($session->results as $result) {
                $total += $result->score;
                $count++;
            }
        }
        return $count > 0 ? round($total / $count, 2) : 0;
    });

    $allFeedback = $sessions->flatMap(function ($session) {
        return $session->feedbacks;
    });

    $availableAssessments = $sessions->filter(function ($session) {
    return $session->assessment !== null;
    })->pluck('assessment.name')->unique()->values();


    return view('user_progress', [
        'user' => $user,
        'sessions' => $sessions,
        'monthlyScores' => $monthlyScores,
        'allFeedback' => $allFeedback,
        'availableAssessments' => $availableAssessments,
        'selectedAssessment' => $assessmentName,
    ]);
}

}

