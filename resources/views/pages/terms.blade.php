@extends('layouts.app')

@section('title', 'Syarat & Ketentuan — LearnFit')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mt-10">
        <h1 class="text-3xl font-bold text-ink-900 mb-6">Syarat & Ketentuan LearnFit</h1>
        <p class="text-slate-600 mb-6">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="prose prose-slate max-w-none">
            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">1. Pendahuluan</h2>
            <p class="mb-4">Selamat datang di LearnFit! Dengan mengakses dan menggunakan platform kami, Anda menyetujui syarat dan ketentuan yang berlaku. Harap baca dengan saksama sebelum mulai belajar atau mengajar di platform ini.</p>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">2. Akun Pengguna</h2>
            <p class="mb-4">Anda bertanggung jawab untuk menjaga kerahasiaan kata sandi dan akun Anda. Setiap aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda sepenuhnya. Kami berhak menonaktifkan akun jika ditemukan pelanggaran terhadap syarat ini.</p>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">3. Konten & Hak Cipta</h2>
            <p class="mb-4">Pengajar dapat mengunggah materi pembelajaran. Dengan mengunggah materi, Anda menjamin bahwa Anda memiliki hak atas konten tersebut. LearnFit tidak bertanggung jawab atas pelanggaran hak cipta yang dilakukan oleh pengguna.</p>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">4. Aturan Penggunaan</h2>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>Dilarang menggunakan platform untuk tujuan ilegal.</li>
                <li>Dilarang membagikan konten yang mengandung unsur kekerasan, kebencian, atau pornografi.</li>
                <li>Dilarang melakukan tindakan yang merusak atau membebani sistem LearnFit (seperti spamming).</li>
            </ul>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">5. Perubahan Syarat</h2>
            <p class="mb-4">LearnFit dapat memperbarui syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui email atau notifikasi di beranda.</p>
            
            <div class="mt-12 pt-8 border-t border-slate-200">
                <a href="/" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center gap-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
