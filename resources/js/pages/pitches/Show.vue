<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import PitchController from '@/actions/App/Http/Controllers/PitchController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
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

type Props = {
    pitch: Pitch;
    hasExpressedInterest: boolean;
};

const props = defineProps<Props>();

const page = usePage();
const authUser = page.props.auth?.user as { id: number } | undefined;
const isOwner = authUser && authUser.id === props.pitch.user?.id;
const canExpressInterest =
    authUser &&
    !isOwner &&
    props.pitch.status === 'published' &&
    !props.hasExpressedInterest;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Discover', href: '/discover' },
];
</script>

<template>
    <Head :title="pitch.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <Card class="max-w-3xl">
                <CardHeader>
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <CardTitle class="text-xl">{{ pitch.title }}</CardTitle>
                            <CardDescription class="mt-1">
                                {{ pitch.tagline }}
                            </CardDescription>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            <Badge variant="secondary">
                                {{ pitch.pitch_type }}
                            </Badge>
                            <Badge
                                v-if="pitch.role_sought"
                                variant="outline"
                            >
                                {{ pitch.role_sought }}
                            </Badge>
                        </div>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        by {{ pitch.user?.name }}
                    </p>
                </CardHeader>
                <CardContent class="space-y-4">
                    <p
                        class="whitespace-pre-wrap text-sm text-muted-foreground"
                    >
                        {{ pitch.body }}
                    </p>
                    <div
                        v-if="pitch.skills?.length"
                        class="flex flex-wrap gap-2"
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
            </Card>

            <Card v-if="canExpressInterest" class="max-w-3xl">
                <CardHeader>
                    <CardTitle class="text-base">Express interest</CardTitle>
                    <CardDescription>
                        Send a message to {{ pitch.user?.name }} about this
                        pitch.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Form
                        :action="`/pitches/${pitch.id}/interest`"
                        method="post"
                        class="space-y-4"
                    >
                        <div class="grid gap-2">
                            <Label for="interest-message">Message (optional)</Label>
                            <textarea
                                id="interest-message"
                                name="message"
                                rows="3"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                                placeholder="Introduce yourself or say why you're interested..."
                            />
                        </div>
                        <Button type="submit">Send interest</Button>
                    </Form>
                </CardContent>
            </Card>

            <p
                v-else-if="
                    authUser && !isOwner && hasExpressedInterest
                "
                class="text-sm text-muted-foreground"
            >
                You have already expressed interest in this pitch.
            </p>

            <div class="flex flex-wrap gap-2">
                <Button as-child variant="outline" size="sm">
                    <Link href="/discover">Back to Discover</Link>
                </Button>
                <Button
                    v-if="authUser"
                    as-child
                    variant="default"
                    size="sm"
                >
                    <Link :href="PitchController.index.url()"
                        >Your pitch</Link
                    >
                </Button>
                <Button
                    v-if="isOwner"
                    as-child
                    variant="secondary"
                    size="sm"
                >
                    <Link href="/pitches/interests">View interests</Link>
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
