<footer class="footer">
    <div class="container footer-inner">
        <div class="footer-left">
            <a href="{{ route('home') }}" class="brand footer-brand" aria-label="PortfolioCraft home">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="m7 6 6 6-6 6"/><path d="m13 6 6 6-6 6"/></svg>
                </span>
                <span>PortfolioCraft</span>
            </a>
            <nav aria-label="Footer" class="footer-links">
                <a href="{{ route('home') }}#features">Features</a>
                <a href="{{ route('home') }}#templates">Templates</a>
                <a href="{{ route('portfolios.create') }}">Create</a>
                <a href="{{ route('portfolios.index') }}">Manage</a>
            </nav>
        </div>
        <p class="footer-copy">&copy; {{ date('Y') }} PortfolioCraft. All rights reserved.</p>
    </div>
</footer>
