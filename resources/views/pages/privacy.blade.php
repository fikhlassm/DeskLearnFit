@extends('layouts.app')

@section('title', 'Kebijakan Privasi — LearnFit')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 mt-10">
        <h1 class="text-3xl font-bold text-ink-900 mb-6">Kebijakan Privasi LearnFit</h1>
        <p class="text-slate-600 mb-6">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="prose prose-slate max-w-none">
            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">1. Informasi yang Kami Kumpulkan</h2>
            <p class="mb-4">Kami mengumpulkan informasi dasar saat Anda mendaftar, seperti nama lengkap dan alamat email. Kami juga menyimpan data aktivitas belajar Anda seperti nilai kuis, riwayat sesi belajar, dan catatan jurnal untuk mempersonalisasi pengalaman belajar Anda.</p>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">2. Penggunaan Informasi</h2>
            <p class="mb-4">Data yang kami kumpulkan digunakan untuk:</p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>Menyediakan fitur-fitur pembelajaran yang sesuai dengan kebutuhan Anda.</li>
                <li>Melacak progress belajar dan memberikan analitik performa.</li>
                <li>Memperbaiki dan mengembangkan fitur platform di masa mendatang.</li>
            </ul>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">3. Keamanan Data</h2>
            <p class="mb-4">Kami sangat menghargai privasi Anda dan menerapkan standar keamanan yang wajar untuk melindungi data pribadi dari akses, perubahan, atau pengungkapan yang tidak sah. Namun, tidak ada sistem yang 100% aman di internet.</p>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">4. Pembagian Data</h2>
            <p class="mb-4">Kami <strong>tidak akan menjual</strong> atau menyewakan informasi pribadi Anda kepada pihak ketiga. Informasi hanya dapat dibagikan dalam kondisi hukum yang mewajibkan, atau jika diperlukan untuk memfasilitasi integrasi dengan platform pihak ketiga atas izin Anda (seperti Google Login).</p>

            <h2 class="text-xl font-semibold mt-8 mb-4 text-ink-900">5. Hubungi Kami</h2>
            <p class="mb-4">Jika Anda memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi tim dukungan kami melalui halaman <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">Kontak</a>.</p>
            
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
