<x-app-layout>
    <x-slot name="header">
        <h3>Edit Pengguna</h3>
    </x-slot>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Pengguna</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf @method('PUT')
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    <br>
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    <br>
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                    <br>
                    <!-- <label>Role</label> -->
                    <!-- <select name="role" class="form-control" required>
                        <option value="pengguna" {{ $user->role == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="pembimbing" {{ $user->role == 'pembimbing' ? 'selected' : '' }}>Pembimbing</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select> -->

                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
