@extends('layouts.app')
@section('content')

@php
$user = Auth::user();
$quizResult = $user->quiz_result;

$metodeLabel = [
    'pomodoro'     => ['label'=>'Pomodoro',      'color'=>'#2563EB','bg'=>'#EFF6FF','icon'=>'⌚', 'theme'=>'blue'],
    'active_recall'=> ['label'=>'Active Recall',  'color'=>'#7C3AED','bg'=>'#F5F3FF','icon'=>'🧠', 'theme'=>'violet'],
    'blurting'     => ['label'=>'Blurting',       'color'=>'#059669','bg'=>'#ECFDF5','icon'=>'✍️', 'theme'=>'emerald'],
    'feynman'      => ['label'=>'Feynman',        'color'=>'#D97706','bg'=>'#FFFBEB','icon'=>'🏫', 'theme'=>'amber'],
    'lainnya'      => ['label'=>'Lainnya',        'color'=>'#475569','bg'=>'#F1F5F9','icon'=>'📝', 'theme'=>'slate'],
];

// Determine active theme color
$activeMetode = request('metode');
if ($quizResult) {
    $activeMetode = $quizResult; // Force filter
}
$mTheme = $metodeLabel[$activeMetode] ?? null;

// Determine banner gradient based on theme
$bannerGradients = [
    'blue' => 'from-blue-700 to-blue-400',
    'violet' => 'from-violet-700 to-violet-400',
    'emerald' => 'from-emerald-700 to-emerald-400',
    'amber' => 'from-amber-700 to-amber-400',
    'slate' => 'from-slate-700 to-slate-400'
];
$bannerGradient = $mTheme ? $bannerGradients[$mTheme['theme']] : 'from-sky-400 to-blue-500';
@endphp

<div class="dash-page flex min-h-screen bg-slate-100 text-slate-900 font-sans">
    @include('dashboard._sidebar_siswa', ['active' => 'catatan'])

    <main class="dash-main flex-1 flex flex-col p-6 md:py-6 md:px-8 gap-6 overflow-x-hidden">
        {{-- Header / Judul Halaman --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button class="hamburger flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white lg:hidden" id="hamburgerBtn" aria-label="Buka Menu">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M3 5h14M3 10h14M3 15h14" stroke="#475569" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Catatan Belajar</h1>
                    <p class="text-sm text-slate-500 mt-1">Catat dan evaluasi proses belajarmu secara efektif.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm">{{ session('error') }}</div>
        @endif

        {{-- NEW BANNER / STATS ROW --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br {{ $bannerGradient }} p-8 md:p-10 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-8 transition-colors duration-500">
            <!-- Decorative circle -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col gap-2">
                <h2 class="text-3xl font-bold">Perkembanganmu</h2>
                <p class="text-white/80 max-w-md">Mencatat berarti mengingat dua kali. Evaluasi hasil belajarmu secara rutin agar lebih efektif.</p>
                
                <div class="flex flex-wrap gap-4 mt-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-3 border border-white/20">
                        <span class="block text-3xl font-black">{{ $totalJurnal }}</span>
                        <span class="block text-xs font-medium text-white/70 uppercase tracking-wider mt-1">Total Catatan</span>
                    </div>
                    @if($jurnalTerbaru)
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-3 border border-white/20">
                        <span class="block text-3xl font-black">{{ $jurnalTerbaru->tanggal->format('d M') }}</span>
                        <span class="block text-xs font-medium text-white/70 uppercase tracking-wider mt-1">Terakhir</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="relative z-10">
                <button class="bg-white text-slate-900 font-bold px-6 py-4 rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2" onclick="openModal('modalTambah')">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Catatan Baru
                </button>
            </div>
        </div>

        {{-- FILTER & CONTROLS --}}
        <div class="flex items-center justify-between mt-2">
            <form method="GET" action="{{ route('catatan.index') }}" class="flex flex-wrap items-center gap-3">
                @if($quizResult)
                    <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-sm font-semibold text-slate-500">Metode Anda:</span>
                        <span class="text-sm font-bold flex items-center gap-1.5" style="color:{{ $metodeLabel[$quizResult]['color'] }}">
                            {{ $metodeLabel[$quizResult]['icon'] }} {{ $metodeLabel[$quizResult]['label'] }} 🔒
                        </span>
                        <input type="hidden" name="metode" value="{{ $quizResult }}">
                    </div>
                @else
                    <div class="relative">
                        <select name="metode" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium transition-shadow cursor-pointer">
                            <option value="">Semua Metode</option>
                            @foreach($metodeLabel as $key => $m)
                            <option value="{{ $key }}" {{ request('metode') === $key ? 'selected' : '' }}>{{ $m['icon'] }} {{ $m['label'] }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @if(request('metode'))
                        <a href="{{ route('catatan.index') }}" class="text-sm font-semibold text-slate-400 hover:text-slate-700 transition-colors">Reset Filter</a>
                    @endif
                @endif
            </form>
        </div>

        {{-- DAFTAR CATATAN --}}
        @if($jurnalList->isEmpty())
        <div class="flex flex-col items-center justify-center text-center p-12 bg-white border border-slate-200 rounded-3xl shadow-sm my-4">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada catatan belajar</h3>
            <p class="text-slate-500 mb-8 max-w-sm">Mulai catat proses belajarmu hari ini untuk melacak perkembangan.</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all hover:-translate-y-0.5" onclick="openModal('modalTambah')">
                + Tambah Catatan Pertama
            </button>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-10">
            @foreach($jurnalList as $jurnal)
            @php 
                $m = $metodeLabel[$jurnal->metode_yang_digunakan] ?? null; 
                $cardBorder = $m ? $m['color'] : '#E2E8F0';
            @endphp
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 hover:shadow-lg transition-all duration-300 group flex flex-col h-full hover:-translate-y-1 relative overflow-hidden">
                <!-- Top Color Bar -->
                <div class="absolute top-0 left-0 w-full h-1.5" style="background-color: {{ $cardBorder }}"></div>
                
                <div class="flex justify-between items-start mb-4 mt-2">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-50 rounded-xl p-2.5 text-center border border-slate-100 min-w-[56px]">
                            <span class="block text-xl font-extrabold text-slate-800 leading-none">{{ $jurnal->tanggal->format('d') }}</span>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $jurnal->tanggal->format('M') }}</span>
                        </div>
                        @if($m)
                        <div class="flex flex-col gap-1.5">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md inline-flex items-center w-max" style="background:{{ $m['bg'] }};color:{{ $m['color'] }}">{{ $m['icon'] }} {{ $m['label'] }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-col items-end gap-1.5">
                        @if($jurnal->rating_efektivitas)
                        <div class="flex gap-0.5">
                            @for($i=1;$i<=5;$i++)
                                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="{{ $i<=$jurnal->rating_efektivitas ? '#FBBF24' : '#E2E8F0' }}"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        @endif
                        @if($jurnal->durasi_menit)
                        <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $jurnal->durasi_menit }} mnt</span>
                        @endif
                    </div>
                </div>

                @if($jurnal->judul)
                <h3 class="text-base font-bold text-slate-800 mb-2 line-clamp-1">{{ $jurnal->judul }}</h3>
                @endif
                <p class="text-sm text-slate-600 mb-6 leading-relaxed flex-1">{{ Str::limit($jurnal->isi_jurnal, 150) }}</p>
                
                <div class="mt-auto flex items-center gap-2 pt-4 border-t border-slate-100 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 text-sm font-semibold py-2 rounded-lg transition-colors text-center" onclick="openEditModal({{ $jurnal->id }})">Edit</button>
                    <form method="POST" action="{{ route('catatan.destroy', $jurnal) }}" class="flex-1 m-0" onsubmit="return confirm('Hapus catatan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold py-2 rounded-lg transition-colors text-center">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $jurnalList->links() }}</div>
        @endif
    </main>

    <!-- Overlay sidebar mobile -->
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden" id="sidebarOverlay"></div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal-overlay hidden fixed inset-0 bg-slate-900/45 backdrop-blur-sm z-[100] items-center justify-center p-4 transition-all duration-200" id="modalTambah">
    <div class="modal bg-white rounded-3xl w-full max-w-lg shadow-2xl scale-95 opacity-0 transition-all duration-300 transform">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-800">Catatan Belajar Baru</h2>
            <button class="text-slate-400 hover:text-slate-600 transition-colors p-1" onclick="closeModal('modalTambah')"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <form method="POST" action="{{ route('catatan.store') }}">
            @csrf
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                @if($errors->any())
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-xl mb-5 text-sm">{{ $errors->first() }}</div>
                @endif
                
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi <span class="text-slate-400 font-normal">(menit)</span></label>
                        <input type="number" name="durasi_menit" value="{{ old('durasi_menit') }}" min="1" max="1440" placeholder="cth: 45" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Catatan</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Apa yang kamu pelajari?" maxlength="200" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Metode Belajar <span class="text-red-500">*</span></label>
                        <select name="metode_yang_digunakan" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none bg-white">
                            <option value="">-- Pilih Metode --</option>
                            @foreach($metodeLabel as $key => $m)
                                @if($quizResult && $quizResult !== $key)
                                    <option value="{{ $key }}" disabled>{{ $m['icon'] }} {{ $m['label'] }} 🔒</option>
                                @else
                                    <option value="{{ $key }}" {{ old('metode_yang_digunakan', $quizResult) === $key ? 'selected' : '' }}>{{ $m['icon'] }} {{ $m['label'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Rating Efektivitas</label>
                        <div class="flex items-center gap-1.5 mt-2" id="starInput">
                            @for($i=1;$i<=5;$i++)
                            <input type="radio" name="rating_efektivitas" value="{{ $i }}" id="star{{ $i }}" {{ old('rating_efektivitas')==$i ? 'checked' : '' }} class="hidden">
                            <label for="star{{ $i }}" class="cursor-pointer transition-transform hover:scale-110" data-val="{{ $i }}">
                                <svg class="w-6 h-6 star-svg text-slate-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </label>
                            @endfor
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Catatan <span class="text-red-500">*</span></label>
                    <textarea name="isi_jurnal" rows="4" required maxlength="5000" placeholder="Tulis rincian apa yang kamu pelajari hari ini..." class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-y">{{ old('isi_jurnal') }}</textarea>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/50 rounded-b-3xl">
                <button type="button" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-md transition-all hover:-translate-y-0.5">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal-overlay hidden fixed inset-0 bg-slate-900/45 backdrop-blur-sm z-[100] items-center justify-center p-4 transition-all duration-200" id="modalEdit">
    <div class="modal bg-white rounded-3xl w-full max-w-lg shadow-2xl scale-95 opacity-0 transition-all duration-300 transform">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-800">Edit Catatan Belajar</h2>
            <button class="text-slate-400 hover:text-slate-600 transition-colors p-1" onclick="closeModal('modalEdit')"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <form method="POST" id="formEdit" action="">
            @csrf @method('PUT')
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="e_tanggal" max="{{ date('Y-m-d') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi <span class="text-slate-400 font-normal">(menit)</span></label>
                        <input type="number" name="durasi_menit" id="e_durasi" min="1" max="1440" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Catatan</label>
                    <input type="text" name="judul" id="e_judul" maxlength="200" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Metode Belajar <span class="text-red-500">*</span></label>
                        <select name="metode_yang_digunakan" id="e_metode" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none bg-white">
                            <option value="">-- Pilih Metode --</option>
                            @foreach($metodeLabel as $key => $m)
                                @if($quizResult && $quizResult !== $key)
                                    <option value="{{ $key }}" disabled>{{ $m['icon'] }} {{ $m['label'] }} 🔒</option>
                                @else
                                    <option value="{{ $key }}">{{ $m['icon'] }} {{ $m['label'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Rating Efektivitas</label>
                        <input type="number" name="rating_efektivitas" id="e_rating" min="1" max="5" placeholder="1–5" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Catatan <span class="text-red-500">*</span></label>
                    <textarea name="isi_jurnal" id="e_isi" rows="4" required maxlength="5000" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-y"></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/50 rounded-b-3xl">
                <button type="button" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 transition-colors" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-md transition-all hover:-translate-y-0.5">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
const sidebar = document.querySelector('.sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const hamburgerBtn = document.getElementById('hamburgerBtn');
if (hamburgerBtn && sidebarOverlay) {
    hamburgerBtn.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar--open');
        sidebarOverlay.classList.toggle('hidden');
    });
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('sidebar--open');
        sidebarOverlay.classList.add('hidden');
    });
}

function openModal(id) {
    const overlay = document.getElementById(id);
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    // slight delay for animation
    setTimeout(() => {
        overlay.querySelector('.modal').classList.remove('scale-95', 'opacity-0');
        overlay.querySelector('.modal').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal(id) {
    const overlay = document.getElementById(id);
    overlay.querySelector('.modal').classList.add('scale-95', 'opacity-0');
    overlay.querySelector('.modal').classList.remove('scale-100', 'opacity-100');
    setTimeout(() => {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }, 300);
}

async function openEditModal(id) {
    try {
        const res = await fetch(`/dashboard/siswa/catatan-belajar/${id}/edit`);
        if (!res.ok) throw new Error('Gagal memuat data');
        const data = await res.json();
        
        document.getElementById('formEdit').action = `/dashboard/siswa/catatan-belajar/${id}`;
        document.getElementById('e_tanggal').value = data.tanggal || '';
        document.getElementById('e_durasi').value = data.durasi_menit || '';
        document.getElementById('e_judul').value = data.judul || '';
        document.getElementById('e_metode').value = data.metode_yang_digunakan || '';
        document.getElementById('e_rating').value = data.rating_efektivitas || '';
        document.getElementById('e_isi').value = data.isi_jurnal || '';
        
        openModal('modalEdit');
    } catch (e) {
        alert('Terjadi kesalahan: ' + e.message);
    }
}

// Star rating behavior
document.querySelectorAll('#starInput label').forEach(label => {
    label.addEventListener('click', function() {
        const val = this.dataset.val;
        document.querySelectorAll('#starInput label svg').forEach((svg, index) => {
            if (index < val) {
                svg.classList.remove('text-slate-300');
                svg.classList.add('text-amber-400');
            } else {
                svg.classList.remove('text-amber-400');
                svg.classList.add('text-slate-300');
            }
        });
    });
});

// Initial star coloring
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('#starInput input:checked');
    if (checked) {
        const val = checked.value;
        document.querySelectorAll('#starInput label svg').forEach((svg, index) => {
            if (index < val) {
                svg.classList.remove('text-slate-300');
                svg.classList.add('text-amber-400');
            }
        });
    }
});
</script>
<style>
/* Reset & Sidebar Styles for compatibility with _sidebar_siswa */
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.sidebar{width:240px;flex-shrink:0;background:#fff;border-right:1px solid #E2E8F0;display:flex;flex-direction:column;padding:1.25rem 0;position:sticky;top:0;height:100vh;overflow-y:auto;}
.sidebar__brand{display:flex;align-items:center;gap:.7rem;padding:.25rem 1.25rem 1.25rem;border-bottom:1px solid #F1F5F9;margin-bottom:.5rem;}
.sidebar__brand-name{font-size:.95rem;font-weight:700;color:#0F172A;line-height:1.2;}
.sidebar__brand-sub{font-size:.68rem;color:#94A3B8;}
.sidebar__nav{flex:1;display:flex;flex-direction:column;gap:.15rem;padding:.5rem .75rem;}
.sidebar__link{display:flex;align-items:center;gap:.7rem;padding:.65rem .85rem;border-radius:10px;text-decoration:none;font-size:.85rem;font-weight:500;color:#475569;transition:background .18s,color .18s;}
.sidebar__link:hover{background:#F8FAFC;color:#2563EB;}
.sidebar__link--active{background:#EFF6FF;color:#2563EB;font-weight:600;}
.sidebar__user{display:flex;align-items:center;gap:.7rem;padding:.85rem 1.25rem;border-top:1px solid #F1F5F9;margin:.5rem .75rem 0;border-radius:10px;}
.sidebar__avatar{width:36px;height:36px;border-radius:50%;background:#E2E8F0;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sidebar__user-name{font-size:.82rem;font-weight:600;color:#0F172A;}
.sidebar__user-role{font-size:.68rem;color:#94A3B8;}
@media(max-width:1024px){
  .sidebar{position:fixed;left:-240px;transition:left .25s ease;z-index:999;box-shadow:4px 0 24px rgba(0,0,0,.05);}
  .sidebar--open{left:0;}
}
/* Ensure tailwind doesn't break star ratings */
.star-svg { transition: color 0.15s ease, transform 0.1s ease; }
</style>
@endsection
