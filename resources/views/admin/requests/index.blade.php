@extends('layouts.admin')

@section('title', 'Customer Requests Hub')
@section('page_title', 'Customer Requests Hub')

@section('content')
<div class="flex flex-col gap-6">

  <!-- Header & Tabs -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b-2 border-on-surface">
    <div>
      <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface">Pusat Permintaan Pelanggan</h2>
      <p class="text-xs text-on-surface-variant font-medium mt-0.5">Kelola permintaan perpanjangan waktu dan pesanan F&B dari QR code meja.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-1 bg-surface-container-high p-1 border-2 border-on-surface neo-shadow-sm">
      <a href="{{ route('admin.requests.index', ['tab' => 'all']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'all' ? 'bg-primary text-on-primary neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Semua ({{ $counts['all'] }})
      </a>
      <a href="{{ route('admin.requests.index', ['tab' => 'pending']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'pending' ? 'bg-secondary-container text-on-secondary neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Pending ({{ $counts['pending'] }})
      </a>
      <a href="{{ route('admin.requests.index', ['tab' => 'approved']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'approved' ? 'bg-emerald-600 text-white neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Disetujui ({{ $counts['approved'] }})
      </a>
      <a href="{{ route('admin.requests.index', ['tab' => 'rejected']) }}" class="px-3 py-1.5 font-headline-sm text-xs font-bold uppercase transition {{ $filter === 'rejected' ? 'bg-error text-white neo-shadow-sm' : 'text-on-surface hover:bg-surface' }}">
        Ditolak ({{ $counts['rejected'] }})
      </a>
    </div>
  </div>

  <!-- Requests Table / Grid -->
  <div id="live-request-container">
    @include('admin.requests.partial-list')
  </div>

</div>
@endsection

@push('scripts')
<script>
  function fetchLatestRequests() {
    const url = new URL(window.location.href);
    url.pathname = '{{ route("admin.requests.api", [], false) }}';
    
    fetch(url.toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
      document.getElementById('live-request-container').innerHTML = html;
    })
    .catch(err => console.error('Gagal memuat request terbaru:', err));
  }

  setInterval(fetchLatestRequests, 15000);

  function processRequestAction(id, action, role) {
    if (!confirm(action === 'approve' ? 'Setujui request ini?' : 'Tolak request ini?')) return;
    
    let url = '';
    if (action === 'approve') {
      url = '/admin/requests/' + id + '/approve';
    } else {
      url = '/admin/requests/' + id + '/reject';
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
        fetchLatestRequests();
      } else {
        alert(data.message || 'Terjadi kesalahan.');
      }
    })
    .catch(err => console.error('Action failed:', err));
  }
</script>
@endpush
