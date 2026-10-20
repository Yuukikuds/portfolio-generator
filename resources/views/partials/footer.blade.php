@php
    // Our team (alphabetical by surname) and the project links.
    $team = [
        'Abucay, Lyca Marie S.',
        'Badilla, Rosemin Mae D.',
        'Cabuenas, Wayne Brradly L.',
        'Carsido, John Ivan P.',
        'Collantes, James Harold S.',
        'Mabini, Brian Jr. B.',
        'Martir, Manuelito J.',
    ];
    $githubUrl = 'https://github.com/Yuukikuds/portfolio-generator';
    $railwayUrl = 'https://portfolio-generator-production-4612.up.railway.app';

    // Errors for the feedback box only (the variable does not exist on some error pages).
    $feedbackErrors = (isset($errors) && $errors->hasBag('feedback')) ? $errors->getBag('feedback') : null;
@endphp
<footer class="footer">
    <div class="container footer-inner">
        <a href="{{ route('home') }}" class="brand" aria-label="PortfolioCraft home">
            <span class="brand-mark" aria-hidden="true">P</span>
            <span>PortfolioCraft</span>
        </a>
        <p class="footer-note">&copy; {{ date('Y') }} PortfolioCraft</p>
    </div>

    <div class="container footer-credits">
        <div class="credits-team">
            <h2 class="credits-title">Our Team</h2>
            <ul class="credits-names">
                @foreach ($team as $member)
                    <li>{{ $member }}</li>
                @endforeach
            </ul>
        </div>

        {{-- The feedback box needs a started session (for its security token), so it is hidden on bare error pages. --}}
        @if (app('session.store')->isStarted())
            <div class="credits-feedback" id="feedback">
                <h2 class="credits-title">Feedback</h2>
                <form method="POST" action="{{ route('feedback.store') }}" class="feedback-form" novalidate>
                    @csrf
                    <textarea name="feedback_message" rows="3" maxlength="1000" aria-label="Your feedback"
                              placeholder="Tell us what you think about PortfolioCraft...">{{ old('feedback_message') }}</textarea>
                    @if ($feedbackErrors && $feedbackErrors->has('feedback_message'))
                        <p class="field-error" role="alert">{{ $feedbackErrors->first('feedback_message') }}</p>
                    @endif
                    <button type="submit" class="btn btn-secondary btn-small">Send feedback</button>
                </form>
            </div>
        @endif

        <div class="credits-links">
            <h2 class="credits-title">Project</h2>
            <a href="{{ $githubUrl }}" target="_blank" rel="noopener noreferrer">GitHub repository</a>
            <a href="{{ $railwayUrl }}" target="_blank" rel="noopener noreferrer">Live on Railway</a>
        </div>
    </div>
</footer>
