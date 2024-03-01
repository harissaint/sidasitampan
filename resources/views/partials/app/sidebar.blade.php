<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('app/assets/images/icon-mo.png') }}" class="logo-icon" alt="logo icon"
                style="height: 50px;width:auto;">
        </div>
        <div>
            <h4 class="logo-text">SiDasi Tampan</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li> <a href="{{ route('home') }}"><i class="bx bx-right-arrow-alt"></i>Rekening</a>
                </li>
                <li> <a href="{{ route('urusan') }}"><i class="bx bx-right-arrow-alt"></i>Urusan</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-bar-chart-alt-2'></i>
                </div>
                <div class="menu-title">Perbandingan Penganggaran</div>
            </a>
            <ul>
                <li> <a href="{{ route('perbandingan.rekening') }}"><i class="bx bx-right-arrow-alt"></i>Rekening</a>
                </li>
                <li> <a href="{{ route('perbandingan.urusan') }}"><i class="bx bx-right-arrow-alt"></i>Urusan</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bxs-bar-chart-alt-2'></i>
                </div>
                <div class="menu-title">Perbandingan Realisasi</div>
            </a>
            <ul>
                <li> <a href="{{ route('realisasi.rekening') }}"><i class="bx bx-right-arrow-alt"></i>Rekening</a>
                </li>
                <li> <a href="{{ route('realisasi.urusan') }}"><i class="bx bx-right-arrow-alt"></i>Urusan</a>
                </li>
            </ul>
        </li>
        @canany(['isAdmin', 'isTapd'], App\Model\User::class)
            <li>
                <a href="{{ route('alokasi') }}">
                    <div class="parent-icon"><i class='bx bx-wallet-alt'></i>
                    </div>
                    <div class="menu-title">Alokasi Anggaran Wajib</div>
                </a>
            </li>
        @endcanany
        <li class="menu-label">Master</li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-list-ol'></i>
                </div>
                <div class="menu-title">Penganggaran</div>
            </a>
            <ul>
                @canany(['isAdmin', 'isTapd'], App\Model\User::class)
                    <li> <a href="{{ route('master.tahapan.index') }}"><i class="bx bx-right-arrow-alt"></i>List</a>
                    </li>
                @endcan
                <li> <a href="{{ route('master.sipd.bandingkan') }}"><i class="bx bx-right-arrow-alt"></i>Bandingkan</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-list-ul'></i>
                </div>
                <div class="menu-title">Realisasi</div>
            </a>
            <ul>
                <li> <a href="{{ route('master.realisasi.index') }}"><i class="bx bx-right-arrow-alt"></i>List</a>
                </li>
                <li> <a href="{{ route('master.realisasi.bandingkan') }}"><i
                            class="bx bx-right-arrow-alt"></i>Bandingkan</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-list-check'></i>
                </div>
                <div class="menu-title">Prioritas</div>
            </a>
            <ul>
                <li> <a href="{{ route('master.prioritas.index') }}"><i class="bx bx-right-arrow-alt"></i>Rekening</a>
                </li>
                <li> <a href="{{ route('master.prioritas-sk.index') }}"><i class="bx bx-right-arrow-alt"></i>Sub
                        Kegiatan</a>
                </li>
                <li> <a href="javascript:;" class="has-arrow"><i class="bx bx-right-arrow-alt"></i>Sumber Dana</a>
                    <ul>
                        <li>
                            <a href="{{ route('master.prioritas-sd.index') }}"><i class="bx bx-right-arrow-alt"></i>
                                Rekening
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('master.prioritas-sd-sk.index') }}"><i class="bx bx-right-arrow-alt"></i>
                                Sub Kegiatan
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        @canany(['isAdmin', 'isTapd'], App\Model\User::class)
            <li>
                <a href="{{ route('master.skpd.index') }}">
                    <div class="parent-icon"><i class='bx bx-buildings'></i>
                    </div>
                    <div class="menu-title">SKPD</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-wallet'></i>
                    </div>
                    <div class="menu-title">Rekening</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('master.akun.index') }}"><i class="bx bx-right-arrow-alt"></i>
                            List
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.akun.nilai') }}"><i class="bx bx-right-arrow-alt"></i>
                            Nilai
                        </a>
                    </li>
                </ul>
            </li>
        @endcanany
        @can('isAdmin', App\Model\User::class)
            <li class="menu-label">Setting</li>
            <li>
                <a href="{{ route('setting.group.index') }}">
                    <div class="parent-icon"><i class='bx bx-category'></i>
                    </div>
                    <div class="menu-title">Group</div>
                </a>
            </li>
            <li>
                <a href="{{ route('setting.pengguna.index') }}">
                    <div class="parent-icon"><i class='bx bx-group'></i>
                    </div>
                    <div class="menu-title">Pengguna</div>
                </a>
            </li>
            <li>
                <a href="{{ route('setting.sosmed.index') }}">
                    <div class="parent-icon"><i class='bx bx-grid'></i>
                    </div>
                    <div class="menu-title">Sosmed</div>
                </a>
            </li>
        @endcan
    </ul>
    <!--end navigation-->
</div>
