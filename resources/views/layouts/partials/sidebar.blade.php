<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/logo.png')">

    <!-- Add Sidebar Menu Items Here -->

    <x-maz-sidebar-item name="Dashboard" :link="route('dashboard')" icon="bi bi-grid-fill"></x-maz-sidebar-item>
    <!-- <x-maz-sidebar-item name="Siswa" icon="bi bi-person">
        <x-maz-sidebar-sub-item name="Daftar Siswa" :link="route('components.accordion')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    
    <x-maz-sidebar-item name="Pembimbing" icon="bi bi-people">
        <x-maz-sidebar-sub-item name="Daftar Pembimbing" :link="route('components.accordion')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item> -->
   
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'guru')
    <!-- Admin -->
    <x-maz-sidebar-item name="Pengguna" icon="bi bi-people">
        @if(Auth::user()->role == 'admin')
            <x-maz-sidebar-sub-item name="Daftar Pengguna" :link="route('users.index')"></x-maz-sidebar-sub-item>
            <x-maz-sidebar-sub-item name="Daftar Guru" :link="route('guru.index')"></x-maz-sidebar-sub-item>
        @endif

        @if(Auth::user()->role == 'guru')
            <x-maz-sidebar-sub-item name="Daftar Siswa" :link="route('siswa.index')"></x-maz-sidebar-sub-item>
            <!-- <x-maz-sidebar-sub-item name="Daftar Pembimbing" :link="route('pembimbing.index')"></x-maz-sidebar-sub-item> -->
        @endif

    </x-maz-sidebar-item>
    @endif

    @if(Auth::user()->role == 'guru')
    <!-- Guru -->
    <x-maz-sidebar-item name="Pengabdian" icon="bi bi-briefcase">
        <x-maz-sidebar-sub-item name="Daftar Pengabdian" :link="route('pengabdian.index')"></x-maz-sidebar-sub-item>
        <!-- <x-maz-sidebar-sub-item name="Tambah Pengabdian" :link="route('pengabdian.index')"></x-maz-sidebar-sub-item> -->
    </x-maz-sidebar-item> 
    <x-maz-sidebar-item name="Mitra" icon="bi bi-building">
        <x-maz-sidebar-sub-item name="Daftar Mitra" :link="route('mitra.index')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    @endif

    @if(Auth::user()->role == 'siswa')
    <!-- Siswa -->
    <x-maz-sidebar-item name="Laporan" icon="bi bi-pencil">
        <x-maz-sidebar-sub-item name="Laporan Harian" :link="route('penilaian.index')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    @endif

    @if(Auth::user()->role == 'pembimbing')
    <!-- Pembimbing Mitra -->
    <x-maz-sidebar-item name="Penilaian" icon="bi bi-journal">
        <x-maz-sidebar-sub-item name="Daftar Siswa Pengabdian" :link="route('penilaian.index')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    <x-maz-sidebar-item name="Perusahaan Saya" :link="route('perusahaan')" icon="bi bi-building"></x-maz-sidebar-item>
    @endif

</x-maz-sidebar>