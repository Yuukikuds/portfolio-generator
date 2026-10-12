<footer class="footer">
    <div class="container footer-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="PortfolioCraft home">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span>PortfolioCraft</span>
        </a>
        <nav aria-label="Footer">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('portfolios.create') }}">Create Portfolio</a>
            <a href="{{ route('portfolios.index') }}">Manage</a>
        </nav>
        <p class="footer-note">&copy; {{ date('Y') }} PortfolioCraft</p>
    </div>
</footer>
