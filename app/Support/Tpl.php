<?php

namespace App\Support;

/**
 * Small helper functions used by the Blade views and the three portfolio templates.
 * An instance is shared with every view as the variable $tpl.
 */
class Tpl
{
    /** The three templates (exactly three). */
    public const TEMPLATES = [
        'simple' => [
            'name' => 'Simple',
            'tags' => ['Clean', 'Minimal', 'Professional'],
            'description' => 'A white, single-column resume layout with clear sections and easy-to-read text.',
        ],
        'modern' => [
            'name' => 'Modern',
            'tags' => ['Cards', 'Sections', 'Strong hierarchy'],
            'description' => 'A bold header, skill badges, and project cards arranged in a modern card layout.',
        ],
        'creative' => [
            'name' => 'Creative',
            'tags' => ['Unique layout', 'Visual elements', 'Expressive'],
            'description' => 'A dark sidebar, skill rings, and case-study projects for a standout look.',
        ],
    ];

    public function templates(): array
    {
        return self::TEMPLATES;
    }

    public function isTemplate($id): bool
    {
        return is_string($id) && isset(self::TEMPLATES[$id]);
    }

    public function name(?string $id): string
    {
        return self::TEMPLATES[$id]['name'] ?? (string) $id;
    }

    /** "2022" + "2026" becomes "2022 - 2026". */
    public function range($start, $end): string
    {
        $s = trim((string) $start);
        $e = trim((string) $end);
        if ($s !== '' && $e !== '') {
            return $s . ' - ' . $e;
        }

        return $s !== '' ? $s : $e;
    }

    private function words($text): array
    {
        $parts = preg_split('/\s+/u', trim((string) $text), -1, PREG_SPLIT_NO_EMPTY);

        return $parts === false ? [] : $parts;
    }

    public function initials($name): string
    {
        $out = '';
        foreach (array_slice($this->words($name), 0, 2) as $word) {
            $out .= mb_strtoupper(mb_substr($word, 0, 1));
        }

        return $out;
    }

    public function firstName($name): string
    {
        return $this->words($name)[0] ?? '';
    }

    public function telHref($number): string
    {
        return 'tel:' . preg_replace('/[^\d+]/', '', (string) $number);
    }

    public function platform($platform): string
    {
        return $platform === 'Website' ? 'Personal Website' : (string) $platform;
    }

    /** A short line under the name: the latest job title, otherwise the first degree. */
    public function tagline($portfolio): string
    {
        $jobs = $portfolio->work_experience ?? [];
        if (! empty($jobs[0]['jobTitle'])) {
            return (string) $jobs[0]['jobTitle'];
        }
        $education = $portfolio->education ?? [];
        if (! empty($education[0]['degree'])) {
            return (string) $education[0]['degree'];
        }

        return '';
    }

    public function shorten($text, int $max = 150): string
    {
        $clean = trim((string) preg_replace('/\s+/u', ' ', (string) $text));

        return mb_strlen($clean) > $max ? rtrim(mb_substr($clean, 0, $max)) . '...' : $clean;
    }

    /** "React, Node.js" becomes ["React", "Node.js"]. */
    public function split($text): array
    {
        $items = array_map('trim', explode(',', (string) $text));

        return array_values(array_filter($items, fn ($item) => $item !== ''));
    }

    public function levelPercent($level): int
    {
        $map = ['Beginner' => 25, 'Intermediate' => 50, 'Advanced' => 75, 'Expert' => 100];

        return $map[$level] ?? 50;
    }
}
