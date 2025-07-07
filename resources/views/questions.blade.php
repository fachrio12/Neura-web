@extends('layouts.app')

@section('title', 'Tambah Pertanyaan')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-purple-200 animate-fade-in">
    <div class="w-full max-w-3xl bg-white shadow-lg rounded-xl p-8 border border-blue-200">
        <h2 class="text-3xl font-bold text-center text-purple-700 mb-6 animate-fade-down">
            Tambahkan Pertanyaan untuk: <span class="text-blue-600">{{ $assessment->name }}</span>
        </h2>

        <form action="{{ route('admin.questions.multi_store') }}" method="POST" onsubmit="return handleSubmit(event)">
            @csrf
            <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

            <div id="questions-wrapper">
                <div class="question-block mb-6 border-t pt-4 relative animate-slide-in">
                    <h4 class="font-semibold text-purple-600 mb-2">Pertanyaan 1</h4>

                    <div class="mb-3">
                        <input type="text" name="questions[0][question_text]" class="w-full border border-blue-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400" placeholder="Masukkan pertanyaan..." required>
                    </div>

                    <div class="option-wrapper">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="questions[0][options][0][option_text]" class="w-3/4 border border-purple-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Teks opsi" required>
                            <input type="number" name="questions[0][options][0][score]" class="w-1/4 border border-purple-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Skor" required>
                        </div>
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="questions[0][options][1][option_text]" class="w-3/4 border rounded px-3 py-2" placeholder="Teks opsi" required>
                            <input type="number" name="questions[0][options][1][score]" class="w-1/4 border rounded px-3 py-2" placeholder="Skor" required>
                        </div>
                    </div>

                    <button type="button" class="text-sm text-blue-600 hover:underline mb-2 transition hover:text-purple-700" onclick="addOption(this)">
                        + Tambah Opsi
                    </button>
                </div>
            </div>

            <button type="button" onclick="addQuestion()" class="text-sm text-purple-700 hover:underline mb-4 font-medium transition hover:text-blue-600">
                + Tambah Pertanyaan Baru
            </button>

            <div class="text-center">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-2 rounded-full shadow-lg hover:scale-105 transition-transform">
                    Simpan Semua Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let questionIndex = 1;
let formSubmitted = false;

function addQuestion() {
    const wrapper = document.getElementById('questions-wrapper');

    const questionBlock = document.createElement('div');
    questionBlock.className = 'question-block mb-6 border-t pt-4 relative animate-slide-in';

    questionBlock.innerHTML = `
        <h4 class="font-semibold text-gray-700 mb-2">Pertanyaan ${questionIndex + 1}</h4>
        <div class="mb-3">
            <input type="text" name="questions[${questionIndex}][question_text]" class="w-full border rounded px-3 py-2" placeholder="Masukkan pertanyaan..." required>
        </div>
        <div class="option-wrapper">
            <div class="flex gap-2 mb-2">
                <input type="text" name="questions[${questionIndex}][options][0][option_text]" class="w-3/4 border rounded px-3 py-2" placeholder="Teks opsi" required>
                <input type="number" name="questions[${questionIndex}][options][0][score]" class="w-1/4 border rounded px-3 py-2" placeholder="Skor" required min="0" max="100">
            </div>
            <div class="flex gap-2 mb-2">
                <input type="text" name="questions[${questionIndex}][options][1][option_text]" class="w-3/4 border rounded px-3 py-2" placeholder="Teks opsi" required>
                <input type="number" name="questions[${questionIndex}][options][1][score]" class="w-1/4 border rounded px-3 py-2" placeholder="Skor" required min="0" max="100">
            </div>
        </div>
        <button type="button" class="text-sm text-blue-600 hover:underline mb-2" onclick="addOption(this)">
            + Tambah Opsi
        </button>
        <button type="button" onclick="removeQuestion(this)" class="absolute top-0 right-0 text-red-500 hover:text-red-700">&times;</button>
    `;

    wrapper.appendChild(questionBlock);
    questionIndex++;
}

function addOption(button) {
    const questionBlock = button.closest('.question-block');
    const optionWrapper = questionBlock.querySelector('.option-wrapper');

    const qIndex = Array.from(document.querySelectorAll('.question-block')).indexOf(questionBlock);
    const oIndex = optionWrapper.querySelectorAll('.flex').length;

    const newOption = document.createElement('div');
    newOption.className = 'flex gap-2 mb-2';
    newOption.innerHTML = `
        <input type="text" name="questions[${qIndex}][options][${oIndex}][option_text]" class="w-3/4 border rounded px-3 py-2" placeholder="Teks opsi" required>
        <input type="number" name="questions[${qIndex}][options][${oIndex}][score]" class="w-1/4 border rounded px-3 py-2" placeholder="Skor" required min="1" max="100">
        <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 text-lg">&times;</button>
    `;

    optionWrapper.appendChild(newOption);
}

function removeQuestion(button) {
    button.closest('.question-block').remove();
    updateQuestionNumbers();
}

function removeOption(button) {
    button.closest('.flex').remove();
}

function updateQuestionNumbers() {
    const questionBlocks = document.querySelectorAll('.question-block');
    questionBlocks.forEach((block, index) => {
        const label = block.querySelector('h4');
        label.textContent = `Pertanyaan ${index + 1}`;
    });
}

function handleBeforeUnload(e) {
    if (!formSubmitted) {
        e.preventDefault();
        e.returnValue = '';
    }
}

function handleSubmit(e) {
    const questionBlocks = document.querySelectorAll('.question-block');

    if (questionBlocks.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 pertanyaan.');
        return false;
    }

    for (let i = 0; i < questionBlocks.length; i++) {
        const options = questionBlocks[i].querySelectorAll('.option-wrapper .flex');
        if (options.length < 2) {
            e.preventDefault();
            alert(`Pertanyaan ${i + 1} harus memiliki minimal 2 opsi.`);
            return false;
        }

        for (let scoreInput of options) {
            const score = scoreInput.querySelector('input[type="number"]');
            const value = parseInt(score.value);
            if (isNaN(value) || value < 0 || value > 100) {
                e.preventDefault();
                alert(`Skor di Pertanyaan ${i + 1} harus antara 0 dan 100.`);
                return false;
            }
        }
    }

    formSubmitted = true;
    window.removeEventListener('beforeunload', handleBeforeUnload);
    return true;
}

document.querySelectorAll('.block-navigation').forEach(link => {
    link.addEventListener('click', function (e) {
        if (!formSubmitted) {
            e.preventDefault();
            alert('Harap simpan data terlebih dahulu sebelum berpindah halaman.');
        }
    });
});

window.addEventListener('beforeunload', handleBeforeUnload);

history.pushState(null, null, location.href);
window.addEventListener('popstate', function () {
    if (!formSubmitted) {
        alert('Harap simpan data sebelum kembali.');
        history.pushState(null, null, location.href);
    }
});
</script>
<style>
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in { animation: fade-in 0.5s ease-in-out; }

@keyframes fade-down {
  from { transform: translateY(-20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
.animate-fade-down { animation: fade-down 0.5s ease-in-out; }

@keyframes slide-in {
  from { transform: translateX(20px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.5s ease-in-out; }
</style>
@endsection
