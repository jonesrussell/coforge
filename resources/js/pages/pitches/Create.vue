<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PitchController from '@/actions/App/Http/Controllers/PitchController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

type Skill = {
    id: number;
    name: string;
    slug: string;
};

type Props = {
    pitch: { pitch_type: string; status: string };
    skills: Skill[];
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pitches',
        href: PitchController.index.url(),
    },
    {
        title: 'Create pitch',
    },
];
</script>

<template>
    <Head title="Create your pitch" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <Heading
                variant="small"
                title="Create your pitch"
                description="Describe your project or what you're looking for in a co-founder."
            />

            <Form
                v-bind="PitchController.store.form()"
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input
                        id="title"
                        name="title"
                        required
                        placeholder="e.g. B2B SaaS for inventory management"
                    />
                    <InputError :message="errors.title" />
                </div>

                <div class="grid gap-2">
                    <Label for="tagline">Tagline</Label>
                    <Input
                        id="tagline"
                        name="tagline"
                        required
                        placeholder="One line that sums it up"
                    />
                    <InputError :message="errors.tagline" />
                </div>

                <div class="grid gap-2">
                    <Label for="body">Full description</Label>
                    <textarea
                        id="body"
                        name="body"
                        required
                        rows="6"
                        class="flex min-h-[120px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="What's the vision? What stage are you at? What do you need?"
                    />
                    <InputError :message="errors.body" />
                </div>

                <div class="grid gap-2">
                    <Label>Pitch type</Label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input
                                type="radio"
                                name="pitch_type"
                                value="project"
                                :checked="pitch.pitch_type === 'project'"
                            />
                            <span class="text-sm">I have a project / idea</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input
                                type="radio"
                                name="pitch_type"
                                value="seeking"
                                :checked="pitch.pitch_type === 'seeking'"
                            />
                            <span class="text-sm"
                                >I'm seeking a co-founder</span
                            >
                        </label>
                    </div>
                    <InputError :message="errors.pitch_type" />
                </div>

                <div class="grid gap-2">
                    <Label for="role_sought">Role sought (optional)</Label>
                    <Input
                        id="role_sought"
                        name="role_sought"
                        placeholder="e.g. Technical co-founder, Business co-founder"
                    />
                    <InputError :message="errors.role_sought" />
                </div>

                <div v-if="skills.length" class="grid gap-2">
                    <Label>Skills</Label>
                    <div class="flex flex-wrap gap-4">
                        <label
                            v-for="skill in skills"
                            :key="skill.id"
                            class="flex items-center gap-2"
                        >
                            <input
                                type="checkbox"
                                name="skill_ids[]"
                                :value="skill.id"
                                class="size-4 rounded border-input"
                            />
                            <span class="text-sm">{{ skill.name }}</span>
                        </label>
                    </div>
                    <InputError :message="errors.skill_ids" />
                </div>

                <div class="grid gap-2">
                    <Label>Status</Label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input
                                type="radio"
                                name="status"
                                value="draft"
                                :checked="pitch.status === 'draft'"
                            />
                            <span class="text-sm">Draft</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input
                                type="radio"
                                name="status"
                                value="published"
                                :checked="pitch.status === 'published'"
                            />
                            <span class="text-sm">Published</span>
                        </label>
                    </div>
                    <InputError :message="errors.status" />
                </div>

                <Button :disabled="processing" type="submit"
                    >Create pitch</Button
                >
            </Form>
        </div>
    </AppLayout>
</template>
