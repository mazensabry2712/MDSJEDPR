<!-- main-sidebar -->
<aside class="app-sidebar sidebar-scroll">
    <!-- Logo Section - Above Red Line -->
    <div class="sidebar-logo-container">
        <a href="{{ url('/dashboard') }}" class="sidebar-logo-link">
            <img src="{{ URL::asset('assets/img/brand/logosidbar.png') }}" class="sidebar-main-logo" alt="MDSJEDPR Logo">
        </a>
    </div>

    <div class="main-sidemenu">
        <ul class="side-menu">
            <li class="side-item side-item-category">Main</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url('dashboard') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                        <path
                            d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                    </svg><span class="side-menu__label">Dashboard</span></a>
            </li>
            <li class="side-item side-item-category">General</li>




 <li class="slide">
                <a class="side-menu__item" href="{{ route('epo.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z"/>
                    </svg><span class="side-menu__label">Project EPO </span></a>
            </li>
            

            <li class="slide">
                <a class="side-menu__item" href="{{ route('projects.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg><span class="side-menu__label">Project Details </span></a>
            </li>













            <li class="slide">
                <a class="side-menu__item" href="{{ route('customer.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg><span class="side-menu__label">Customer </span></a>
            </li>









            <li class="slide">
                <a class="side-menu__item" href="{{ route('pm.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg><span class="side-menu__label">PM </span></a>
            </li>



            <li class="slide">
                <a class="side-menu__item" href="{{ route('am.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg><span class="side-menu__label">AM </span></a>
            </li>









            <li class="slide">
                <a class="side-menu__item" href="{{ route('vendors.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 0 0-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                    </svg><span class="side-menu__label">Vendors </span></a>
            </li>








            <li class="slide">
                <a class="side-menu__item" href="{{ route('ds.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M20,8h-2.81c-0.45-0.78-1.07-1.45-1.82-1.96L17,4.41L15.59,3L13.17,5.42C12.61,5.15,12.01,5,11.4,5h-0.8 C9.01,5,8.41,5.15,7.83,5.42L5.41,3L4,4.41l1.62,1.63C4.87,6.55,4.25,7.22,3.8,8H1v2h2.81c-0.04,0.33-0.06,0.66-0.06,1 s0.02,0.67,0.06,1H1v2h2.8c0.45,0.78,1.07,1.45,1.82,1.96L4,17.59L5.41,19l2.42-2.42C8.41,16.85,9.01,17,9.6,17h0.8 c0.59,0,1.19-0.15,1.77-0.42L14.59,19L16,17.59l-1.62-1.63c0.75-0.51,1.37-1.18,1.82-1.96H19v-2h-2.81c0.04-0.33,0.06-0.66,0.06-1 s-0.02-0.67-0.06-1H19V8z M10,13c-1.65,0-3-1.35-3-3s1.35-3,3-3s3,1.35,3,3S11.65,13,10,13z"/>
                    </svg><span class="side-menu__label">Disti/ Supplier </span></a>
            </li>








            <li class="slide">
                <a class="side-menu__item" href="{{ route('invoices.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        <path d="M8,12H16V14H8V12M8,16H13V18H8V16Z"/>
                    </svg><span class="side-menu__label">Invoice </span></a>
            </li>










            <li class="slide">
                <a class="side-menu__item" href="{{ route('dn.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M19,3H5C3.9,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.9 20.1,3 19,3M19,19H5V5H19V19Z"/>
                        <path d="M14,17H7V15H14M17,13H7V11H17M17,9H7V7H17V9Z"/>
                    </svg><span class="side-menu__label">DN </span></a>
            </li>










            <li class="slide">
                <a class="side-menu__item" href="{{ route('coc.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16.2V16H7.8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                    </svg><span class="side-menu__label">CoC </span></a>
            </li>











            <li class="slide">
                <a class="side-menu__item" href="{{ route('ppos.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M7,15H9C9,16.08 10.37,17 12,17C13.63,17 15,16.08 15,15C15,13.9 13.96,13.5 11.76,12.97C9.64,12.44 7,11.78 7,9C7,7.21 8.47,5.69 10.5,5.18V3H13.5V5.18C15.53,5.69 17,7.21 17,9H15C15,7.92 13.63,7 12,7C10.37,7 9,7.92 9,9C9,10.1 10.04,10.5 12.24,11.03C14.36,11.56 17,12.22 17,15C17,16.79 15.53,18.31 13.5,18.82V21H10.5V18.82C8.47,18.31 7,16.79 7,15Z"/>
                    </svg><span class="side-menu__label">Project POs </span></a>
            </li>





            <li class="slide">
                <a class="side-menu__item" href="{{ route('pstatus.index') }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M9,4V6H15V4H9M9,8V10H15V8H9M9,12V14H15V12H9M3,2V22L7.5,20L12,22L16.5,20L21,22V2L16.5,4L12,2L7.5,4L3,2Z"/>
                    </svg><span class="side-menu__label">Project Status </span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('ptasks.index') }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M10,17L5,12L6.41,10.58L10,14.17L17.59,6.58L19,8M19,19H5V5H19V19Z"/>
                    </svg><span class="side-menu__label">Project Tasks </span></a>
            </li>


            <li class="slide">
                <a class="side-menu__item" href="{{ route('risks.index') }}"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                    </svg><span class="side-menu__label">Risks </span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('milestones.index') }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M14.4 6L14 4H5v17h2v-7h5.6l.4 2h7V6z"/>
                    </svg><span class="side-menu__label">Milestones </span></a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('reports.index') }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
                    </svg><span class="side-menu__label">Reports </span></a>
            </li>

            {{-- @can('المستخدمين') --}}
            <li class="side-item side-item-category ">Users</li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zM4 18v-4h3v-3c0-1.1.9-2 2-2h2c1.1 0 2 .9 2 2v3h3v4H4zM0 20h24v2H0v-2z"/>
                        <path d="M12.5 11.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5S11 9.17 11 10s.67 1.5 1.5 1.5z"/>
                        <path d="M12.5 13c-1.83 0-5.5.92-5.5 2.75V18h11v-2.25c0-1.83-3.67-2.75-5.5-2.75z"/>
                    </svg><span class="side-menu__label mb-1">Users</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    {{-- @can('قائمة المستخدمين') --}}
                    <li><a class="slide-item" href="{{ url('/' . ($page = 'users')) }}">User List</a></li>
                    {{-- @endcan --}}

                    {{-- @can('صلاحيات المستخدمين') --}}
                    <li><a class="slide-item" href="{{ url('/' . ($page = 'roles')) }}">User Permissions</a></li>
                    {{-- @endcan --}}
                </ul>
            </li>

            </li>



        </ul>
    </div>
</aside>
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<!-- main-sidebar -->
