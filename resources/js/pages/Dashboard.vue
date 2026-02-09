<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PitchController from '@/actions/App/Http/Controllers/PitchController';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

type Pitch = {
    id: number;
    title: string;
    tagline: string;
    status: string;
} | null;

type Props = {
    pitch: Pitch;
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <Card v-if="pitch" class="max-w-2xl">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0"
                >
                    <div>
                        <CardTitle>Your pitch</CardTitle>
                        <CardDescription>{{ pitch.title }}</CardDescription>
                    </div>
                    <Button as-child variant="default">
                        <Link :href="PitchController.index.url()"
                            >View pitch</Link
                        >
                    </Button>
                </CardHeader>
                <CardContent>
                    <p class="text-sm text-muted-foreground">
                        {{ pitch.tagline }}
                    </p>
                    <p class="mt-2 text-xs text-muted-foreground">
                        Status: {{ pitch.status }}
                    </p>
                </CardContent>
            </Card>

            <Card v-else class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Your pitch</CardTitle>
                    <CardDescription>
                        Create your first pitch to tell others about your
                        project or what you're looking for in a co-founder.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Button as-child variant="default">
                        <Link :href="PitchController.create.url()"
                            >Create your first pitch</Link
                        >
                    </Button>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
