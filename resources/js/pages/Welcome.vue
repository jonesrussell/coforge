<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    { canRegister: true },
);

const particles = Array.from({ length: 22 }, (_, i) => ({
    id: i,
    left: `${4 + Math.random() * 92}%`,
    size: 2 + Math.random() * 3,
    duration: 5 + Math.random() * 8,
    delay: Math.random() * 7,
    opacity: 0.12 + Math.random() * 0.35,
}));

function scrollTo(id: string) {
    document.querySelector(id)?.scrollIntoView({ behavior: 'smooth' });
}

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.08, rootMargin: '0px 0px -30px 0px' },
    );
    document
        .querySelectorAll('.scroll-reveal')
        .forEach((el) => observer.observe(el));
});
</script>

<template>
    <Head title="Coforge — Find Your Co-Founder">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin="anonymous"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
    </Head>

    <div class="landing-page">
        <!-- ═══════════════════ NAVIGATION ═══════════════════ -->
        <header class="nav-bar">
            <nav
                class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4"
            >
                <a href="/" class="nav-logo" aria-label="Coforge Home"
                    >COFORGE</a
                >
                <div class="flex items-center gap-3">
                    <Link href="/discover" class="nav-link-ghost">
                        Discover
                    </Link>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="nav-link-outline"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()" class="nav-link-ghost">
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="nav-link-outline"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- ═══════════════════ HERO ═══════════════════ -->
        <section class="hero">
            <!-- Background layers -->
            <div class="hero-grid" aria-hidden="true"></div>
            <div class="orb orb-warm" aria-hidden="true"></div>
            <div class="orb orb-cool" aria-hidden="true"></div>
            <div class="noise-overlay" aria-hidden="true"></div>

            <!-- Ember particles -->
            <div
                v-for="p in particles"
                :key="p.id"
                class="ember"
                aria-hidden="true"
                :style="{
                    left: p.left,
                    width: p.size + 'px',
                    height: p.size + 'px',
                    animationDuration: p.duration + 's',
                    animationDelay: p.delay + 's',
                    opacity: p.opacity,
                }"
            ></div>

            <!-- Content -->
            <div class="hero-content">
                <p class="eyebrow hero-reveal hero-reveal-1">
                    Where builders meet
                </p>

                <h1 class="hero-headline hero-reveal hero-reveal-2">
                    Every <span class="text-amber">Jobs</span><br />
                    needs a <span class="text-indigo">Woz</span>.
                </h1>

                <p class="hero-sub hero-reveal hero-reveal-3">
                    And every <span class="text-indigo">Woz</span> needs a
                    <span class="text-amber">Jobs</span>.
                </p>

                <p class="hero-desc hero-reveal hero-reveal-4">
                    Coforge matches developers with entrepreneurs&mdash;and
                    gives<br class="hidden md:block" />
                    both sides the shared context to start building together.
                </p>

                <div class="hero-cta hero-reveal hero-reveal-5">
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="btn-primary"
                    >
                        Start Pitching
                    </Link>
                    <button
                        class="btn-ghost"
                        @click="scrollTo('#how-it-works')"
                    >
                        See How It Works
                        <svg
                            width="16"
                            height="16"
                            viewBox="0 0 16 16"
                            fill="none"
                            class="ml-1.5 inline-block"
                        >
                            <path
                                d="M8 3v10m0 0l4-4m-4 4L4 9"
                                stroke="currentColor"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div
                class="scroll-hint hero-reveal hero-reveal-6"
                aria-hidden="true"
            >
                <div class="scroll-track">
                    <div class="scroll-dot"></div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════ THE FORGE — MATCHMAKING ═══════════════════ -->
        <section id="how-it-works" class="forge-section">
            <div class="forge-inner">
                <p class="section-eyebrow scroll-reveal">The Forge</p>
                <h2 class="section-heading scroll-reveal">
                    Two halves.<br />One forge.
                </h2>
                <p class="section-desc scroll-reveal">
                    Context-aware matching that goes deeper than keywords.
                    Coforge understands what you're building, what you need, and
                    who you should meet.
                </p>

                <!-- Two-card visual -->
                <div class="match-cards">
                    <!-- Visionary Card -->
                    <div class="match-card card-visionary scroll-reveal">
                        <div class="match-badge badge-amber">Entrepreneur</div>
                        <p class="match-quote">
                            &ldquo;I have the vision, the market insight, and
                            the drive.&nbsp;I&nbsp;need a technical
                            co&#8209;founder who can bring it
                            to&nbsp;life.&rdquo;
                        </p>
                        <div class="match-tags">
                            <span>SaaS</span><span>FinTech</span
                            ><span>B2B</span>
                        </div>
                    </div>

                    <!-- Forge symbol -->
                    <div class="forge-symbol scroll-reveal" aria-hidden="true">
                        <svg viewBox="0 0 120 80" class="forge-svg">
                            <defs>
                                <radialGradient
                                    id="forge-glow"
                                    cx="50%"
                                    cy="50%"
                                    r="50%"
                                >
                                    <stop
                                        offset="0%"
                                        stop-color="#F5A623"
                                        stop-opacity="0.5"
                                    />
                                    <stop
                                        offset="100%"
                                        stop-color="#818CF8"
                                        stop-opacity="0"
                                    />
                                </radialGradient>
                            </defs>
                            <circle
                                cx="42"
                                cy="40"
                                r="28"
                                fill="none"
                                stroke="#F5A623"
                                stroke-width="1"
                                opacity="0.5"
                            />
                            <circle
                                cx="78"
                                cy="40"
                                r="28"
                                fill="none"
                                stroke="#818CF8"
                                stroke-width="1"
                                opacity="0.5"
                            />
                            <ellipse
                                cx="60"
                                cy="40"
                                rx="14"
                                ry="24"
                                fill="url(#forge-glow)"
                            />
                        </svg>
                    </div>

                    <!-- Builder Card -->
                    <div class="match-card card-builder scroll-reveal">
                        <div class="match-badge badge-indigo">Developer</div>
                        <p class="match-quote">
                            &ldquo;I have the skills, the architecture instinct,
                            and the craft.&nbsp;I&nbsp;need a project that's
                            truly worth building.&rdquo;
                        </p>
                        <div class="match-tags">
                            <span>Vue</span><span>Go</span><span>Systems</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════ FEATURES ═══════════════════ -->
        <section class="features-section">
            <div class="features-inner">
                <p class="section-eyebrow scroll-reveal">How it works</p>
                <h2 class="section-heading scroll-reveal">
                    Pitch. Discover. Build.
                </h2>

                <div class="features-grid">
                    <!-- Feature 1 -->
                    <div
                        class="feature-card scroll-reveal"
                        style="--accent: #f5a623"
                    >
                        <div class="feature-icon">
                            <svg
                                viewBox="0 0 32 32"
                                fill="none"
                                stroke="#F5A623"
                                stroke-width="1.5"
                            >
                                <rect
                                    x="4"
                                    y="6"
                                    width="24"
                                    height="20"
                                    rx="3"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M4 12h24M10 18h8M10 22h5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <h3 class="feature-title">Pitch Your Project</h3>
                        <p class="feature-desc">
                            Present your vision clearly&mdash;whether you're a
                            founder seeking technical leadership or a developer
                            looking for a visionary. Structured pitches, not
                            cold DMs.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="feature-card scroll-reveal"
                        style="--accent: #818cf8"
                    >
                        <div class="feature-icon">
                            <svg
                                viewBox="0 0 32 32"
                                fill="none"
                                stroke="#818CF8"
                                stroke-width="1.5"
                            >
                                <circle cx="16" cy="16" r="10" />
                                <circle cx="16" cy="16" r="3" />
                                <path
                                    d="M16 6v3M16 23v3M26 16h-3M9 16H6"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>
                        <h3 class="feature-title">Discover Your Match</h3>
                        <p class="feature-desc">
                            Context-aware matching that understands what you're
                            building and who you should meet. Skills,
                            temperament, and vision&mdash;all&nbsp;factored in.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="feature-card scroll-reveal"
                        style="--accent: #34d399"
                    >
                        <div class="feature-icon">
                            <svg
                                viewBox="0 0 32 32"
                                fill="none"
                                stroke="#34D399"
                                stroke-width="1.5"
                            >
                                <path
                                    d="M6 16c3-6 6-6 10 0s7 6 10 0"
                                    stroke-linecap="round"
                                />
                                <path
                                    d="M6 10c3-6 6-6 10 0s7 6 10 0"
                                    stroke-linecap="round"
                                    opacity="0.4"
                                />
                                <path
                                    d="M6 22c3-6 6-6 10 0s7 6 10 0"
                                    stroke-linecap="round"
                                    opacity="0.4"
                                />
                            </svg>
                        </div>
                        <h3 class="feature-title">Stay Informed</h3>
                        <p class="feature-desc">
                            A curated feed of articles and insights tuned to
                            your domain, powered by North Cloud's content
                            intelligence. Always relevant, always fresh.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════ ARTICLE FEED PREVIEW ═══════════════════ -->
        <section class="feed-section">
            <div class="feed-inner">
                <p class="section-eyebrow scroll-reveal">
                    Powered by North Cloud
                </p>
                <h2 class="section-heading scroll-reveal">
                    Your feed,<br />forged for you.
                </h2>
                <p class="section-desc scroll-reveal">
                    The platform continuously ingests high-quality,
                    industry-aligned content&mdash;giving you a feed that feels
                    alive, curated, and directly connected to what you're
                    building.
                </p>

                <div class="feed-grid">
                    <article class="feed-card scroll-reveal">
                        <div class="feed-tag tag-fintech">FinTech</div>
                        <h4 class="feed-title">
                            Why Payment Infrastructure Is the New Moat
                        </h4>
                        <p class="feed-meta">
                            <span class="feed-source">The Generalist</span>
                            <span class="feed-dot">&middot;</span>
                            <span>4 min read</span>
                        </p>
                    </article>
                    <article class="feed-card scroll-reveal">
                        <div class="feed-tag tag-ai">AI / ML</div>
                        <h4 class="feed-title">
                            Retrieval-Augmented Generation in Production Systems
                        </h4>
                        <p class="feed-meta">
                            <span class="feed-source">Pragmatic Engineer</span>
                            <span class="feed-dot">&middot;</span>
                            <span>7 min read</span>
                        </p>
                    </article>
                    <article class="feed-card scroll-reveal">
                        <div class="feed-tag tag-oss">Open Source</div>
                        <h4 class="feed-title">
                            The Business Model Canvas for Developer Tools
                        </h4>
                        <p class="feed-meta">
                            <span class="feed-source">OSS Capital</span>
                            <span class="feed-dot">&middot;</span>
                            <span>5 min read</span>
                        </p>
                    </article>
                    <article class="feed-card scroll-reveal">
                        <div class="feed-tag tag-startup">Startups</div>
                        <h4 class="feed-title">
                            Finding Technical Co-Founders: A Founder's Playbook
                        </h4>
                        <p class="feed-meta">
                            <span class="feed-source">First Round Review</span>
                            <span class="feed-dot">&middot;</span>
                            <span>6 min read</span>
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- ═══════════════════ CTA ═══════════════════ -->
        <section class="cta-section">
            <div class="cta-inner scroll-reveal">
                <div class="cta-glow" aria-hidden="true"></div>
                <h2 class="cta-heading">Ready to forge<br />your future?</h2>
                <p class="cta-desc">
                    Join a community of builders who refuse to build alone.
                </p>
                <div class="cta-actions">
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="btn-primary btn-lg"
                    >
                        Create Your Profile
                    </Link>
                    <Link :href="login()" class="btn-ghost">
                        Already have an account?
                    </Link>
                </div>
            </div>
        </section>

        <!-- ═══════════════════ FOOTER ═══════════════════ -->
        <footer class="site-footer">
            <div class="footer-inner">
                <span class="footer-logo">COFORGE</span>
                <p class="footer-copy">
                    &copy; {{ new Date().getFullYear() }} Coforge. All rights
                    reserved.
                </p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* ── Foundation ────────────────────────────────────── */
.landing-page {
    --bg: #060b18;
    --surface: #0e1525;
    --surface-hover: #141c2e;
    --amber: #f5a623;
    --ember: #e8791d;
    --flame: #c2410c;
    --indigo: #818cf8;
    --text: #f1efe9;
    --text-secondary: #a0a3ab;
    --muted: #6b6e76;
    --border: #1a2030;
    --font-display: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif;

    background: var(--bg);
    color: var(--text);
    font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

.text-amber {
    color: var(--amber);
}
.text-indigo {
    color: var(--indigo);
}

/* ── Nav ───────────────────────────────────────────── */
.nav-bar {
    position: fixed;
    inset-inline: 0;
    top: 0;
    z-index: 50;
    background: rgba(6, 11, 24, 0.6);
    backdrop-filter: blur(16px) saturate(1.4);
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.nav-logo {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 1.1rem;
    letter-spacing: 0.08em;
    color: var(--text);
    text-decoration: none;
    transition: color 0.2s;
}
.nav-logo:hover {
    color: var(--amber);
}

.nav-link-ghost {
    padding: 0.45rem 1rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
    text-decoration: none;
    border-radius: 8px;
    transition: color 0.2s;
}
.nav-link-ghost:hover {
    color: var(--text);
}

.nav-link-outline {
    padding: 0.45rem 1.1rem;
    font-size: 0.875rem;
    color: var(--text);
    text-decoration: none;
    border: 1px solid var(--border);
    border-radius: 8px;
    transition:
        border-color 0.25s,
        box-shadow 0.25s;
}
.nav-link-outline:hover {
    border-color: rgba(245, 166, 35, 0.3);
    box-shadow: 0 0 16px rgba(245, 166, 35, 0.08);
}

/* ── Hero ──────────────────────────────────────────── */
.hero {
    position: relative;
    min-height: 100vh;
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6rem 1.5rem 3rem;
    overflow: hidden;
}

.hero-grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(
        circle,
        rgba(255, 255, 255, 0.04) 1px,
        transparent 1px
    );
    background-size: 40px 40px;
    mask-image: radial-gradient(
        ellipse 70% 60% at 50% 45%,
        black 30%,
        transparent 80%
    );
    -webkit-mask-image: radial-gradient(
        ellipse 70% 60% at 50% 45%,
        black 30%,
        transparent 80%
    );
}

.orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    will-change: transform;
}
.orb-warm {
    width: min(600px, 80vw);
    height: min(600px, 80vw);
    background: radial-gradient(
        circle,
        rgba(245, 166, 35, 0.13) 0%,
        transparent 70%
    );
    top: 8%;
    left: 15%;
    animation: orb-drift-warm 18s ease-in-out infinite;
}
.orb-cool {
    width: min(500px, 70vw);
    height: min(500px, 70vw);
    background: radial-gradient(
        circle,
        rgba(129, 140, 248, 0.1) 0%,
        transparent 70%
    );
    top: 18%;
    right: 10%;
    animation: orb-drift-cool 22s ease-in-out infinite;
}

.noise-overlay {
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-repeat: repeat;
    background-size: 256px 256px;
}

.ember {
    position: absolute;
    bottom: -8px;
    border-radius: 50%;
    background: var(--amber);
    pointer-events: none;
    animation: rise linear infinite;
}

.hero-content {
    position: relative;
    z-index: 10;
    text-align: center;
    max-width: 52rem;
}

.eyebrow {
    font-family: var(--font-display);
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--amber);
    margin-bottom: 1.5rem;
}

.hero-headline {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2.8rem, 8vw, 6rem);
    line-height: 1.1;
    letter-spacing: -0.025em;
    margin-bottom: 0.6rem;
}

.hero-sub {
    font-family: var(--font-display);
    font-weight: 500;
    font-size: clamp(1.15rem, 3vw, 1.65rem);
    color: var(--text-secondary);
    margin-bottom: 2rem;
}

.hero-desc {
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--muted);
    max-width: 36rem;
    margin: 0 auto 2.5rem;
}

.hero-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Hero entrance animation */
.hero-reveal {
    opacity: 0;
    transform: translateY(24px);
    animation: reveal-up 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.hero-reveal-1 {
    animation-delay: 0.15s;
}
.hero-reveal-2 {
    animation-delay: 0.35s;
}
.hero-reveal-3 {
    animation-delay: 0.55s;
}
.hero-reveal-4 {
    animation-delay: 0.75s;
}
.hero-reveal-5 {
    animation-delay: 0.95s;
}
.hero-reveal-6 {
    animation-delay: 1.2s;
}

/* Scroll indicator */
.scroll-hint {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
}
.scroll-track {
    width: 20px;
    height: 34px;
    border: 1.5px solid rgba(255, 255, 255, 0.15);
    border-radius: 10px;
    position: relative;
}
.scroll-dot {
    width: 3px;
    height: 7px;
    border-radius: 2px;
    background: var(--amber);
    position: absolute;
    left: 50%;
    top: 6px;
    transform: translateX(-50%);
    animation: scroll-bob 2s ease-in-out infinite;
}

/* ── Buttons ───────────────────────────────────────── */
.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.8rem;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.95rem;
    color: #0a0a0c;
    background: linear-gradient(135deg, var(--amber), var(--ember));
    border: none;
    border-radius: 10px;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 0 24px rgba(245, 166, 35, 0.25);
    transition:
        transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
        box-shadow 0.25s;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 40px rgba(245, 166, 35, 0.4);
}

.btn-lg {
    padding: 0.9rem 2.2rem;
    font-size: 1.05rem;
}

.btn-ghost {
    display: inline-flex;
    align-items: center;
    padding: 0.7rem 1.4rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
    background: transparent;
    border: 1px solid var(--border);
    border-radius: 10px;
    text-decoration: none;
    cursor: pointer;
    transition:
        color 0.2s,
        border-color 0.2s;
}
.btn-ghost:hover {
    color: var(--text);
    border-color: rgba(255, 255, 255, 0.15);
}

/* ── Section Shared ────────────────────────────────── */
.section-eyebrow {
    font-family: var(--font-display);
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: var(--amber);
    text-align: center;
    margin-bottom: 1rem;
}

.section-heading {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2rem, 5vw, 3.4rem);
    line-height: 1.15;
    letter-spacing: -0.02em;
    text-align: center;
    margin-bottom: 1.25rem;
}

.section-desc {
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--muted);
    text-align: center;
    max-width: 34rem;
    margin: 0 auto 3.5rem;
}

/* ── The Forge (Matchmaking) ───────────────────────── */
.forge-section {
    padding: 6rem 1.5rem 5rem;
    position: relative;
}
.forge-inner {
    max-width: 64rem;
    margin: 0 auto;
}

.match-cards {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .match-cards {
        flex-direction: row;
        align-items: stretch;
        gap: 0;
        justify-content: center;
    }
}

.match-card {
    flex: 0 1 320px;
    padding: 2rem 1.8rem;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: var(--surface);
    transition:
        transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
        box-shadow 0.4s,
        border-color 0.4s;
}

@media (min-width: 768px) {
    .card-visionary {
        transform: rotate(-2deg) translateX(12px);
    }
    .card-builder {
        transform: rotate(2deg) translateX(-12px);
    }
    .card-visionary:hover {
        transform: rotate(0deg) translateX(12px) translateY(-4px);
        box-shadow: 0 0 40px rgba(245, 166, 35, 0.1);
        border-color: rgba(245, 166, 35, 0.2);
    }
    .card-builder:hover {
        transform: rotate(0deg) translateX(-12px) translateY(-4px);
        box-shadow: 0 0 40px rgba(129, 140, 248, 0.1);
        border-color: rgba(129, 140, 248, 0.2);
    }
}

.match-badge {
    display: inline-block;
    padding: 0.3rem 0.75rem;
    font-family: var(--font-display);
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    border-radius: 6px;
    margin-bottom: 1rem;
}
.badge-amber {
    color: var(--amber);
    background: rgba(245, 166, 35, 0.1);
    border: 1px solid rgba(245, 166, 35, 0.15);
}
.badge-indigo {
    color: var(--indigo);
    background: rgba(129, 140, 248, 0.1);
    border: 1px solid rgba(129, 140, 248, 0.15);
}

.match-quote {
    font-size: 0.95rem;
    line-height: 1.65;
    color: var(--text-secondary);
    margin-bottom: 1.25rem;
}

.match-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.match-tags span {
    font-size: 0.7rem;
    font-weight: 500;
    padding: 0.25rem 0.6rem;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.04);
    color: var(--muted);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.forge-symbol {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    padding: 1rem 0;
}

@media (min-width: 768px) {
    .forge-symbol {
        padding: 0 0.5rem;
        margin: 0 -0.5rem;
    }
}

.forge-svg {
    width: 100px;
    height: 66px;
    animation: forge-pulse 4s ease-in-out infinite;
}

/* ── Features ──────────────────────────────────────── */
.features-section {
    padding: 5rem 1.5rem 6rem;
}
.features-inner {
    max-width: 64rem;
    margin: 0 auto;
}

.features-grid {
    display: grid;
    gap: 1.5rem;
    grid-template-columns: 1fr;
}

@media (min-width: 768px) {
    .features-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.feature-card {
    padding: 2rem 1.8rem;
    border-radius: 16px;
    border: 1px solid var(--border);
    border-top: 2px solid var(--accent);
    background: var(--surface);
    transition:
        transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
        box-shadow 0.4s,
        border-color 0.3s;
}

.feature-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.06);
}

.feature-icon {
    width: 40px;
    height: 40px;
    margin-bottom: 1.25rem;
}
.feature-icon svg {
    width: 100%;
    height: 100%;
}

.feature-title {
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 1.15rem;
    margin-bottom: 0.65rem;
}

.feature-desc {
    font-size: 0.9rem;
    line-height: 1.65;
    color: var(--muted);
}

/* ── Feed Preview ──────────────────────────────────── */
.feed-section {
    padding: 5rem 1.5rem 6rem;
    position: relative;
}
.feed-inner {
    max-width: 52rem;
    margin: 0 auto;
}

.feed-grid {
    display: grid;
    gap: 1rem;
    grid-template-columns: 1fr;
}

@media (min-width: 640px) {
    .feed-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.feed-card {
    padding: 1.5rem;
    border-radius: 14px;
    border: 1px solid var(--border);
    background: var(--surface);
    transition:
        transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
        border-color 0.3s,
        box-shadow 0.3s;
    cursor: default;
}
.feed-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.08);
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.25);
}

.feed-tag {
    display: inline-block;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.2rem 0.55rem;
    border-radius: 4px;
    margin-bottom: 0.75rem;
}
.tag-fintech {
    color: #34d399;
    background: rgba(52, 211, 153, 0.1);
}
.tag-ai {
    color: var(--indigo);
    background: rgba(129, 140, 248, 0.1);
}
.tag-oss {
    color: var(--amber);
    background: rgba(245, 166, 35, 0.1);
}
.tag-startup {
    color: #f472b6;
    background: rgba(244, 114, 182, 0.1);
}

.feed-title {
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.4;
    margin-bottom: 0.6rem;
}

.feed-meta {
    font-size: 0.75rem;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.feed-source {
    color: var(--text-secondary);
}
.feed-dot {
    opacity: 0.4;
}

/* ── CTA ───────────────────────────────────────────── */
.cta-section {
    padding: 5rem 1.5rem 6rem;
}

.cta-inner {
    position: relative;
    max-width: 44rem;
    margin: 0 auto;
    text-align: center;
    padding: 4rem 2rem;
    border-radius: 24px;
    border: 1px solid var(--border);
    background: var(--surface);
    overflow: hidden;
}

.cta-glow {
    position: absolute;
    top: -120px;
    left: 50%;
    transform: translateX(-50%);
    width: 400px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        rgba(245, 166, 35, 0.1) 0%,
        rgba(129, 140, 248, 0.05) 50%,
        transparent 80%
    );
    filter: blur(40px);
    pointer-events: none;
}

.cta-heading {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: clamp(2rem, 5vw, 3rem);
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin-bottom: 1rem;
    position: relative;
}

.cta-desc {
    font-size: 1.05rem;
    color: var(--muted);
    margin-bottom: 2rem;
    position: relative;
}

.cta-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    position: relative;
}

/* ── Footer ────────────────────────────────────────── */
.site-footer {
    padding: 2.5rem 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.04);
}

.footer-inner {
    max-width: 64rem;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.footer-logo {
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    color: var(--muted);
}

.footer-copy {
    font-size: 0.75rem;
    color: var(--muted);
}

/* ── Scroll Reveal ─────────────────────────────────── */
.scroll-reveal {
    opacity: 0;
    transform: translateY(28px);
    transition:
        opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.scroll-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Stagger within groups */
.match-cards .scroll-reveal:nth-child(1) {
    transition-delay: 0s;
}
.match-cards .scroll-reveal:nth-child(2) {
    transition-delay: 0.15s;
}
.match-cards .scroll-reveal:nth-child(3) {
    transition-delay: 0.3s;
}

.features-grid .scroll-reveal:nth-child(1) {
    transition-delay: 0s;
}
.features-grid .scroll-reveal:nth-child(2) {
    transition-delay: 0.12s;
}
.features-grid .scroll-reveal:nth-child(3) {
    transition-delay: 0.24s;
}

.feed-grid .scroll-reveal:nth-child(1) {
    transition-delay: 0s;
}
.feed-grid .scroll-reveal:nth-child(2) {
    transition-delay: 0.08s;
}
.feed-grid .scroll-reveal:nth-child(3) {
    transition-delay: 0.16s;
}
.feed-grid .scroll-reveal:nth-child(4) {
    transition-delay: 0.24s;
}

/* ── Keyframes ─────────────────────────────────────── */
@keyframes reveal-up {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes orb-drift-warm {
    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(40px, -30px) scale(1.05);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.95);
    }
}

@keyframes orb-drift-cool {
    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(-35px, 25px) scale(0.95);
    }
    66% {
        transform: translate(25px, -35px) scale(1.08);
    }
}

@keyframes rise {
    0% {
        transform: translateY(0) scale(1);
        opacity: var(--particle-opacity, 0.3);
    }
    100% {
        transform: translateY(-100vh) scale(0);
        opacity: 0;
    }
}

@keyframes scroll-bob {
    0%,
    100% {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    50% {
        transform: translateX(-50%) translateY(10px);
        opacity: 0.3;
    }
}

@keyframes forge-pulse {
    0%,
    100% {
        opacity: 0.7;
        transform: scale(1);
    }
    50% {
        opacity: 1;
        transform: scale(1.08);
    }
}
</style>
