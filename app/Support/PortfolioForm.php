<?php

namespace App\Support;

use App\Models\Portfolio;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Everything about the portfolio form: blank data, reading it from a request,
 * adding and removing rows, cleaning, validating, and preparing the profile picture.
 */
class PortfolioForm
{
    public const SKILL_LEVELS = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];

    public const PLATFORMS = ['Facebook', 'Instagram', 'LinkedIn', 'GitHub', 'Website'];

    /** The five sections that can have many entries. */
    public const SECTIONS = ['education', 'skills', 'projects', 'work_experience', 'social_links'];

    private const TEXT_FIELDS = ['full_name', 'email', 'contact_number', 'address', 'about_me'];

    private const MAX_PICTURE_LENGTH = 700000;

    // ------------------------------------------------------------------ blank data

    public static function blankEntry(string $section): array
    {
        return match ($section) {
            'education' => ['school' => '', 'degree' => '', 'startYear' => '', 'endYear' => '', 'description' => ''],
            'skills' => ['name' => '', 'level' => 'Intermediate'],
            'projects' => ['title' => '', 'description' => '', 'technologies' => '', 'link' => ''],
            'work_experience' => ['jobTitle' => '', 'company' => '', 'startDate' => '', 'endDate' => '', 'description' => ''],
            'social_links' => ['platform' => 'GitHub', 'url' => ''],
            default => [],
        };
    }

    public static function blank(): array
    {
        $form = [
            'full_name' => '',
            'email' => '',
            'contact_number' => '',
            'address' => '',
            'about_me' => '',
            'profile_picture' => '',
        ];
        foreach (self::SECTIONS as $section) {
            $form[$section] = [self::blankEntry($section)];
        }

        return $form;
    }

    public static function fromModel(Portfolio $portfolio): array
    {
        $form = [];
        foreach (self::TEXT_FIELDS as $field) {
            $form[$field] = (string) $portfolio->{$field};
        }
        $form['profile_picture'] = (string) $portfolio->profile_picture;
        foreach (self::SECTIONS as $section) {
            $form[$section] = self::normalizeRows($section, (array) ($portfolio->{$section} ?? []));
        }

        return $form;
    }

    // ------------------------------------------------------------------ reading a request

    private static function normalizeRows(string $section, array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $entry = self::blankEntry($section);
            foreach ($entry as $key => $default) {
                $value = $row[$key] ?? $default;
                $entry[$key] = is_scalar($value) ? (string) $value : $default;
            }
            $result[] = $entry;
        }

        return $result;
    }

    public static function fromRequest(Request $request): array
    {
        $form = [];
        foreach (array_merge(self::TEXT_FIELDS, ['profile_picture']) as $field) {
            $value = $request->input($field, '');
            $form[$field] = is_string($value) ? $value : '';
        }
        foreach (self::SECTIONS as $section) {
            $rows = $request->input($section, []);
            $form[$section] = self::normalizeRows($section, is_array($rows) ? $rows : []);
        }

        return $form;
    }

    // ------------------------------------------------------------------ add / remove buttons

    /**
     * Applies "add:education", "remove:skills:2" or "remove-photo".
     * Returns true when the action changed the form (so the page is shown again).
     */
    public static function applyAction(array &$form, string $action): bool
    {
        $sections = implode('|', self::SECTIONS);

        if (preg_match('/^add:(' . $sections . ')$/', $action, $m)) {
            $form[$m[1]][] = self::blankEntry($m[1]);

            return true;
        }

        if (preg_match('/^remove:(' . $sections . '):(\d+)$/', $action, $m)) {
            unset($form[$m[1]][(int) $m[2]]);
            $form[$m[1]] = array_values($form[$m[1]]);

            return true;
        }

        if ($action === 'remove-photo') {
            $form['profile_picture'] = '';

            return true;
        }

        return $action === 'photo';
    }

    // ------------------------------------------------------------------ cleaning and saving

    /** Trims everything and drops rows the user left completely empty. */
    public static function clean(array $form): array
    {
        foreach (self::TEXT_FIELDS as $field) {
            $form[$field] = trim($form[$field]);
        }

        $ignore = ['skills' => ['level'], 'social_links' => ['platform']];

        foreach (self::SECTIONS as $section) {
            $kept = [];
            foreach ($form[$section] as $row) {
                $blank = true;
                foreach ($row as $key => $value) {
                    if (in_array($key, $ignore[$section] ?? [], true)) {
                        continue;
                    }
                    if (trim($value) !== '') {
                        $blank = false;
                        break;
                    }
                }
                if (! $blank) {
                    $kept[] = array_map('trim', $row);
                }
            }
            $form[$section] = $kept;
        }

        return $form;
    }

    /** Converts cleaned form data into database columns. */
    public static function toAttributes(array $clean): array
    {
        $attributes = [];
        foreach (self::TEXT_FIELDS as $field) {
            $attributes[$field] = $clean[$field];
        }
        $attributes['profile_picture'] = $clean['profile_picture'];
        foreach (self::SECTIONS as $section) {
            $attributes[$section] = array_values($clean[$section]);
        }

        return $attributes;
    }

    // ------------------------------------------------------------------ validation

    public static function validator(array $data): ValidatorContract
    {
        $year = ['nullable', 'regex:/^(\d{4}|present)$/i'];

        $rules = [
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'max:150', 'email'],
            'contact_number' => ['nullable', 'string', 'regex:/^[0-9+()\-.\s]{7,30}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'about_me' => ['nullable', 'string', 'max:2000'],

            'education' => ['array', 'max:10'],
            'education.*.school' => ['required', 'string', 'max:150'],
            'education.*.degree' => ['nullable', 'string', 'max:150'],
            'education.*.startYear' => $year,
            'education.*.endYear' => $year,
            'education.*.description' => ['nullable', 'string', 'max:1000'],

            'skills' => ['array', 'max:30'],
            'skills.*.name' => ['required', 'string', 'max:50'],
            'skills.*.level' => ['required', Rule::in(self::SKILL_LEVELS)],

            'projects' => ['array', 'max:10'],
            'projects.*.title' => ['required', 'string', 'max:100'],
            'projects.*.description' => ['nullable', 'string', 'max:1000'],
            'projects.*.technologies' => ['nullable', 'string', 'max:200'],
            'projects.*.link' => ['nullable', 'string', 'max:500', 'url:http,https'],

            'work_experience' => ['array', 'max:10'],
            'work_experience.*.jobTitle' => ['required', 'string', 'max:100'],
            'work_experience.*.company' => ['required', 'string', 'max:100'],
            'work_experience.*.startDate' => ['nullable', 'string', 'max:30'],
            'work_experience.*.endDate' => ['nullable', 'string', 'max:30'],
            'work_experience.*.description' => ['nullable', 'string', 'max:1000'],

            'social_links' => ['array', 'max:10'],
            'social_links.*.platform' => ['required', Rule::in(self::PLATFORMS)],
            'social_links.*.url' => ['required', 'string', 'max:500', 'url:http,https'],
        ];

        $messages = [
            'max' => 'This is too long (maximum :max characters).',
            'url' => 'Please enter a valid link that starts with http:// or https://',
            'regex' => 'Please check this value.',
            'in' => 'Please choose one of the listed options.',

            'full_name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'contact_number.regex' => 'Please enter a valid contact number (digits, +, -, spaces).',

            'education.max' => 'You can add up to :max education entries.',
            'education.*.school.required' => 'Please enter the school name.',
            'education.*.startYear.regex' => 'Please enter a 4-digit year.',
            'education.*.endYear.regex' => 'Please enter a 4-digit year (or Present).',

            'skills.max' => 'You can add up to :max skills.',
            'skills.*.name.required' => 'Please enter the skill name.',

            'projects.max' => 'You can add up to :max projects.',
            'projects.*.title.required' => 'Please enter the project title.',

            'work_experience.max' => 'You can add up to :max work experience entries.',
            'work_experience.*.jobTitle.required' => 'Please enter the job title.',
            'work_experience.*.company.required' => 'Please enter the company name.',

            'social_links.max' => 'You can add up to :max social links.',
            'social_links.*.url.required' => 'Please enter the link.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        $validator->after(function ($validator) use ($data) {
            $problem = self::pictureError((string) ($data['profile_picture'] ?? ''));
            if ($problem !== null) {
                $validator->errors()->add('profile_picture', $problem);
            }
        });

        return $validator;
    }

    // ------------------------------------------------------------------ profile picture

    public static function pictureError(string $value): ?string
    {
        if ($value === '') {
            return null;
        }
        if (strlen($value) > self::MAX_PICTURE_LENGTH) {
            return 'The profile picture is too large. Please choose a smaller image.';
        }
        if (! preg_match('#^data:image/(jpeg|png|webp);base64,([A-Za-z0-9+/]+=*)$#', $value, $m)
            || base64_decode($m[2], true) === false) {
            return 'Please upload a valid JPG, PNG, or WEBP image.';
        }

        return null;
    }

    /**
     * Turns an uploaded file into a small data URL stored in the database.
     * With the GD extension the photo is cropped to a square and shrunk to 400 px.
     *
     * @return array{0: ?string, 1: ?string} [data URL, error message]
     */
    public static function processUpload(UploadedFile $file): array
    {
        if (! $file->isValid()) {
            return [null, 'The picture could not be uploaded. Please try again.'];
        }

        $mime = (string) $file->getMimeType();
        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return [null, 'Please choose a JPG, PNG, or WEBP image.'];
        }
        if ($file->getSize() > 5 * 1024 * 1024) {
            return [null, 'That image is too large. Please choose one smaller than 5 MB.'];
        }

        $bytes = (string) file_get_contents($file->getRealPath());

        if (function_exists('imagecreatefromstring') && function_exists('imagejpeg')) {
            $source = @imagecreatefromstring($bytes);
            if ($source === false) {
                return [null, 'That file is not a valid image. Please choose a JPG, PNG, or WEBP file.'];
            }

            $width = imagesx($source);
            $height = imagesy($source);
            $side = min($width, $height);
            $size = min($side, 400);

            $square = imagecreatetruecolor($size, $size);
            imagefill($square, 0, 0, imagecolorallocate($square, 255, 255, 255));
            imagecopyresampled(
                $square, $source,
                0, 0,
                intdiv($width - $side, 2), intdiv($height - $side, 2),
                $size, $size,
                $side, $side
            );

            ob_start();
            imagejpeg($square, null, 85);
            $jpeg = (string) ob_get_clean();

            return ['data:image/jpeg;base64,' . base64_encode($jpeg), null];
        }

        // Without the GD extension the original file is kept, but only if it is small enough.
        $data = 'data:' . $mime . ';base64,' . base64_encode($bytes);
        if (strlen($data) > self::MAX_PICTURE_LENGTH) {
            return [null, 'The image is too large for this server. Please choose one smaller than 500 KB.'];
        }

        return [$data, null];
    }
}
