@extends('layouts.admin')

@section('title', 'Manajemen Kasir')
@section('page_title', 'Kelola Akun Kasir & Admin')

@section('content')
<div class="flex flex-col gap-6">

  <!-- Header -->
  <div class="flex items-center justify-between pb-4 border-b-2 border-on-surface">
    <div>
      <h2 class="text-xl font-headline-lg font-black uppercase text-on-surface">Daftar Akun Pengguna</h2>
      <p class="text-xs text-on-surface-variant font-medium mt-0.5">Kelola data akun admin dan kasir, serta reset PIN.</p>
    </div>

    <button onclick="openUserModal()" class="py-2.5 px-5 bg-primary text-on-primary font-headline-lg text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container flex items-center gap-1.5">
      <span class="material-symbols-outlined text-base">add</span>
      <span>Tambah Akun Baru</span>
    </button>
  </div>

  <!-- Users List (Table-like Grid) -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach ($users as $user)
      <div class="p-5 bg-surface-container-lowest border-2 border-on-surface neo-shadow flex flex-col justify-between">
        <div>
          <!-- Header -->
          <div class="flex items-start justify-between pb-3 border-b-2 border-on-surface">
            <div>
              <span class="text-[9px] font-label-sm font-bold uppercase px-1.5 py-0.5 border border-on-surface bg-primary-fixed text-primary">
                {{ strtoupper($user->role ?? 'KASIR') }}
              </span>
              <h3 class="font-headline-lg font-black text-lg uppercase mt-1 text-on-surface">{{ $user->name }}</h3>
              <p class="font-label-sm text-xs font-black text-on-surface-variant mt-0.5">
                {{ $user->email }}
              </p>
            </div>
          </div>
        </div>

        <!-- Action Row -->
        <div class="pt-3 mt-4 border-t-2 border-on-surface flex items-center justify-between gap-2">
          <button type="button" onclick="editUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role ?? 'kasir' }}')" class="py-1.5 px-3 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm btn-press hover:bg-surface-container-high flex items-center gap-1 w-full justify-center">
            <span class="material-symbols-outlined text-sm">edit</span>
            <span>Edit</span>
          </button>
          
          @if(auth()->id() !== $user->id)
          <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline w-full" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="py-1.5 px-3 bg-error text-white border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm btn-press hover:bg-red-700 flex items-center gap-1 w-full justify-center">
              <span class="material-symbols-outlined text-sm">delete</span>
              <span>Hapus</span>
            </button>
          </form>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  @if($users->hasPages())
    <div class="mt-4">
      {{ $users->links() }}
    </div>
  @endif

</div>

<!-- User Modal -->
<div id="modal-user" class="fixed inset-0 z-50 flex items-center justify-center bg-on-surface/50 backdrop-blur-xs p-4 hidden">
  <div class="w-full max-w-md bg-surface-container-lowest border-2 border-on-surface p-6 neo-shadow-lg relative animate-in fade-in zoom-in duration-150">
    <div class="flex items-center justify-between pb-3 border-b-2 border-on-surface mb-4">
      <h3 class="font-headline-lg font-black uppercase text-base" id="user-modal-title">Tambah Akun Baru</h3>
      <button onclick="closeUserModal()" class="p-1 border border-on-surface hover:bg-surface-container-high btn-press">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>

    <form id="user-form" method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col gap-3">
      @csrf
      <input type="hidden" name="_method" id="user-form-method" value="POST">

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Nama Lengkap</label>
        <input type="text" name="name" id="user-name" required placeholder="Contoh: Budi Santoso" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>
      
      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Email / Username</label>
        <input type="email" name="email" id="user-email" required placeholder="Contoh: kasir1@rentalps.com" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-body-md text-xs neo-shadow-sm">
      </div>

      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Role</label>
          <select name="role" id="user-role" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold uppercase neo-shadow-sm">
            <option value="kasir">Kasir</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        
        <div>
          <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">PIN (6 Digit)</label>
          <input type="text" name="pin" id="user-pin" pattern="[0-9]{6}" maxlength="6" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm" placeholder="Misal: 123456">
          <span class="text-[9px] text-on-surface-variant block mt-1" id="user-pin-help">Wajib diisi</span>
        </div>
      </div>

      <div>
        <label class="block font-headline-sm text-xs uppercase font-bold tracking-wider mb-1">Password Baru</label>
        <input type="password" name="password" id="user-password" minlength="4" class="w-full px-3 py-2 bg-surface border-2 border-on-surface font-headline-sm text-xs font-bold neo-shadow-sm">
        <span class="text-[9px] text-on-surface-variant block mt-1" id="user-password-help">Wajib diisi</span>
      </div>

      <div class="flex items-center justify-end pt-3 border-t-2 border-on-surface mt-2">
        <div class="flex gap-3 ml-auto">
          <button type="button" onclick="closeUserModal()" class="py-2 px-4 border-2 border-on-surface font-headline-sm text-xs uppercase font-bold neo-shadow-sm btn-press hover:bg-surface-container-high">
            Batal
          </button>
          <button type="submit" class="py-2 px-5 bg-primary text-on-primary font-headline-sm text-xs uppercase tracking-wider font-black border-2 border-on-surface neo-shadow btn-press hover:bg-primary-container">
            Simpan Akun
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  function openUserModal() {
    document.getElementById('user-form').action = '{{ route("admin.users.store") }}';
    document.getElementById('user-form-method').value = 'POST';
    document.getElementById('user-name').value = '';
    document.getElementById('user-email').value = '';
    document.getElementById('user-role').value = 'kasir';
    document.getElementById('user-pin').value = '';
    document.getElementById('user-pin').required = true;
    document.getElementById('user-pin-help').innerText = 'Wajib diisi';
    document.getElementById('user-password').value = '';
    document.getElementById('user-password').required = true;
    document.getElementById('user-password-help').innerText = 'Wajib diisi';
    document.getElementById('user-modal-title').innerText = 'Tambah Akun Baru';
    document.getElementById('modal-user').classList.remove('hidden');
  }

  function editUser(id, name, email, role) {
    document.getElementById('user-form').action = '/admin/users/' + id;
    document.getElementById('user-form-method').value = 'PUT';
    document.getElementById('user-name').value = name;
    document.getElementById('user-email').value = email;
    document.getElementById('user-role').value = role;
    document.getElementById('user-pin').value = '';
    document.getElementById('user-pin').required = false;
    document.getElementById('user-pin-help').innerText = 'Kosongkan jika tidak ingin diubah';
    document.getElementById('user-password').value = '';
    document.getElementById('user-password').required = false;
    document.getElementById('user-password-help').innerText = 'Kosongkan jika tidak ingin diubah';
    document.getElementById('user-modal-title').innerText = 'Edit Akun — ' + name;
    document.getElementById('modal-user').classList.remove('hidden');
  }

  function closeUserModal() {
    document.getElementById('modal-user').classList.add('hidden');
  }
</script>
@endsection
