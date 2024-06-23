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
            Checklist Records
        </p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('approval-records.*') ? 'active' : '' }}" href="{{ route('approval-records.index') }}">
        <i class="nav-icon fas fa-clipboard-check"></i>
        <p>
            Approval Records
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
