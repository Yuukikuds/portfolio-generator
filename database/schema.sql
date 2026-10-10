-- PortfolioCraft database schema
-- Safe to run more than once.

CREATE TABLE IF NOT EXISTS portfolios (
    id                SERIAL PRIMARY KEY,
    full_name         VARCHAR(100)  NOT NULL,
    email             VARCHAR(150)  NOT NULL,
    contact_number    VARCHAR(30)   NOT NULL DEFAULT '',
    address           VARCHAR(255)  NOT NULL DEFAULT '',
    about_me          TEXT          NOT NULL DEFAULT '',

    -- Profile picture stored as a compressed image data URL
    -- (example: "data:image/jpeg;base64,/9j/4AAQ...").
    profile_picture   TEXT          NOT NULL DEFAULT '',

    -- JSONB columns: each holds an ARRAY of entries for one section.
    -- Example skills: [{"name":"JavaScript","level":"Advanced"}]
    education         JSONB         NOT NULL DEFAULT '[]'::jsonb,
    skills            JSONB         NOT NULL DEFAULT '[]'::jsonb,
    projects          JSONB         NOT NULL DEFAULT '[]'::jsonb,
    work_experience   JSONB         NOT NULL DEFAULT '[]'::jsonb,
    social_links      JSONB         NOT NULL DEFAULT '[]'::jsonb,

    selected_template VARCHAR(20)   NOT NULL DEFAULT 'simple',

    created_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
    updated_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),

    CONSTRAINT chk_template
        CHECK (selected_template IN ('simple', 'modern', 'creative')),
    CONSTRAINT chk_education_array
        CHECK (jsonb_typeof(education) = 'array'),
    CONSTRAINT chk_skills_array
        CHECK (jsonb_typeof(skills) = 'array'),
    CONSTRAINT chk_projects_array
        CHECK (jsonb_typeof(projects) = 'array'),
    CONSTRAINT chk_experience_array
        CHECK (jsonb_typeof(work_experience) = 'array'),
    CONSTRAINT chk_social_array
        CHECK (jsonb_typeof(social_links) = 'array')
);

-- Manage page lists newest-updated portfolios first.
CREATE INDEX IF NOT EXISTS idx_portfolios_updated_at
    ON portfolios (updated_at DESC);
