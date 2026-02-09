<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

type Skill = {
    id: number;
    name: string;
    slug: string;
};

type User = {
    id: number;
    name: string;
};

type Pitch = {
    id: number;
    title: string;
    slug: string;
    tagline: string;
    body: string;
    pitch_type: string;
    role_sought: string | null;
    status: string;
    user: User;
    skills: Skill[];
};

type Paginator = {
    data: Pitch[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

type Props = {
    pitches: Paginator;
    filters: {
        pitch_type?: string;
        role_sought?: string;
        skills?: number[];
    };
    skills: Skill[];
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Discover', href: '/discover' },
];

function buildDiscoverUrl(merge: Record<string, string | number[] | undefined>) {
    const params = new URLSearchParams();
    if (merge.pitch_type) params.set('pitch_type', String(merge.pitch_type));
    if (merge.role_sought) params.set('role_sought', String(merge.role_sought));
    if (merge.skills?.length)
        merge.skills.forEach((id) => params.append('skills[]', String(id)));
    const q = params.toString();
    return `/discover${q ? `?${q}` : ''}`;
}

function applyFilters() {
    const form = document.getElementById('discover-filters') as HTMLFormElement;
    if (!form) return;
    const fd = new FormData(form);
    const pitch_type = (fd.get('pitch_type') as string) || undefined;
    const role_sought = (fd.get('role_sought') as string) || undefined;
    const skills = fd.getAll('skills[]').map(Number).filter(Boolean);
    router.get(
        buildDiscoverUrl({
            pitch_type,
            role_sought,
            skills: skills.length ? skills : undefined,
        }),
    );
}

function clearFilters() {
    router.get('/discover');
}
</script>

<template>
    <Head title="Discover pitches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold">Discover</h1>
                <Button as-child variant="outline" size="sm">
                    <Link :href="dashboard().url">Dashboard</Link>
                </Button>
            </div>

            <Card id="discover-filters">
                <CardHeader>
                    <CardTitle class="text-base">Filters</CardTitle>
                    <CardDescription>
                        Filter by pitch type, role sought, or skills
                    </CardDescription>
                </CardHeader>
                <CardContent class="flex flex-wrap items-end gap-4">
                    <div class="grid gap-2">
                        <label for="filter-pitch_type" class="text-sm font-medium"
                            >Pitch type</label
                        >
                        <select
                            id="filter-pitch_type"
                            name="pitch_type"
                            :value="filters.pitch_type ?? ''"
                            class="flex h-9 w-[180px] rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                            @change="applyFilters"
                        >
                            <option value="">Any</option>
                            <option value="project">Project / idea</option>
                            <option value="seeking">Seeking co-founder</option>
                        </select>
                    </div>
                    <div class="grid gap-2">
                        <label
                            for="filter-role_sought"
                            class="text-sm font-medium"
                            >Role sought</label
                        >
                        <input
                            id="filter-role_sought"
                            type="text"
                            name="role_sought"
                            :value="filters.role_sought ?? ''"
                            placeholder="e.g. Technical"
                            class="flex h-9 w-[180px] rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                            @keydown.enter.prevent="applyFilters"
                        />
                    </div>
                    <Button type="button" size="sm" @click="applyFilters">
                        Apply filters
                    </Button>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Skills</label>
                        <div class="flex flex-wrap gap-2">
                            <label
                                v-for="skill in skills"
                                :key="skill.id"
                                class="flex items-center gap-2 text-sm"
                            >
                                <input
                                    type="checkbox"
                                    name="skills[]"
                                    :value="skill.id"
                                    :checked="
                                        (filters.skills ?? []).includes(skill.id)
                                    "
                                    class="size-4 rounded border-input"
                                    @change="applyFilters"
                                />
                                {{ skill.name }}
                            </label>
                        </div>
                    </div>
                    <Button variant="secondary" size="sm" @click="clearFilters">
                        Clear
                    </Button>
                </CardContent>
            </Card>

            <div
                v-if="pitches.data.length === 0"
                class="rounded-lg border border-dashed p-8 text-center text-muted-foreground"
            >
                No published pitches match your filters.
            </div>

            <div
                v-else
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
            >
                <Card
                    v-for="pitch in pitches.data"
                    :key="pitch.id"
                    class="flex flex-col"
                >
                    <CardHeader class="flex-grow">
                        <CardTitle class="line-clamp-1">
                            {{ pitch.title }}
                        </CardTitle>
                        <CardDescription class="line-clamp-2">
                            {{ pitch.tagline }}
                        </CardDescription>
                        <div class="flex flex-wrap gap-1 pt-1">
                            <Badge variant="secondary" class="text-xs">
                                {{ pitch.pitch_type }}
                            </Badge>
                            <Badge
                                v-if="pitch.role_sought"
                                variant="outline"
                                class="text-xs"
                            >
                                {{ pitch.role_sought }}
                            </Badge>
                        </div>
                        <div
                            v-if="pitch.skills?.length"
                            class="flex flex-wrap gap-1 pt-1"
                        >
                            <Badge
                                v-for="s in pitch.skills"
                                :key="s.id"
                                variant="outline"
                                class="text-xs"
                            >
                                {{ s.name }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="flex-grow">
                        <p
                            class="line-clamp-3 text-sm text-muted-foreground"
                        >
                            {{ pitch.body }}
                        </p>
                    </CardContent>
                    <CardFooter>
                        <Button as-child variant="default" size="sm">
                            <Link :href="`/pitches/${pitch.slug}`">
                                View full pitch
                            </Link>
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <div
                v-if="pitches.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template
                    v-for="(link, idx) in pitches.links"
                    :key="idx"
                >
                    <Button
                        v-if="link.url"
                        as-child
                        :variant="link.active ? 'default' : 'outline'"
                        size="sm"
                    >
                        <Link :href="link.url">
                            <span v-html="link.label" />
                        </Link>
                    </Button>
                    <span
                        v-else
                        class="px-2 text-sm text-muted-foreground"
                        v-html="link.label"
                    />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
