@extends('layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center ">
    <div class="w-full max-w-lg bg-white bg-opacity-90 backdrop-blur-md shadow-2xl rounded-3xl p-8 border border-purple-200">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-6">
            ✨Profil Kamu✨
        </h2>

        @if (session('success'))
            <div class="mb-4 text-green-700 font-semibold text-center bg-green-100 border border-green-300 px-4 py-2 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-gray-700 font-semibold mb-1">👤 Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full border border-purple-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-400"
                    required>
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">📧 Email</label>
                <div class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg">{{ $user->email }}</div>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">🎂 Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1" required>
                @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">🚻 Jenis Kelamin</label>
                <input type="text" name="gender" value="{{ $user->gender }}" 
                    class="w-full border border-purple-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-800"
                    readonly>
            </div>

            <div class="text-center pt-4">
                <button type="submit"
                    class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-pink-500 hover:to-purple-500 text-white font-bold py-2 px-6 rounded-full shadow-lg transition-all duration-300">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection