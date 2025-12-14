<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'

interface Module {
    id: number
    code: string
    name: string
}

interface Class {
    year: number
    specialization: string | null
    track: string | null
    semester: number
    name: string
}

interface Props {
    modules: Module[]
    classes: Class[]
}

const props = defineProps<Props>()

const form = useForm({
    title: '',
    content: '',
    scope: 'module' as 'module' | 'class',
    module_id: null as number | null,
    year: null as number | null,
    specialization: null as string | null,
    track: null as string | null,
    semester: null as number | null,
    publish_now: true,
})

const selectedClass = computed(() => {
    if (!form.year || !form.semester) return null
    return props.classes.find(c =>
        c.year === form.year &&
        c.semester === form.semester &&
        c.specialization === form.specialization &&
        c.track === form.track
    )
})

// Reset dependent fields when scope changes
watch(() => form.scope, (newScope) => {
    form.module_id = null
    form.year = null
    form.specialization = null
    form.track = null
    form.semester = null
})

const submit = () => {
    form.post('/teacher/announcements')
}
</script>

<template>
    <AppLayout>
        <Head title="Create Announcement" />

        <div class="container mx-auto py-6 max-w-3xl">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Create Announcement</h1>
                <p class="text-muted-foreground">Send an announcement to your students</p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Announcement Details</CardTitle>
                    <CardDescription>Choose the scope and content of your announcement</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Scope Selection -->
                        <div class="space-y-2">
                            <Label for="scope">Announcement Scope</Label>
                            <Select v-model="form.scope">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select scope" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="module">Module - All students in this module</SelectItem>
                                    <SelectItem value="class">Class - All students in a specific class</SelectItem>
                                </SelectContent>
                            </Select>
                            <p class="text-xs text-muted-foreground">
                                Choose who will receive this announcement
                            </p>
                        </div>

                        <!-- Module Selection (if scope is module) -->
                        <div v-if="form.scope === 'module'" class="space-y-2">
                            <Label for="module">Module</Label>
                            <Select v-model="form.module_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select module" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="module in modules" :key="module.id" :value="module.id">
                                        {{ module.code }} - {{ module.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.module_id" class="text-sm text-red-500">{{ form.errors.module_id }}</p>
                        </div>

                        <!-- Class Selection (if scope is class) -->
                        <div v-if="form.scope === 'class'" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label>Class</Label>
                                    <Select v-model="form.year">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Select year" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="c in classes" :key="c.name" :value="c.year">
                                                {{ c.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <p v-if="selectedClass" class="text-sm text-muted-foreground">
                                Selected: {{ selectedClass.name }}
                            </p>
                        </div>

                        <!-- Title -->
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                placeholder="Enter announcement title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                        </div>

                        <!-- Content -->
                        <div class="space-y-2">
                            <Label for="content">Content</Label>
                            <Textarea
                                id="content"
                                v-model="form.content"
                                placeholder="Enter announcement content"
                                rows="6"
                                :class="{ 'border-red-500': form.errors.content }"
                            />
                            <p v-if="form.errors.content" class="text-sm text-red-500">{{ form.errors.content }}</p>
                        </div>

                        <div class="flex gap-3">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Creating...' : 'Create Announcement' }}
                            </Button>
                            <Button type="button" variant="outline" @click="router.visit('/teacher/announcements')">
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
