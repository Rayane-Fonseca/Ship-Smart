<style>
    .nav-root {
        background: #fff;
        border-bottom: 1px solid #fce7f3;
        box-shadow: 0 1px 8px rgba(244,63,94,.06);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .nav-inner {
        width: 100%;
        padding: 0 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 62px;
        gap: 16px;
    }

    /* ── Logo ── */
    .nav-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .nav-logo-icon {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        box-shadow: 0 4px 10px rgba(244,63,94,.22);
        flex-shrink: 0;
    }

    .nav-logo-text {
        font-size: 16px;
        font-weight: 800;
        color: #1f2937;
        letter-spacing: -.01em;
    }

    .nav-logo-text span {
        color: #f43f5e;
    }

    /* ── Links centrais ── */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 2px;
        flex: 1;
        margin-left: 24px;
    }

    .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        text-decoration: none;
        transition: .15s ease;
        white-space: nowrap;
    }

    .nav-link:hover {
        color: #e11d48;
        background: #fff1f2;
    }

    .nav-link.active {
        color: #e11d48;
        background: #fff1f2;
        font-weight: 600;
    }

    .nav-link-icon {
        font-size: 15px;
        line-height: 1;
    }

    /* ── Direita: usuário ── */
    .nav-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    /* Dropdown wrapper */
    .nav-dropdown {
        position: relative;
    }

    .nav-user-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px 6px 6px;
        border-radius: 12px;
        border: 1px solid #fce7f3;
        background: #fff;
        cursor: pointer;
        transition: .15s ease;
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }

    .nav-user-btn:hover {
        background: #fdf2f8;
        border-color: #fbcfe8;
    }

    .nav-avatar {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .nav-user-name {
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .nav-chevron {
        color: #f472b6;
        font-size: 11px;
        transition: transform .2s ease;
    }

    /* Dropdown menu */
    .nav-dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        width: 200px;
        background: #fff;
        border: 1px solid #fce7f3;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,.10);
        overflow: hidden;
        z-index: 100;
    }

    .nav-dropdown-menu.open {
        display: block;
        animation: dropIn .15s ease;
    }

    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .nav-dropdown-header {
        padding: 14px 16px 10px;
        border-bottom: 1px solid #fdf2f8;
    }

    .nav-dropdown-name {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .nav-dropdown-email {
        font-size: 12px;
        color: #9ca3af;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .nav-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        font-size: 14px;
        color: #374151;
        text-decoration: none;
        transition: .13s ease;
        cursor: pointer;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        font-weight: 500;
    }

    .nav-dropdown-item:hover {
        background: #fdf2f8;
        color: #e11d48;
    }

    .nav-dropdown-item.danger:hover {
        background: #fff1f2;
        color: #b91c1c;
    }

    .nav-dropdown-divider {
        height: 1px;
        background: #fdf2f8;
        margin: 4px 0;
    }

    /* ── Hamburger (mobile) ── */
    .nav-hamburger {
        display: none;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #fce7f3;
        background: #fff;
        cursor: pointer;
        color: #f472b6;
        transition: .15s ease;
    }

    .nav-hamburger:hover {
        background: #fdf2f8;
    }

    /* ── Mobile menu ── */
    .nav-mobile {
        display: none;
        border-top: 1px solid #fdf2f8;
        padding: 12px 16px;
        background: #fff;
    }

    .nav-mobile.open {
        display: block;
    }

    .nav-mobile-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        text-decoration: none;
        transition: .13s ease;
        margin-bottom: 2px;
    }

    .nav-mobile-link:hover,
    .nav-mobile-link.active {
        color: #e11d48;
        background: #fff1f2;
        font-weight: 600;
    }

    .nav-mobile-user {
        padding: 14px 12px 10px;
        border-top: 1px solid #fdf2f8;
        margin-top: 8px;
    }

    .nav-mobile-user-name {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
    }

    .nav-mobile-user-email {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
        margin-bottom: 10px;
    }

    @media (max-width: 640px) {
        .nav-links     { display: none; }
        .nav-user-btn  { display: none; }
        .nav-hamburger { display: flex; }
        .nav-inner     { padding: 0 16px; }
    }
</style>

<nav class="nav-root" x-data="{ open: false, dropOpen: false }">
    <div class="nav-inner">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <div class="nav-logo-icon">📦</div>
            <span class="nav-logo-text">Packt<span>.</span></span>
        </a>

        {{-- Links desktop --}}
        <div class="nav-links">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-link-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('pacotes.index') }}"
               class="nav-link {{ request()->routeIs('pacotes.*') ? 'active' : '' }}">
                <span class="nav-link-icon">📦</span> Pacotes
            </a>
        </div>

        {{-- Direita --}}
        <div class="nav-right">

            {{-- Dropdown usuário (desktop) --}}
            <div class="nav-dropdown" x-data="{ dropOpen: false }" @click.outside="dropOpen = false">
                <button class="nav-user-btn" @click="dropOpen = !dropOpen">
                    <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <span class="nav-user-name">{{ Auth::user()->name }}</span>
                    <span class="nav-chevron" :style="dropOpen ? 'transform:rotate(180deg)' : ''">▾</span>
                </button>

                <div class="nav-dropdown-menu" :class="{ open: dropOpen }">
                    <div class="nav-dropdown-header">
                        <div class="nav-dropdown-name">{{ Auth::user()->name }}</div>
                        <div class="nav-dropdown-email">{{ Auth::user()->email }}</div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">
                        👤 Perfil
                    </a>

                    <div class="nav-dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-dropdown-item danger"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            🚪 Sair
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hambúrguer (mobile) --}}
            <button class="nav-hamburger" @click="open = !open">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <template x-if="!open">
                        <g><line x1="2" y1="5"  x2="16" y2="5"/><line x1="2" y1="9"  x2="16" y2="9"/><line x1="2" y1="13" x2="16" y2="13"/></g>
                    </template>
                    <template x-if="open">
                        <g><line x1="3" y1="3" x2="15" y2="15"/><line x1="15" y1="3" x2="3" y2="15"/></g>
                    </template>
                </svg>
            </button>
        </div>

    </div>

    {{-- Menu mobile --}}
    <div class="nav-mobile" :class="{ open: open }">
        <a href="{{ route('dashboard') }}"
           class="nav-mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>
        <a href="{{ route('pacotes.index') }}"
           class="nav-mobile-link {{ request()->routeIs('pacotes.*') ? 'active' : '' }}">
            📦 Pacotes
        </a>

        <div class="nav-mobile-user">
            <div class="nav-mobile-user-name">{{ Auth::user()->name }}</div>
            <div class="nav-mobile-user-email">{{ Auth::user()->email }}</div>

            <a href="{{ route('profile.edit') }}" class="nav-mobile-link">👤 Perfil</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-mobile-link" style="width:100%;text-align:left;border:none;background:none;cursor:pointer;"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    🚪 Sair
                </button>
            </form>
        </div>
    </div>
</nav>