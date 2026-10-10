@extends('layouts.app')

@section('title', ($isEdit ? 'Edit Portfolio' : 'Create Portfolio') . ' - PortfolioCraft')

@section('content')
@php
    $levels = \App\Support\PortfolioForm::SKILL_LEVELS;
    $platforms = \App\Support\PortfolioForm::PLATFORMS;
@endphp

@include('partials.stepper', ['current' => 1])

<header class="page-header">
    <h1>{{ $isEdit ? 'Edit Your Portfolio' : 'Create Your Portfolio' }}</h1>
    <p class="lead">Tell us about yourself. You can change everything later.</p>
</header>
<p class="required-note">Fields marked with <span aria-hidden="true">*</span> are required.</p>

@if ($errors->any())
    <div class="message message-error" role="alert">
        {{ $errors->has('form') ? $errors->first('form') : 'Please fix the highlighted fields.' }}
    </div>
@endif

<form class="portfolio-form" method="POST" action="{{ $actionUrl }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- Pressing Enter in a text box uses this first button (Save), never a Remove button. --}}
    <button type="submit" name="action" value="save" class="sr-only" tabindex="-1" aria-hidden="true">Save</button>
    <input type="hidden" name="profile_picture" value="{{ $form['profile_picture'] }}">

    <nav class="jump-nav" aria-label="Jump to a section">
        <a href="#sec-personal">Personal</a>
        <a href="#sec-about">About</a>
        <a href="#sec-education">Education</a>
        <a href="#sec-skills">Skills</a>
        <a href="#sec-projects">Projects</a>
        <a href="#sec-experience">Experience</a>
        <a href="#sec-links">Links</a>
    </nav>

    {{-- ---------- 1. Personal information ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-personal">Personal Information</h2>
            <p>Your name and how people can reach you.</p>
        </header>

        <div class="field photo-field">
            <label class="field-label" for="profile_picture_file">Profile Picture</label>
            <div class="photo-row">
                @if ($form['profile_picture'] !== '')
                    <img class="avatar-preview" src="{{ $form['profile_picture'] }}" alt="Your profile picture preview">
                @else
                    <div class="avatar-preview avatar-empty" aria-hidden="true">No photo</div>
                @endif
                <div class="photo-controls">
                    <input id="profile_picture_file" class="file-input" type="file" name="profile_picture_file" accept="image/jpeg,image/png,image/webp" aria-describedby="photo-hint">
                    <button type="submit" name="action" value="photo" formaction="{{ $actionUrl }}#sec-personal" class="btn btn-secondary btn-small">Upload photo</button>
                    @if ($form['profile_picture'] !== '')
                        <button type="submit" name="action" value="remove-photo" formaction="{{ $actionUrl }}#sec-personal" class="btn btn-ghost btn-small">Remove photo</button>
                    @endif
                    <p id="photo-hint" class="hint">JPG, PNG, or WEBP. Choose a file, then press Upload photo (or Save). It is cropped to a square automatically.</p>
                </div>
            </div>
            @error('profile_picture')
                <p class="field-error" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <x-input name="full_name" label="Full Name" required :value="$form['full_name']" placeholder="e.g. Maria Santos" maxlength="100" autocomplete="name" />
        <div class="grid-2">
            <x-input name="email" type="email" label="Email" required :value="$form['email']" placeholder="e.g. maria&#64;example.com" maxlength="150" autocomplete="email" />
            <x-input name="contact_number" type="tel" label="Contact Number" :value="$form['contact_number']" placeholder="e.g. +63 912 345 6789" maxlength="30" autocomplete="tel" />
        </div>
        <x-input name="address" label="Address" :value="$form['address']" placeholder="e.g. City, Province, Country" maxlength="255" autocomplete="street-address" />
    </section>

    {{-- ---------- 2. About me ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-about">About Me</h2>
            <p>A short introduction in your own words.</p>
        </header>
        <x-textarea name="about_me" label="About Me" rows="5" :value="$form['about_me']" :hint="mb_strlen($form['about_me']) . ' / 2000 characters'" placeholder="Write a short introduction about yourself." maxlength="2000" />
    </section>

    {{-- ---------- 3. Education ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-education">Educational Background</h2>
            <p>Schools, programs, and degrees.</p>
        </header>
        @error('education')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @forelse ($form['education'] as $i => $item)
            <div class="entry">
                <div class="entry-header">
                    <h3>Education {{ $i + 1 }}</h3>
                    <button type="submit" name="action" value="remove:education:{{ $i }}" formaction="{{ $actionUrl }}#sec-education" class="btn btn-danger-outline btn-small" aria-label="Remove Education {{ $i + 1 }}">Remove Education</button>
                </div>
                <x-input :name="'education[' . $i . '][school]'" label="School" required :value="$item['school']" placeholder="e.g. Sample State University" maxlength="150" />
                <x-input :name="'education[' . $i . '][degree]'" label="Degree / Program" :value="$item['degree']" placeholder="e.g. BS Information Technology" maxlength="150" />
                <div class="grid-2">
                    <x-input :name="'education[' . $i . '][startYear]'" label="Start Year" :value="$item['startYear']" placeholder="e.g. 2022" maxlength="7" />
                    <x-input :name="'education[' . $i . '][endYear]'" label="End Year" :value="$item['endYear']" placeholder="e.g. 2026 or Present" maxlength="7" />
                </div>
                <x-textarea :name="'education[' . $i . '][description]'" label="Description" rows="3" :value="$item['description']" placeholder="Honors, activities, or a short note." maxlength="1000" />
            </div>
        @empty
            <p class="empty-note">No education entries yet. Use the button below to add one.</p>
        @endforelse
        <button type="submit" name="action" value="add:education" formaction="{{ $actionUrl }}#sec-education" class="btn btn-add">+ Add Education</button>
    </section>

    {{-- ---------- 4. Skills ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-skills">Skills</h2>
            <p>Tools and abilities you want to highlight.</p>
        </header>
        @error('skills')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @forelse ($form['skills'] as $i => $item)
            <div class="entry">
                <div class="entry-header">
                    <h3>Skill {{ $i + 1 }}</h3>
                    <button type="submit" name="action" value="remove:skills:{{ $i }}" formaction="{{ $actionUrl }}#sec-skills" class="btn btn-danger-outline btn-small" aria-label="Remove Skill {{ $i + 1 }}">Remove Skill</button>
                </div>
                <div class="grid-2">
                    <x-input :name="'skills[' . $i . '][name]'" label="Skill Name" required :value="$item['name']" placeholder="e.g. JavaScript" maxlength="50" />
                    <x-select :name="'skills[' . $i . '][level]'" label="Skill Level" :options="$levels" :value="$item['level']" />
                </div>
            </div>
        @empty
            <p class="empty-note">No skill entries yet. Use the button below to add one.</p>
        @endforelse
        <button type="submit" name="action" value="add:skills" formaction="{{ $actionUrl }}#sec-skills" class="btn btn-add">+ Add Skill</button>
    </section>

    {{-- ---------- 5. Projects ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-projects">Projects</h2>
            <p>Work you are proud of.</p>
        </header>
        @error('projects')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @forelse ($form['projects'] as $i => $item)
            <div class="entry">
                <div class="entry-header">
                    <h3>Project {{ $i + 1 }}</h3>
                    <button type="submit" name="action" value="remove:projects:{{ $i }}" formaction="{{ $actionUrl }}#sec-projects" class="btn btn-danger-outline btn-small" aria-label="Remove Project {{ $i + 1 }}">Remove Project</button>
                </div>
                <x-input :name="'projects[' . $i . '][title]'" label="Project Title" required :value="$item['title']" placeholder="e.g. Online Library System" maxlength="100" />
                <x-textarea :name="'projects[' . $i . '][description]'" label="Project Description" rows="3" :value="$item['description']" placeholder="What does the project do?" maxlength="1000" />
                <div class="grid-2">
                    <x-input :name="'projects[' . $i . '][technologies]'" label="Technologies Used" :value="$item['technologies']" placeholder="e.g. Laravel, PHP, PostgreSQL" maxlength="200" />
                    <x-input :name="'projects[' . $i . '][link]'" type="url" label="Project Link" :value="$item['link']" placeholder="https://github.com/you/project" maxlength="500" />
                </div>
            </div>
        @empty
            <p class="empty-note">No project entries yet. Use the button below to add one.</p>
        @endforelse
        <button type="submit" name="action" value="add:projects" formaction="{{ $actionUrl }}#sec-projects" class="btn btn-add">+ Add Project</button>
    </section>

    {{-- ---------- 6. Work experience ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-experience">Work Experience</h2>
            <p>Jobs, internships, and volunteer roles.</p>
        </header>
        @error('work_experience')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @forelse ($form['work_experience'] as $i => $item)
            <div class="entry">
                <div class="entry-header">
                    <h3>Experience {{ $i + 1 }}</h3>
                    <button type="submit" name="action" value="remove:work_experience:{{ $i }}" formaction="{{ $actionUrl }}#sec-experience" class="btn btn-danger-outline btn-small" aria-label="Remove Experience {{ $i + 1 }}">Remove Experience</button>
                </div>
                <div class="grid-2">
                    <x-input :name="'work_experience[' . $i . '][jobTitle]'" label="Job Title" required :value="$item['jobTitle']" placeholder="e.g. Web Developer Intern" maxlength="100" />
                    <x-input :name="'work_experience[' . $i . '][company]'" label="Company" required :value="$item['company']" placeholder="e.g. Sample Company Inc." maxlength="100" />
                </div>
                <div class="grid-2">
                    <x-input :name="'work_experience[' . $i . '][startDate]'" label="Start Date" :value="$item['startDate']" placeholder="e.g. June 2025" maxlength="30" />
                    <x-input :name="'work_experience[' . $i . '][endDate]'" label="End Date" :value="$item['endDate']" placeholder="e.g. August 2025 or Present" maxlength="30" />
                </div>
                <x-textarea :name="'work_experience[' . $i . '][description]'" label="Description" rows="3" :value="$item['description']" placeholder="What did you do in this role?" maxlength="1000" />
            </div>
        @empty
            <p class="empty-note">No experience entries yet. Use the button below to add one.</p>
        @endforelse
        <button type="submit" name="action" value="add:work_experience" formaction="{{ $actionUrl }}#sec-experience" class="btn btn-add">+ Add Experience</button>
    </section>

    {{-- ---------- 7. Social links ---------- --}}
    <section class="card form-section">
        <header class="section-head">
            <h2 id="sec-links">Social Media / Website Links</h2>
            <p>Where people can find you online.</p>
        </header>
        @error('social_links')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @forelse ($form['social_links'] as $i => $item)
            <div class="entry">
                <div class="entry-header">
                    <h3>Social Link {{ $i + 1 }}</h3>
                    <button type="submit" name="action" value="remove:social_links:{{ $i }}" formaction="{{ $actionUrl }}#sec-links" class="btn btn-danger-outline btn-small" aria-label="Remove Social Link {{ $i + 1 }}">Remove Social Link</button>
                </div>
                <div class="grid-2">
                    <x-select :name="'social_links[' . $i . '][platform]'" label="Platform" :options="$platforms" :value="$item['platform']" hint="Choose Website for a personal website." />
                    <x-input :name="'social_links[' . $i . '][url]'" type="url" label="Link" required :value="$item['url']" placeholder="https://..." maxlength="500" />
                </div>
            </div>
        @empty
            <p class="empty-note">No social link entries yet. Use the button below to add one.</p>
        @endforelse
        <button type="submit" name="action" value="add:social_links" formaction="{{ $actionUrl }}#sec-links" class="btn btn-add">+ Add Social Link</button>
    </section>

    {{-- ---------- Actions ---------- --}}
    <div class="form-actions">
        <p class="action-note">Your portfolio is stored in an online database.</p>
        <button type="submit" name="action" value="save" class="btn btn-secondary btn-large">Save</button>
        <button type="submit" name="action" value="continue" class="btn btn-primary btn-large">Save &amp; Continue to Templates</button>
    </div>
</form>
@endsection
