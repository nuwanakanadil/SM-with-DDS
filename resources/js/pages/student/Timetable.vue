<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import ThemeIconToggle from '@/components/ThemeIconToggle.vue';
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import { useAppearance } from '@/composables/useAppearance';
import publicRoutes from '@/routes/public';
import { Head, Link } from '@inertiajs/vue3';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import {
    AlarmClock,
    ArrowLeft,
    BookOpenCheck,
    CalendarDays,
    Clock3,
    FlaskConical,
    Info,
    Palette,
    PhoneCall,
    Sigma,
    Sparkles,
    Zap,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

type Filter = 'all' | 'maths' | 'science';
type SessionType = 'morning' | 'mid' | 'afternoon';

type ExamSession = {
    time: string;
    label: string;
    subjects: string;
    translation: string;
    type: SessionType;
};

type ExamDay = {
    no: number;
    date: string;
    daySi: string;
    dayEn: string;
    highlight?: 'maths' | 'science' | 'basket' | 'art';
    basketNote?: string;
    sessions: ExamSession[];
};

const filter = ref<Filter>('all');
const { resolvedAppearance } = useAppearance();
const pageRoot = ref<HTMLElement | null>(null);
let animationContext: ReturnType<typeof gsap.context> | null = null;

const examDays: ExamDay[] = [
    {
        no: 1,
        date: '2026.11.16',
        daySi: 'සඳුදා',
        dayEn: 'Monday',
        sessions: [{ time: '08:00 - 11:10', label: 'I, II', subjects: 'බුද්ධ ධර්මය / කතෝලික ධර්මය / ක්‍රිස්තියානි ධර්මය / ඉස්ලාම් ධර්මය', translation: 'Buddhism / Catholicism / Christianity / Islam', type: 'morning' }],
    },
    {
        no: 2,
        date: '2026.11.17',
        daySi: 'අඟහරුවාදා',
        dayEn: 'Tuesday',
        sessions: [
            { time: '08:00 - 11:10', label: 'උදේ', subjects: 'සිංහල භාෂාව හා සාහිත්‍යය / දෙමළ භාෂාව / English Literature', translation: 'Sinhala / Tamil / English Literature I, II', type: 'morning' },
            { time: '11:30 - 13:30', label: 'හවස', subjects: 'සිංහල III / දෙමළ III / Tamil III', translation: 'Essay & Summary Paper', type: 'afternoon' },
        ],
    },
    {
        no: 3,
        date: '2026.11.18',
        daySi: 'බදාදා',
        dayEn: 'Wednesday',
        sessions: [
            { time: '08:00 - 11:10', label: 'II', subjects: 'ඉතිහාසය', translation: 'History II', type: 'morning' },
            { time: '11:30 - 12:30', label: 'I', subjects: 'ඉතිහාසය', translation: 'History I', type: 'mid' },
        ],
    },
    {
        no: 4,
        date: '2026.11.19',
        daySi: 'බ්‍රහස්පතින්දා',
        dayEn: 'Thursday',
        highlight: 'maths',
        sessions: [
            { time: '08:00 - 10:00', label: 'I', subjects: 'ගණිතය', translation: 'Mathematics I', type: 'morning' },
            { time: '10:20 - 13:30', label: 'II', subjects: 'ගණිතය', translation: 'Mathematics II', type: 'afternoon' },
        ],
    },
    {
        no: 5,
        date: '2026.11.20',
        daySi: 'සිකුරාදා',
        dayEn: 'Friday',
        sessions: [
            { time: '08:00 - 09:00', label: 'I', subjects: 'ඉංග්‍රීසි භාෂාව', translation: 'English I', type: 'morning' },
            { time: '09:10 - 11:10', label: 'II', subjects: 'ඉංග්‍රීසි භාෂාව', translation: 'English II', type: 'mid' },
        ],
    },
    {
        no: 6,
        date: '2026.11.23',
        daySi: 'සඳුදා',
        dayEn: 'Monday',
        highlight: 'science',
        sessions: [
            { time: '08:00 - 11:10', label: 'II', subjects: 'විද්‍යාව', translation: 'Science II', type: 'morning' },
            { time: '12:00 - 13:00', label: 'I', subjects: 'විද්‍යාව', translation: 'Science I', type: 'afternoon' },
        ],
    },
    {
        no: 7,
        date: '2026.11.25',
        daySi: 'බදාදා',
        dayEn: 'Wednesday',
        highlight: 'basket',
        basketNote: 'Basket 01 — ව්‍යාපාර හා ගිණුම්කරණ / භූගෝල / පුරවැසි / දෙවන භාෂා / ප්‍රායෝගික තාක්ෂණ කුසලතා',
        sessions: [{ time: '08:00 - 11:10', label: 'I, II', subjects: 'ව්‍යාපාර හා ගිණුම්කරණ අධ්‍යයනය / භූගෝල විද්‍යාව / පුරවැසි අධ්‍යාපනය / දෙවන භාෂා - සිංහල / දෙමළ / ප්‍රායෝගික හා තාක්ෂණ කුසලතා', translation: 'Business / Geography / Civic / Second Language / PTS', type: 'morning' }],
    },
    {
        no: 8,
        date: '2026.11.26',
        daySi: 'බ්‍රහස්පතින්දා',
        dayEn: 'Thursday',
        highlight: 'art',
        basketNote: 'Basket 02 — සෞන්දර්ය විෂයන් / තාක්ෂණය: සංගීතය, නර්තනය, චිත්‍ර, නාට්‍ය, සාහිත්‍යය රසාස්වාදය සහ ICT',
        sessions: [
            { time: '08:00 - 11:10', label: 'I, II', subjects: 'සංගීතය / නර්තනය / චිත්‍ර / නාට්‍ය හා රංගකලාව / සාහිත්‍යය රසාස්වාදය / ICT', translation: 'Music / Dance / Art / Drama / Literature / ICT', type: 'morning' },
            { time: '08:00 - 09:00', label: 'චිත්‍ර I', subjects: 'චිත්‍ර කලාව / சித்திரம்', translation: 'Art I', type: 'morning' },
            { time: '09:15 - 11:15', label: 'චිත්‍ර III', subjects: 'චිත්‍ර කලාව - ප්‍රායෝගික', translation: 'Art III Practical', type: 'mid' },
            { time: '11:45 - 13:45', label: 'චිත්‍ර II', subjects: 'චිත්‍ර කලාව / சித்திரம் II', translation: 'Art II', type: 'afternoon' },
        ],
    },
    {
        no: 9,
        date: '2026.11.27',
        daySi: 'සිකුරාදා',
        dayEn: 'Friday',
        highlight: 'basket',
        basketNote: 'Basket 03 — සෞඛ්‍ය / ගෘහ ආර්ථික / කෘෂිකර්ම සහ තාක්ෂණ විෂයන්',
        sessions: [{ time: '08:00 - 11:10', label: 'I, II', subjects: 'සෞඛ්‍ය හා ශාරීරික අධ්‍යාපනය / ගෘහ ආර්ථික විද්‍යාව / කෘෂිකර්ම හා ආහාර තාක්ෂණය / මත්ස්‍ය හා මුහුදු සම්පත් තාක්ෂණය / නිර්මාණ හා තාක්ෂණය', translation: 'Health / Home Economics / Agriculture / Design & Technology', type: 'morning' }],
    },
];

const visibleDays = computed(() => {
    if (filter.value === 'all') return examDays;
    return examDays.filter((day) => day.highlight === filter.value);
});

const cardAccents: Record<NonNullable<ExamDay['highlight']>, string> = {
    maths: 'tt-day--maths',
    science: 'tt-day--science',
    art: 'tt-day--art',
    basket: 'tt-day--basket',
};

const cardAccent = (day: ExamDay) => day.highlight ? cardAccents[day.highlight] : '';

const sessionAccent = (type: SessionType) => `tt-session--${type}`;

onMounted(() => {
    if (!pageRoot.value || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    gsap.registerPlugin(ScrollTrigger);
    animationContext = gsap.context(() => {
        const intro = gsap.timeline({ defaults: { ease: 'power3.out' } });
        intro
            .from('.tt-nav', { y: -18, opacity: 0, duration: 0.55 })
            .from('.tt-hero__mark', { scale: 0.72, rotate: -8, opacity: 0, duration: 0.8 }, '-=0.25')
            .from('.tt-hero__copy > *', { y: 24, opacity: 0, duration: 0.65, stagger: 0.08 }, '-=0.55')
            .from('.tt-metric', { y: 16, opacity: 0, duration: 0.45, stagger: 0.07 }, '-=0.35');

        gsap.utils.toArray<HTMLElement>('.tt-day').forEach((card) => {
            gsap.from(card, {
                scrollTrigger: { trigger: card, start: 'top 88%', once: true },
                y: 34,
                opacity: 0,
                duration: 0.65,
                ease: 'power2.out',
            });
        });
    }, pageRoot.value);
});

onUnmounted(() => animationContext?.revert());
</script>

<template>
    <Head title="Exam Timetable" />
    <main ref="pageRoot" class="tt-shell" :class="`tt-shell--${resolvedAppearance}`">
        <div class="tt-atmosphere" aria-hidden="true">
            <div class="tt-grid"></div>
            <div class="tt-orbit tt-orbit--one"></div>
            <div class="tt-orbit tt-orbit--two"></div>
            <div class="tt-watermark">Σ</div>
        </div>

        <div class="tt-frame">
            <header class="tt-nav">
                <Link :href="publicRoutes.results.url()" class="tt-brand">
                    <span class="tt-brand__logo"><ApplicationLogo /></span>
                    <span>
                        <strong>{{ APP_BRAND_NAME }}</strong>
                        <small>{{ APP_SYSTEM_SUBTITLE }}</small>
                    </span>
                </Link>
                <div class="tt-nav__actions">
                    <ThemeIconToggle />
                    <Link :href="publicRoutes.results.url()" class="tt-back">
                        <ArrowLeft />
                        <span>Back to results</span>
                    </Link>
                </div>
            </header>

            <section class="tt-hero">
                <div class="tt-hero__mark" aria-hidden="true">
                    <span>Σ</span>
                    <small>O/L</small>
                </div>
                <div class="tt-hero__copy">
                    <p class="tt-kicker"><Sparkles /> Central Province · 2026 edition</p>
                    <h1>
                        Practice exam
                        <span>timetable.</span>
                    </h1>
                    <p class="tt-hero__sinhala">අ.පො.ස. සාමාන්‍ය පෙළ පෙරහුරු විභාග කාලසටහන</p>
                    <p class="tt-hero__description">Nine focused exam days. Every paper, time slot and subject basket—mapped clearly from first bell to final paper.</p>
                </div>
                <div class="tt-hero__status">
                    <span class="tt-live"><i></i> Schedule live</span>
                    <strong>16—27</strong>
                    <small>November 2026</small>
                </div>
            </section>

            <section class="tt-metrics" aria-label="Timetable summary">
                <div class="tt-metric"><CalendarDays /><span><strong>09</strong><small>Exam days</small></span></div>
                <div class="tt-metric"><BookOpenCheck /><span><strong>34+</strong><small>Subjects</small></span></div>
                <div class="tt-metric tt-metric--accent"><AlarmClock /><span><strong>+10</strong><small>Reading minutes</small></span></div>
                <div class="tt-metric"><Clock3 /><span><strong>08:00</strong><small>First bell</small></span></div>
            </section>

            <section class="tt-control-deck">
                <div>
                    <p class="tt-label">01 / Schedule control</p>
                    <h2>Find your paper</h2>
                </div>
                <div class="tt-filters" role="group" aria-label="Filter timetable">
                    <button type="button" :class="{ active: filter === 'all' }" :aria-pressed="filter === 'all'" @click="filter = 'all'">All days</button>
                    <button type="button" :class="{ active: filter === 'maths' }" :aria-pressed="filter === 'maths'" @click="filter = 'maths'"><Sigma /> Mathematics</button>
                    <button type="button" :class="{ active: filter === 'science' }" :aria-pressed="filter === 'science'" @click="filter = 'science'"><FlaskConical /> Science</button>
                </div>
                <div class="tt-arrival"><AlarmClock /><span><strong>Arrive by 07:30</strong><small>Every morning paper starts at 08:00</small></span></div>
            </section>

            <section class="tt-feature">
                <div class="tt-feature__icon"><Zap /></div>
                <div>
                    <p class="tt-label">Super Maths focus</p>
                    <h2>Mathematics · 19 November</h2>
                    <p>Paper I <strong>08:00—10:00</strong> <span></span> Paper II <strong>10:20—13:30</strong></p>
                </div>
                <div class="tt-feature__badge">DAY 04</div>
            </section>

            <section class="tt-days" aria-live="polite">
                <article v-for="day in visibleDays" :key="day.no" class="tt-day" :class="cardAccent(day)">
                    <div class="tt-day__beam"></div>
                    <header class="tt-day__header">
                        <div class="tt-day__number"><small>DAY</small><strong>{{ String(day.no).padStart(2, '0') }}</strong></div>
                        <div class="tt-day__date">
                            <p>{{ day.date }} · {{ day.dayEn }}</p>
                            <h3>{{ day.daySi }}</h3>
                        </div>
                        <span v-if="day.highlight" class="tt-day__tag">
                            <Sigma v-if="day.highlight === 'maths'" />
                            <FlaskConical v-else-if="day.highlight === 'science'" />
                            <Palette v-else-if="day.highlight === 'art'" />
                            <BookOpenCheck v-else />
                            {{ day.highlight === 'art' ? 'Basket 02' : day.highlight }}
                        </span>
                    </header>

                    <div v-if="day.basketNote" class="tt-basket-note"><Info /> <span>{{ day.basketNote }}</span></div>

                    <div class="tt-sessions">
                        <div v-for="(session, index) in day.sessions" :key="`${day.no}-${index}`" class="tt-session" :class="sessionAccent(session.type)">
                            <div class="tt-session__time"><Clock3 /> {{ session.time }}</div>
                            <div class="tt-session__copy">
                                <div><strong>{{ session.subjects }}</strong><span>{{ session.label }}</span></div>
                                <p>{{ session.translation }}</p>
                            </div>
                        </div>
                    </div>
                    <footer class="tt-day__footer"><span>supermathswithdamith.lk</span><span><i></i> Central Province</span></footer>
                </article>
            </section>

            <section class="tt-notes">
                <div class="tt-notes__heading"><p class="tt-label">02 / Exam protocol</p><h2>Before you enter the hall.</h2></div>
                <ol>
                    <li><span>01</span><p><strong>Reading time</strong>Three-hour papers receive 10 extra minutes before writing begins.</p></li>
                    <li><span>02</span><p><strong>Morning arrival</strong>Be at the examination hall by 07:30 for every 08:00 paper.</p></li>
                    <li><span>03</span><p><strong>Subject baskets</strong>Attend only your selected basket subject. Practical-paper times may differ.</p></li>
                </ol>
            </section>

            <section class="tt-support">
                <div><p class="tt-label">Still unsure?</p><h2>Get timetable support.</h2><p>Ask about a date, paper or subject basket before exam day.</p></div>
                <a href="tel:0712378372"><PhoneCall /> 071 237 8372</a>
            </section>

            <footer class="tt-footer">
                <span>© 2026 {{ APP_BRAND_NAME }}</span>
                <span>Built for focused students · Σ</span>
            </footer>
        </div>
    </main>
</template>

<style scoped>
.tt-shell{--ink:#080a12;--panel:#111522;--line:#262c42;--soft:#9399ab;--orange:#ff6b00;--gold:#ffc247;--indigo:#6864ff;position:relative;min-height:100vh;overflow:hidden;background:var(--ink);color:#f8fafc;font-family:'Instrument Sans','Noto Sans Sinhala',sans-serif}.tt-atmosphere{position:fixed;inset:0;pointer-events:none}.tt-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:56px 56px;mask-image:linear-gradient(to bottom,#000,transparent 85%)}.tt-orbit{position:absolute;border:1px solid rgba(104,100,255,.15);border-radius:50%}.tt-orbit--one{width:48rem;height:48rem;right:-18rem;top:-22rem;box-shadow:0 0 8rem rgba(104,100,255,.1)}.tt-orbit--two{width:34rem;height:34rem;left:-20rem;top:42%;border-color:rgba(255,107,0,.12);box-shadow:0 0 8rem rgba(255,107,0,.08)}.tt-watermark{position:absolute;right:-.04em;top:10rem;color:rgba(255,255,255,.018);font-size:min(70vw,62rem);font-weight:800;line-height:.75}.tt-frame{position:relative;z-index:1;width:min(92rem,calc(100% - 2rem));margin:auto}.tt-nav{display:flex;align-items:center;justify-content:space-between;padding:1.15rem 0;border-bottom:1px solid rgba(255,255,255,.08)}.tt-brand{display:flex;align-items:center;gap:.75rem;color:#fff}.tt-brand__logo{display:grid;width:2.65rem;height:2.65rem;padding:.42rem;border:1px solid var(--line);border-radius:.8rem;background:#fff}.tt-brand__logo :deep(svg){width:100%;height:100%}.tt-brand strong,.tt-brand small{display:block}.tt-brand strong{font-size:.9rem}.tt-brand small{margin-top:.16rem;color:#737a8e;font-size:.65rem;letter-spacing:.08em;text-transform:uppercase}.tt-back{display:flex;align-items:center;gap:.5rem;border:1px solid var(--line);border-radius:999px;padding:.7rem 1rem;color:#c9ceda;font-size:.78rem;font-weight:700;transition:.25s}.tt-back:hover{border-color:#4a5274;background:#171c2c;color:#fff}.tt-back svg{width:1rem}.tt-hero{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:clamp(1.5rem,4vw,4.5rem);padding:clamp(3rem,8vw,7rem) 0 4.5rem}.tt-hero__mark{position:relative;display:grid;place-items:center;width:clamp(8rem,14vw,12rem);aspect-ratio:1;border:1px solid rgba(255,194,71,.5);border-radius:2rem;background:linear-gradient(145deg,var(--orange),var(--gold));color:#0b0c12;box-shadow:0 0 0 .7rem rgba(255,107,0,.05),0 0 5rem rgba(255,107,0,.24)}.tt-hero__mark span{font-size:clamp(4.5rem,8vw,7rem);font-weight:800;line-height:1}.tt-hero__mark small{position:absolute;bottom:1rem;font:700 .62rem ui-monospace,monospace;letter-spacing:.2em}.tt-kicker,.tt-label{display:flex;align-items:center;gap:.45rem;color:#8c91a3;font:700 .68rem ui-monospace,monospace;letter-spacing:.14em;text-transform:uppercase}.tt-kicker svg{width:.9rem;color:var(--orange)}.tt-hero h1{max-width:13ch;margin:.9rem 0 1rem;font-size:clamp(3rem,6.5vw,6.5rem);font-weight:800;letter-spacing:-.065em;line-height:.84}.tt-hero h1 span{display:block;color:transparent;background:linear-gradient(90deg,#fff 10%,#767d91);background-clip:text}.tt-hero__sinhala{font-family:'Noto Sans Sinhala',sans-serif;font-size:clamp(1rem,1.6vw,1.35rem);font-weight:700;color:#daddE7}.tt-hero__description{max-width:43rem;margin-top:1rem;color:var(--soft);font-size:.95rem;line-height:1.7}.tt-hero__status{align-self:end;min-width:11rem;padding-left:1.4rem;border-left:1px solid var(--line)}.tt-live{display:flex;align-items:center;gap:.45rem;color:#a5abc0;font:700 .63rem ui-monospace,monospace;text-transform:uppercase}.tt-live i,.tt-day__footer i{width:.42rem;height:.42rem;border-radius:50%;background:#34d399;box-shadow:0 0 .6rem #34d399}.tt-hero__status strong,.tt-hero__status small{display:block}.tt-hero__status strong{margin-top:1rem;font-size:2.5rem;letter-spacing:-.07em}.tt-hero__status small{color:#747b8f;font:600 .67rem ui-monospace,monospace;text-transform:uppercase}.tt-metrics{display:grid;grid-template-columns:repeat(4,1fr);border:1px solid var(--line);border-radius:1.2rem;background:rgba(17,21,34,.8);backdrop-filter:blur(18px)}.tt-metric{display:flex;align-items:center;gap:1rem;padding:1.25rem 1.4rem;border-right:1px solid var(--line)}.tt-metric:last-child{border:0}.tt-metric>svg{width:1.2rem;color:#737a90}.tt-metric strong,.tt-metric small{display:block}.tt-metric strong{font:800 1.2rem ui-monospace,monospace}.tt-metric small{margin-top:.15rem;color:#737a8e;font-size:.67rem}.tt-metric--accent{background:linear-gradient(120deg,rgba(255,107,0,.18),transparent)}.tt-metric--accent svg,.tt-metric--accent strong{color:#ff8b3d}.tt-control-deck{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:2rem;margin:2rem 0;padding:1.4rem 1.5rem;border:1px solid var(--line);border-radius:1.2rem;background:rgba(13,16,27,.76)}.tt-control-deck h2,.tt-feature h2,.tt-notes h2,.tt-support h2{margin-top:.35rem;font-size:1.25rem;font-weight:750;letter-spacing:-.025em}.tt-filters{display:flex;gap:.55rem}.tt-filters button{display:flex;align-items:center;gap:.45rem;padding:.65rem .9rem;border:1px solid var(--line);border-radius:999px;background:#111522;color:#858ca0;font-size:.73rem;font-weight:700;cursor:pointer;transition:.25s}.tt-filters button svg{width:.9rem}.tt-filters button:hover,.tt-filters button.active{border-color:#fff;background:#fff;color:#080a12}.tt-arrival{display:flex;justify-self:end;align-items:center;gap:.75rem}.tt-arrival>svg{width:1.1rem;color:var(--orange)}.tt-arrival strong,.tt-arrival small{display:block}.tt-arrival strong{font-size:.78rem}.tt-arrival small{margin-top:.15rem;color:#72798d;font-size:.64rem}.tt-feature{position:relative;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:1.2rem;margin-bottom:2rem;padding:1.4rem 1.5rem;overflow:hidden;border:1px solid rgba(255,107,0,.38);border-radius:1.2rem;background:linear-gradient(110deg,rgba(255,107,0,.16),rgba(17,21,34,.9) 45%,rgba(104,100,255,.1));box-shadow:0 1.5rem 5rem -3rem rgba(255,107,0,.55)}.tt-feature__icon{display:grid;place-items:center;width:3.5rem;aspect-ratio:1;border-radius:1rem;background:linear-gradient(145deg,var(--orange),var(--gold));color:#0b0c12}.tt-feature__icon svg{width:1.5rem}.tt-feature p:last-child{margin-top:.4rem;color:#8e95a8;font-size:.78rem}.tt-feature p span{display:inline-block;width:.25rem;height:.25rem;margin:0 .5rem;border-radius:50%;background:var(--orange)}.tt-feature__badge{border:1px solid rgba(255,107,0,.4);border-radius:999px;padding:.5rem .8rem;color:#ff9852;font:700 .66rem ui-monospace,monospace}.tt-days{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem}.tt-day{position:relative;display:flex;min-width:0;flex-direction:column;overflow:hidden;border:1px solid var(--line);border-radius:1.3rem;background:rgba(17,21,34,.92);box-shadow:0 1.5rem 4rem -3rem #000;transition:transform .35s,border-color .35s,box-shadow .35s}.tt-day:hover{transform:translateY(-5px);border-color:#3d4668;box-shadow:0 2rem 5rem -2.6rem rgba(104,100,255,.35)}.tt-day__beam{height:3px;background:linear-gradient(90deg,#343b53,rgba(104,100,255,.65))}.tt-day--maths{border-color:rgba(255,107,0,.55);background:linear-gradient(155deg,rgba(255,107,0,.12),#111522 35%);box-shadow:0 1.7rem 5rem -2.5rem rgba(255,107,0,.4)}.tt-day--maths .tt-day__beam{background:linear-gradient(90deg,var(--orange),var(--gold))}.tt-day--science .tt-day__beam{background:linear-gradient(90deg,var(--indigo),#a4a1ff)}.tt-day--art .tt-day__beam{background:linear-gradient(90deg,#d946ef,#fb7185)}.tt-day--basket .tt-day__beam{background:linear-gradient(90deg,#10b981,#5eead4)}.tt-day__header{display:flex;align-items:center;gap:.8rem;padding:1.1rem}.tt-day__number{display:grid;place-items:center;width:3rem;height:3rem;flex:0 0 auto;border:1px solid var(--line);border-radius:.85rem;background:#090c14}.tt-day__number small{font:700 .48rem ui-monospace,monospace;color:#646b7e;letter-spacing:.12em}.tt-day__number strong{font:800 1rem ui-monospace,monospace}.tt-day--maths .tt-day__number{border-color:#ff7b21;background:var(--orange);color:#090a0e}.tt-day--maths .tt-day__number small{color:rgba(0,0,0,.55)}.tt-day__date{min-width:0}.tt-day__date p{color:#7e8599;font:700 .62rem ui-monospace,monospace;letter-spacing:.03em}.tt-day__date h3{margin-top:.3rem;font-family:'Noto Sans Sinhala',sans-serif;font-size:1rem;font-weight:750}.tt-day__tag{display:flex;align-items:center;gap:.3rem;margin-left:auto;border:1px solid var(--line);border-radius:999px;padding:.35rem .5rem;color:#aab0c0;font:700 .52rem ui-monospace,monospace;text-transform:uppercase}.tt-day__tag svg{width:.7rem}.tt-basket-note{display:flex;gap:.55rem;margin:0 1.1rem 1rem;padding:.7rem;border:1px solid rgba(52,211,153,.18);border-radius:.75rem;background:rgba(16,185,129,.07);color:#9ccbbc;font-family:'Noto Sans Sinhala','Instrument Sans',sans-serif;font-size:.67rem;line-height:1.55}.tt-basket-note svg{width:.85rem;flex:0 0 auto}.tt-sessions{display:grid;gap:.65rem;padding:0 1.1rem 1.1rem}.tt-session{display:grid;grid-template-columns:auto 1fr;gap:.75rem;padding:.8rem;border:1px solid #22283a;border-radius:.85rem;background:#0b0e17}.tt-session--afternoon{border-color:rgba(255,107,0,.18);background:rgba(255,107,0,.045)}.tt-session__time{display:flex;align-items:center;align-self:start;gap:.3rem;border:1px solid #2c3349;border-radius:999px;padding:.35rem .48rem;color:#d2d5de;font:700 .55rem ui-monospace,monospace;white-space:nowrap}.tt-session--afternoon .tt-session__time{border-color:var(--orange);background:var(--orange);color:#090a0e}.tt-session__time svg{width:.68rem}.tt-session__copy{min-width:0}.tt-session__copy>div{display:flex;align-items:flex-start;gap:.35rem}.tt-session__copy strong{font-family:'Noto Sans Sinhala','Instrument Sans',sans-serif;font-size:.71rem;line-height:1.55}.tt-session__copy span{flex:0 0 auto;border:1px solid #2d344a;border-radius:.3rem;padding:.12rem .28rem;color:#787f92;font:700 .48rem ui-monospace,monospace}.tt-session__copy p{margin-top:.25rem;color:#656c7e;font-size:.62rem;line-height:1.4}.tt-day__footer{display:flex;justify-content:space-between;margin-top:auto;padding:.7rem 1.1rem;border-top:1px solid rgba(255,255,255,.045);color:#41475a;font:600 .49rem ui-monospace,monospace}.tt-day__footer span:last-child{display:flex;align-items:center;gap:.35rem}.tt-day__footer i{width:.25rem;height:.25rem}.tt-notes{display:grid;grid-template-columns:.65fr 1.35fr;gap:4rem;margin-top:4rem;padding:3rem;border:1px solid var(--line);border-radius:1.5rem;background:linear-gradient(145deg,rgba(17,21,34,.92),rgba(11,14,23,.88))}.tt-notes__heading h2{max-width:10ch;font-size:clamp(2rem,4vw,3.5rem);line-height:.95}.tt-notes ol{display:grid;gap:0}.tt-notes li{display:grid;grid-template-columns:2.5rem 1fr;gap:1rem;padding:1.2rem 0;border-bottom:1px solid var(--line)}.tt-notes li:last-child{border:0}.tt-notes li>span{color:#596075;font:700 .62rem ui-monospace,monospace}.tt-notes li strong{display:block;margin-bottom:.35rem;color:#f4f5f7;font-size:.83rem}.tt-notes li p{color:#858ca0;font-size:.75rem;line-height:1.6}.tt-support{display:flex;align-items:end;justify-content:space-between;gap:2rem;margin:1rem 0 4rem;padding:2rem 2.2rem;border:1px solid rgba(104,100,255,.28);border-radius:1.5rem;background:linear-gradient(120deg,rgba(104,100,255,.16),rgba(17,21,34,.9))}.tt-support p:last-child{margin-top:.5rem;color:#858ca0;font-size:.78rem}.tt-support a{display:flex;align-items:center;gap:.55rem;border-radius:999px;background:#fff;padding:.8rem 1.1rem;color:#090a0e;font-size:.78rem;font-weight:800}.tt-support a svg{width:1rem}.tt-footer{display:flex;justify-content:space-between;padding:1.3rem 0;border-top:1px solid rgba(255,255,255,.08);color:#4e5568;font:600 .58rem ui-monospace,monospace;text-transform:uppercase;letter-spacing:.08em}
@media(max-width:1000px){.tt-hero{grid-template-columns:auto 1fr}.tt-hero__status{grid-column:2}.tt-days{grid-template-columns:repeat(2,minmax(0,1fr))}.tt-control-deck{grid-template-columns:1fr}.tt-arrival{justify-self:start}.tt-notes{grid-template-columns:1fr;gap:2rem}.tt-notes__heading h2{max-width:none}.tt-metrics{grid-template-columns:repeat(2,1fr)}.tt-metric:nth-child(2){border-right:0}.tt-metric:nth-child(-n+2){border-bottom:1px solid var(--line)}}
@media(max-width:640px){.tt-frame{width:min(100% - 1.1rem,92rem)}.tt-brand small{display:none}.tt-back span{display:none}.tt-hero{grid-template-columns:1fr;gap:1.5rem;padding:3rem .5rem}.tt-hero__mark{width:7rem;border-radius:1.4rem}.tt-hero__status{grid-column:auto}.tt-hero h1{font-size:3.5rem}.tt-metrics{grid-template-columns:1fr}.tt-metric{border-right:0;border-bottom:1px solid var(--line)}.tt-control-deck{padding:1.1rem}.tt-filters{display:grid;grid-template-columns:1fr}.tt-filters button{justify-content:center}.tt-feature{grid-template-columns:auto 1fr}.tt-feature__badge{display:none}.tt-days{grid-template-columns:1fr}.tt-notes{padding:1.5rem}.tt-support{align-items:stretch;flex-direction:column}.tt-support a{justify-content:center}.tt-footer{gap:1rem;flex-direction:column}.tt-session{grid-template-columns:1fr}.tt-session__time{justify-self:start}}
@media(prefers-reduced-motion:reduce){.tt-day,.tt-back,.tt-filters button{transition:none}.tt-day:hover{transform:none}}

/* Blue accent system shared by both appearances. */
.tt-shell{--orange:#2563eb;--gold:#60a5fa;--indigo:#2563eb}.tt-nav__actions{display:flex;align-items:center;gap:.5rem}.tt-nav__actions :deep(button){border:1px solid var(--line);color:inherit}.tt-orbit--two{border-color:rgba(37,99,235,.15);box-shadow:0 0 8rem rgba(37,99,235,.12)}.tt-hero__mark{border-color:rgba(96,165,250,.55);box-shadow:0 0 0 .7rem rgba(37,99,235,.06),0 0 5rem rgba(37,99,235,.28)}.tt-metric--accent{background:linear-gradient(120deg,rgba(37,99,235,.18),transparent)}.tt-metric--accent svg,.tt-metric--accent strong{color:#60a5fa}.tt-feature{border-color:rgba(37,99,235,.42);background:linear-gradient(110deg,rgba(37,99,235,.18),rgba(17,21,34,.9) 45%,rgba(96,165,250,.1));box-shadow:0 1.5rem 5rem -3rem rgba(37,99,235,.6)}.tt-feature__badge{border-color:rgba(37,99,235,.45);color:#93c5fd}.tt-day--maths{border-color:rgba(37,99,235,.6);background:linear-gradient(155deg,rgba(37,99,235,.14),#111522 35%);box-shadow:0 1.7rem 5rem -2.5rem rgba(37,99,235,.42)}.tt-day--maths .tt-day__number{border-color:#60a5fa}.tt-session--afternoon{border-color:rgba(37,99,235,.22);background:rgba(37,99,235,.055)}

/* Matching light technical mode. */
.tt-shell.tt-shell--light{--ink:#f4f7fc;--panel:#fff;--line:#d9e1ee;--soft:#64748b;color-scheme:light;background:#f4f7fc;color:#0f172a}.tt-shell--light .tt-grid{background-image:linear-gradient(rgba(37,99,235,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(37,99,235,.06) 1px,transparent 1px)}.tt-shell--light .tt-orbit{border-color:rgba(37,99,235,.13)}.tt-shell--light .tt-orbit--one{box-shadow:0 0 8rem rgba(37,99,235,.1)}.tt-shell--light .tt-watermark{color:rgba(37,99,235,.035)}.tt-shell--light .tt-nav{border-color:rgba(15,23,42,.1)}.tt-shell--light .tt-brand{color:#0f172a}.tt-shell--light .tt-brand small{color:#64748b}.tt-shell--light .tt-back{background:rgba(255,255,255,.7);color:#334155}.tt-shell--light .tt-back:hover{border-color:#93b4f5;background:#fff;color:#1d4ed8}.tt-shell--light .tt-hero__mark{color:#fff}.tt-shell--light .tt-hero h1 span{background:linear-gradient(90deg,#1e293b,#64748b);background-clip:text}.tt-shell--light .tt-hero__sinhala{color:#334155}.tt-shell--light .tt-kicker,.tt-shell--light .tt-label{color:#64748b}.tt-shell--light .tt-live{color:#475569}.tt-shell--light .tt-hero__status small{color:#64748b}.tt-shell--light .tt-metrics{background:rgba(255,255,255,.86);box-shadow:0 1.5rem 4rem -3rem rgba(37,99,235,.3)}.tt-shell--light .tt-metric>svg{color:#64748b}.tt-shell--light .tt-metric small{color:#64748b}.tt-shell--light .tt-metric--accent svg,.tt-shell--light .tt-metric--accent strong{color:#2563eb}.tt-shell--light .tt-control-deck{background:rgba(255,255,255,.82);box-shadow:0 1.5rem 4rem -3rem rgba(15,23,42,.2)}.tt-shell--light .tt-filters button{background:#f8fafc;color:#475569}.tt-shell--light .tt-filters button:hover,.tt-shell--light .tt-filters button.active{border-color:#2563eb;background:#2563eb;color:#fff}.tt-shell--light .tt-arrival small{color:#64748b}.tt-shell--light .tt-feature{background:linear-gradient(110deg,rgba(37,99,235,.14),rgba(255,255,255,.94) 48%,rgba(96,165,250,.12))}.tt-shell--light .tt-feature p:last-child{color:#64748b}.tt-shell--light .tt-feature__badge{color:#1d4ed8}.tt-shell--light .tt-day{background:rgba(255,255,255,.94);box-shadow:0 1.5rem 4rem -3rem rgba(15,23,42,.3)}.tt-shell--light .tt-day:hover{border-color:#93b4f5;box-shadow:0 2rem 5rem -2.6rem rgba(37,99,235,.3)}.tt-shell--light .tt-day__beam{background:linear-gradient(90deg,#94a3b8,#60a5fa)}.tt-shell--light .tt-day--maths{background:linear-gradient(155deg,rgba(37,99,235,.1),#fff 38%)}.tt-shell--light .tt-day__number{background:#f8fafc}.tt-shell--light .tt-day--maths .tt-day__number{background:#2563eb;color:#fff}.tt-shell--light .tt-day--maths .tt-day__number small{color:rgba(255,255,255,.7)}.tt-shell--light .tt-day__date p{color:#64748b}.tt-shell--light .tt-day__tag{background:#f8fafc;color:#475569}.tt-shell--light .tt-session{border-color:#e2e8f0;background:#f8fafc}.tt-shell--light .tt-session--afternoon{border-color:rgba(37,99,235,.2);background:#eff6ff}.tt-shell--light .tt-session__time{border-color:#cbd5e1;color:#334155}.tt-shell--light .tt-session--afternoon .tt-session__time{border-color:#2563eb;background:#2563eb;color:#fff}.tt-shell--light .tt-session__copy span{border-color:#d7dfeb;color:#64748b}.tt-shell--light .tt-session__copy p{color:#64748b}.tt-shell--light .tt-day__footer{border-color:#e9eef5;color:#94a3b8}.tt-shell--light .tt-notes{background:linear-gradient(145deg,#fff,#f8fafc);box-shadow:0 1.5rem 4rem -3rem rgba(15,23,42,.22)}.tt-shell--light .tt-notes li strong{color:#0f172a}.tt-shell--light .tt-notes li p{color:#64748b}.tt-shell--light .tt-support{border-color:rgba(37,99,235,.2);background:linear-gradient(120deg,#dbeafe,#fff)}.tt-shell--light .tt-support p:last-child{color:#64748b}.tt-shell--light .tt-support a{background:#2563eb;color:#fff}.tt-shell--light .tt-footer{border-color:rgba(15,23,42,.1);color:#64748b}

/* Readability pass: preserve hierarchy while meeting practical contrast and size needs. */
.tt-shell{-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility}.tt-day__date p{font-size:.69rem;line-height:1.45}.tt-day__date h3{font-size:1.08rem;line-height:1.5}.tt-day__tag{font-size:.59rem;padding:.4rem .58rem}.tt-basket-note{font-size:.76rem;line-height:1.75;padding:.8rem .85rem}.tt-session{gap:.9rem;padding:.92rem}.tt-session__time{font-size:.62rem;line-height:1.3;padding:.4rem .55rem}.tt-session__copy strong{font-size:.82rem;font-weight:650;line-height:1.7}.tt-session__copy span{font-size:.55rem;line-height:1.3;padding:.15rem .34rem}.tt-session__copy p{margin-top:.32rem;font-size:.72rem;line-height:1.55}.tt-day__footer{font-size:.56rem;padding:.78rem 1.1rem}.tt-shell--dark .tt-day__date p{color:#aab2c5}.tt-shell--dark .tt-day__tag{color:#d0d5e0}.tt-shell--dark .tt-session__copy p{color:#a2aabd}.tt-shell--dark .tt-session__copy span{border-color:#46506c;color:#b2bacb}.tt-shell--dark .tt-day__footer{color:#778096}.tt-shell--dark .tt-basket-note{border-color:rgba(52,211,153,.32);background:rgba(16,185,129,.11);color:#b9eadb}.tt-shell--light .tt-day__date p{color:#475569}.tt-shell--light .tt-session__copy p{color:#52627a}.tt-shell--light .tt-session__copy span{color:#475569}.tt-shell--light .tt-day__footer{color:#64748b}.tt-shell--light .tt-basket-note{border-color:#86efac;background:#ecfdf5;color:#166534}.tt-shell--light .tt-basket-note svg{color:#15803d}
</style>
