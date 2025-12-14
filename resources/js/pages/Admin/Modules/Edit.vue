<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft } from 'lucide-vue-next';
import { index as modulesIndex, update as modulesUpdate } from '@/routes/admin/modules';

interface Teacher {
    id: number;
    name: string;
    email: string;
}

interface Module {
    id: number;
    name: string;
    code: string;
    description: string | null;
    year: number;
    specialization: string | null;
    track: string | null;
    teacher_id: number;
}

interface Props {
    module: Module;
    teachers: Teacher[];
}

const props = defineProps<Props>();

const form = useForm({
    name: props.module.name,
    code: props.module.code,
    description: props.module.description || '',
    year: props.module.year.toString(),
    specialization: props.module.specialization || '',
    track: props.module.track || '',
    teacher_id: props.module.teacher_id.toString(),
});

// Show specialization field for years 3-5
const showSpecialization = computed(() => {
    const year = parseInt(form.year);
    return year >= 3 && year <= 5;
});

// Show track field for GI specialization in years 4-5
const showTrack = computed(() => {
    const year = parseInt(form.year);
    return form.specialization === 'GI' && (year === 4 || year === 5);
});

const submit = () => {
    form.put(modulesUpdate.url({ module: props.module.id }));
};
</script>

<template>
    <AppLayout>
        <Head title="Edit Module" />

        <div class="container mx-auto max-w-2xl py-8">
            <div class="mb-6">
                <Button variant="ghost" @click="$inertia.visit(modulesIndex.url())">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back to Modules
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Edit Module</CardTitle>
                    <CardDescription>Update module information</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Module Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="e.g., Object Oriented Programming"
                            />
                            <span v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="code">Module Code</Label>
                            <Input
                                id="code"
                                v-model="form.code"
                                type="text"
                                required
                                placeholder="e.g., CS101"
                            />
                            <span v-if="form.errors.code" class="text-sm text-destructive">
                                {{ form.errors.code }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="year">Year</Label>
                            <Select v-model="form.year" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select year" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="1">Year 1</SelectItem>
                                    <SelectItem value="2">Year 2</SelectItem>
                                    <SelectItem value="3">Year 3</SelectItem>
                                    <SelectItem value="4">Year 4</SelectItem>
                                    <SelectItem value="5">Year 5</SelectItem>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.year" class="text-sm text-destructive">
                                {{ form.errors.year }}
                            </span>
                        </div>

                        <div v-if="showSpecialization" class="space-y-2">
                            <Label for="specialization">Specialization</Label>
                            <Select v-model="form.specialization" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select specialization" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="GI">Génie Informatique</SelectItem>
                                    <SelectItem value="GRT">Génie des Réseaux et Télécommunications</SelectItem>
                                    <SelectItem value="GInd">Génie Industriel</SelectItem>
                                    <SelectItem value="GM">Génie Mécanique</SelectItem>
                                    <SelectItem value="GA">Génie de l'Eau et de l'Environnement</SelectItem>
                                    <SelectItem value="GPM">Génie des Procédés et Management de la Qualité</SelectItem>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.specialization" class="text-sm text-destructive">
                                {{ form.errors.specialization }}
                            </span>
                            <p class="text-xs text-muted-foreground">
                                Required for years 3-5. This module will only be available for the selected specialization.
                            </p>
                        </div>

                        <div v-if="showTrack" class="space-y-2">
                            <Label for="track">Track (for GI)</Label>
                            <Select v-model="form.track" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select track" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="DEV">Development</SelectItem>
                                    <SelectItem value="AI">Artificial Intelligence</SelectItem>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.track" class="text-sm text-destructive">
                                {{ form.errors.track }}
                            </span>
                            <p class="text-xs text-muted-foreground">
                                Required for GI specialization in years 4-5.
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="teacher_id">Assign Teacher</Label>
                            <Select v-model="form.teacher_id" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select teacher" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="teacher in teachers"
                                        :key="teacher.id"
                                        :value="teacher.id.toString()"
                                    >
                                        {{ teacher.name }} ({{ teacher.email }})
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.teacher_id" class="text-sm text-destructive">
                                {{ form.errors.teacher_id }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description (Optional)</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Enter module description"
                                rows="4"
                            />
                            <span v-if="form.errors.description" class="text-sm text-destructive">
                                {{ form.errors.description }}
                            </span>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update Module' }}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                @click="$inertia.visit(modulesIndex.url())"
                            >
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
