<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PitchController from '@/actions/App/Http/Controllers/PitchController';
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
import { type BreadcrumbItem } from '@/types';

type Skill = {
    id: number;
    name: string;
    slug: string;
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
    skills: Skill[];
};

type Props = {
    pitch: Pitch;
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pitches', href: PitchController.index.url() },
    { title: 'Your pitch' },
];
</script>

<template>
    <Head title="Your pitch" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0"
                >
                    <div>
                        <CardTitle>{{ pitch.title }}</CardTitle>
                        <CardDescription>{{ pitch.tagline }}</CardDescription>
                    </div>
                    <Badge
                        :variant="
                            pitch.status === 'published'
                                ? 'default'
                                : 'secondary'
                        "
                    >
                        {{ pitch.status }}
                    </Badge>
                </CardHeader>
                <CardContent>
                    <p
                        class="text-sm whitespace-pre-wrap text-muted-foreground"
                    >
                        {{ pitch.body }}
                    </p>
                    <div
                        v-if="pitch.skills?.length"
                        class="mt-4 flex flex-wrap gap-2"
                    >
                        <Badge
                            v-for="skill in pitch.skills"
                            :key="skill.id"
                            variant="outline"
                        >
                            {{ skill.name }}
                        </Badge>
                    </div>
                </CardContent>
                <CardFooter class="flex gap-2">
                    <Button as-child variant="default">
                        <Link :href="PitchController.edit(pitch).url"
                            >Edit pitch</Link
                        >
                    </Button>
                    <Button as-child variant="outline">
                        <Link href="/pitches/interests">Interests</Link>
                    </Button>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
