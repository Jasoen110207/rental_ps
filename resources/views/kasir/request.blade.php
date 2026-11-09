@extends('layouts.app')

@section('title', 'Requests - TambahBang')
@section('page_title', 'Customer Requests Hub')

@section('content')
<div class="flex flex-col gap-6">
  <div class="flex items-center gap-2 overflow-x-auto pb-1 max-w-full">
    <a href="{{ route('kasir.request', ['tab' => 'pending']) }}" class="filter-chip px-3 py-1.5 text-xs {{ $filter === 'pending' ? 'is-active' : '' }}">Menunggu ({{ $counts['pending'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'approved']) }}" class="filter-chip px-3 py-1.5 text-xs {{ $filter === 'approved' ? 'is-active' : '' }}">Disetujui ({{ $counts['approved'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'rejected']) }}" class="filter-chip px-3 py-1.5 text-xs {{ $filter === 'rejected' ? 'is-active' : '' }}">Ditolak ({{ $counts['rejected'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'all']) }}" class="filter-chip px-3 py-1.5 text-xs {{ $filter === 'all' ? 'is-active' : '' }}">Semua ({{ $counts['all'] }})</a>
  </div>

  <div id="live-request-container">
    @include('kasir.partial-list')
  </div>
</div>

@push('scripts')
<script>
  function fetchLatestRequests() {
    // Ambil URL dengan query string (tab filter & page) yang sedang aktif
    const url = new URL(window.location.href);
    // Ganti path ke api endpoint
    url.pathname = '{{ route("kasir.request.api", [], false) }}';
    
    fetch(url.toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
      document.getElementById('live-request-container').innerHTML = html;
    })
    .catch(err => console.error('Gagal memuat request terbaru:', err));
  }

  // Polling tiap 3 detik
  setInterval(fetchLatestRequests, 3000);

  function processRequestAction(id, action, role) {
    if (!confirm(action === 'approve' ? 'Setujui request ini?' : 'Tolak request ini?')) return;
    
    let url = '';
    if (action === 'approve') {
      url = '/kasir/request/' + id + '/approve';
    } else {
      url = '/kasir/request/' + id + '/reject';
    }

    fetch(url, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      }
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        // Segera render ulang tanpa menunggu 15 detik
        fetchLatestRequests();
      } else {
        alert(data.message || 'Terjadi kesalahan.');
      }
    })
    .catch(err => console.error('Action failed:', err));
  }
</script>
@endpush
@endsection
