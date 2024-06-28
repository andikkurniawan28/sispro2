@include('template.kaiadmin.head')
<div class="wrapper">
    @include('template.kaiadmin.sidebar')
    <div class="main-panel">
        <div class="main-header">
            <div class="main-header-logo">
                @include('template.kaiadmin.logo_header')
            </div>
            @include('template.kaiadmin.navbar')
        </div>
        @yield('content')
        @include('template.kaiadmin.footer')
    </div>
</div>
@include('template.kaiadmin.script')
