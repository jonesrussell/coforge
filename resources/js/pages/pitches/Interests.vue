<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
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
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

type User = {
    id: number;
    name: string;
    email: string;
};

type Interest = {
    id: number;
    message: string | null;
    status: string;
    created_at: string;
    user: User;
};

type Pitch = {
    id: number;
    title: string;
    slug: string;
};

type Props = {
    pitch: Pitch;
    interests: Interest[];
};

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pitches', href: PitchController.index.url() },
    { title: 'Your pitch', href: PitchController.edit(props.pitch).url },
    { title: 'Interests' },
];
</script>

<template>
    <Head title="Interests on your pitch" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h1 class="text-2xl font-semibold">Interests</h1>
                <Button as-child variant="outline" size="sm">
                    <Link :href="PitchController.edit(pitch).url"
                        >Edit pitch</Link
                    >
                </Button>
            </div>

            <p class="text-sm text-muted-foreground">
                People who expressed interest in your pitch
                <strong>{{ pitch.title }}</strong>.
            </p>

            <div
                v-if="interests.length === 0"
                class="rounded-lg border border-dashed p-8 text-center text-muted-foreground"
            >
                No one has expressed interest yet. Share your pitch from the
                Discover page so others can find it.
            </div>

            <div v-else class="flex flex-col gap-4">
                <Card
                    v-for="interest in interests"
                    :key="interest.id"
                    class="max-w-2xl"
                >
                    <CardHeader class="flex flex-row items-start justify-between space-y-0">
                        <div>
                            <CardTitle class="text-base">
                                {{ interest.user.name }}
                            </CardTitle>
                            <CardDescription>
                                {{ interest.user.email }}
                            </CardDescription>
                        </div>
                        <Badge
                            :variant="
                                interest.status === 'accepted'
                                    ? 'default'
                                    : interest.status === 'declined'
                                      ? 'secondary'
                                      : 'outline'
                            "
                        >
                            {{ interest.status }}
                        </Badge>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <p
                            v-if="interest.message"
                            class="text-sm text-muted-foreground whitespace-pre-wrap"
                        >
                            {{ interest.message }}
                        </p>
                        <p v-else class="text-sm text-muted-foreground italic">
                            No message provided.
                        </p>
                        <div
                            v-if="interest.status === 'pending'"
                            class="flex gap-2"
                        >
                            <Form
                                :action="`/pitches/interests/${interest.id}`"
                                method="patch"
                                class="inline"
                            >
                                <input
                                    type="hidden"
                                    name="status"
                                    value="accepted"
                                />
                                <Button type="submit" size="sm">
                                    Accept
                                </Button>
                            </Form>
                            <Form
                                :action="`/pitches/interests/${interest.id}`"
                                method="patch"
                                class="inline"
                            >
                                <input
                                    type="hidden"
                                    name="status"
                                    value="declined"
                                />
                                <Button
                                    type="submit"
                                    variant="outline"
                                    size="sm"
                                >
                                    Decline
                                </Button>
                            </Form>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
