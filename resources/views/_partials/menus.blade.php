<li class="nav-item">
    <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Dashboard
        </p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
        <i class="nav-icon fas fa-users"></i>
        <p>
            Data Pengguna
        </p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">
        <i class="nav-icon fas fa-building"></i>
        <p>
            Data Ruangan
        </p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('checklist-records.*') ? 'active' : '' }}" href="{{ route('checklist-records.index') }}">
        <i class="nav-icon fas fa-check"></i>
        <p>
            Pengecekan P3K
        </p>
    </a>
</li>

@if (in_array(auth()->user()->role, ['admin', 'atasan']))
    <li class="nav-item">
        <a class="nav-link {{ Route::is('approval-records.*') ? 'active' : '' }}" href="{{ route('approval-records.index') }}">
            <i class="nav-icon fas fa-clipboard-check"></i>
            <p>
                Persetujuan Pengecekan
            </p>
        </a>
    </li>
@endif

<li class="nav-item">
    <a class="nav-link {{ Route::is('print-records') ? 'active' : '' }}" href="{{ route('print-records') }}">
        <i class="nav-icon fas fa-file"></i>
        <p>
            Cetak Laporan
        </p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('activity-logs.*') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">
        <i class="nav-icon fas fa-history"></i>
        <p>
            Log Aktivitas
        </p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
        <i class="nav-icon fas fa-user-cog"></i>
        <p>
            Profile
        </p>
    </a>
</li>
