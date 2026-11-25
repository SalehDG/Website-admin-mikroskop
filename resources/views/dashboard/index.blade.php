@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="page-heading"><div><h1>License Overview</h1><p>Ringkasan serial code Mikroskop Biologi Digital.</p></div><a href="{{ route('serial-codes.index') }}" class="btn primary"><span>＋</span> Buat Serial Code</a></div>
<div class="stat-grid">
    <article class="stat-card"><div class="stat-icon blue">▱</div><div><span>Total Serial Code</span><strong>{{ number_format($total, 0, ',', '.') }}</strong></div><div class="stat-decoration">↗</div></article>
    <article class="stat-card"><div class="stat-icon green">✓</div><div><span>Sudah Digunakan</span><strong>{{ number_format($used, 0, ',', '.') }}</strong></div><span class="stat-pill green-pill">{{ $total ? number_format($used/$total*100, 1, ',', '.') : '0,0' }}%</span></article>
    <article class="stat-card"><div class="stat-icon amber">◷</div><div><span>Belum Digunakan</span><strong>{{ number_format($unused, 0, ',', '.') }}</strong></div><span class="stat-pill amber-pill">{{ $total ? number_format($unused/$total*100, 1, ',', '.') : '0,0' }}%</span></article>
</div>
<div class="dashboard-grid">
    <article class="panel trend-panel"><div class="panel-heading"><div><h2>Tren Penggunaan Serial Code</h2><p>Jumlah serial code yang digunakan dalam 14 hari terakhir.</p></div><span class="range-chip">14 Hari Terakhir⌄</span></div>
        @php $maxValue = max(1, $trend->max('value')); $points = $trend->values()->map(fn($item, $i) => ($i * 57 + 30) . ',' . (170 - ($item['value'] / $maxValue * 130)))->implode(' '); @endphp
        <div class="chart-wrap"><div class="chart-y-labels"><span>{{ $maxValue }}</span><span>{{ ceil($maxValue*.75) }}</span><span>{{ ceil($maxValue*.5) }}</span><span>{{ ceil($maxValue*.25) }}</span><span>0</span></div><svg viewBox="0 0 830 200" preserveAspectRatio="none" class="trend-chart" role="img" aria-label="Grafik penggunaan serial 14 hari">
            <defs><linearGradient id="chartFill" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#69b9fa" stop-opacity=".28"/><stop offset="1" stop-color="#69b9fa" stop-opacity=".02"/></linearGradient></defs>
            @for($line=0;$line<5;$line++)<line x1="0" y1="{{ $line*42 }}" x2="830" y2="{{ $line*42 }}" class="chart-gridline"/>@endfor
            <polygon points="{{ $points }} 800,190 30,190" fill="url(#chartFill)"/>
            <polyline points="{{ $points }}" fill="none" stroke="#38a3f5" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
            @foreach($trend as $i=>$item)<circle cx="{{ $i*57+30 }}" cy="{{ 170-($item['value']/$maxValue*130) }}" r="4" fill="#fff" stroke="#38a3f5" stroke-width="2.5"/>@endforeach
        </svg></div><div class="chart-x-labels">@foreach($trend as $item)<span>{{ $item['label'] }}</span>@endforeach</div>
    </article>
    <article class="panel distribution-panel"><div class="panel-heading"><div><h2>Distribusi Serial Code</h2><p>Status seluruh serial.</p></div></div><div class="donut-area"><div class="donut" style="background:conic-gradient(#59c59a 0 {{ $total ? ($used/$total*100) : 0 }}%, #f4bd53 {{ $total ? ($used/$total*100) : 0 }}% 100%)"><div class="donut-center"><strong>{{ number_format($total, 0, ',', '.') }}</strong><span>Serial Code</span></div></div><div class="donut-legend"><div><i class="legend-dot green-dot"></i><span>Sudah Digunakan</span><b>{{ number_format($used, 0, ',', '.') }}</b></div><div><i class="legend-dot amber-dot"></i><span>Belum Digunakan</span><b>{{ number_format($unused, 0, ',', '.') }}</b></div></div></div></article>
</div>
<div class="panel recent-panel"><div class="panel-heading"><div><h2>Serial Code Terbaru</h2><p>Serial code yang baru ditambahkan ke sistem.</p></div><a class="text-link" href="{{ route('serial-codes.index') }}">Lihat Semua <span>→</span></a></div><div class="recent-list">
    @forelse($recent as $serial)<div class="recent-item"><div class="recent-symbol {{ $serial->status === 'used' ? 'used-symbol' : 'unused-symbol' }}">{{ $serial->status === 'used' ? '✓' : '⌁' }}</div><div class="recent-detail"><strong>{{ $serial->code }}</strong><small>{{ $serial->notes ?: 'Serial baru dibuat' }}</small></div><span class="status-badge {{ $serial->status === 'used' ? 'status-used' : 'status-unused' }}">{{ $serial->status === 'used' ? 'Digunakan' : 'Belum Digunakan' }}</span><time>{{ $serial->created_at->diffForHumans() }}</time></div>@empty<div class="empty-state">Belum ada serial code. Buat serial pertama kamu.</div>@endforelse
</div></div>
@endsection
