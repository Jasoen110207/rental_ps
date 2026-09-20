@extends('layouts.app')

@section('title', 'Requests - TambahBang')
@section('page_title', 'Customer Requests Hub')

@section('content')
<div class="flex flex-col gap-6">
  <div class="flex items-center gap-2 overflow-x-auto pb-1">
    <a href="{{ route('kasir.request', ['tab' => 'pending']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'pending' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Menunggu ({{ $counts['pending'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'approved']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'approved' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Disetujui ({{ $counts['approved'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'rejected']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'rejected' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Ditolak ({{ $counts['rejected'] }})</a>
    <a href="{{ route('kasir.request', ['tab' => 'all']) }}" class="px-4 py-2.5 text-xs font-bold border-2 border-on-surface whitespace-nowrap {{ $filter === 'all' ? 'bg-on-surface text-white' : 'bg-surface-container-lowest hover:bg-surface-container' }}">Semua ({{ $counts['all'] }})</a>
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

  // Polling tiap 15 detik
  setInterval(fetchLatestRequests, 15000);

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
