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
import { Upload } from 'lucide-vue-next'

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
    description: '',
    file: null as File | null,
    scope: 'module' as 'module' | 'class',
    module_id: null as number | null,
    year: null as number | null,
    specialization: null as string | null,
    track: null as string | null,
    semester: null as number | null,
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

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        form.file = target.files[0]
    }
}

// Reset dependent fields when scope changes
watch(() => form.scope, () => {
    form.module_id = null
    form.year = null
    form.specialization = null
    form.track = null
    form.semester = null
})

const submit = () => {
    form.post('/teacher/resources')
}
</script>

<template>
    <AppLayout>
        <Head title="Upload Resource" />

        <div class="container mx-auto py-6 max-w-3xl">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Upload Resource</h1>
                <p class="text-muted-foreground">Share files with your students</p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Resource Details</CardTitle>
                    <CardDescription>Upload a PDF file for your students</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Scope Selection -->
                        <div class="space-y-2">
                            <Label for="scope">Resource Scope</Label>
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
                                Choose who will receive this resource
                            </p>
                        </div>

                        <!-- Module Selection -->
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

                        <!-- Class Selection -->
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
                                placeholder="Enter resource title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description (Optional)</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Enter resource description"
                                rows="3"
                                :class="{ 'border-red-500': form.errors.description }"
                            />
                            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                        </div>

                        <!-- File Upload -->
                        <div class="space-y-2">
                            <Label for="file">PDF File</Label>
                            <div class="flex items-center gap-4">
                                <Input
                                    id="file"
                                    type="file"
                                    accept=".pdf"
                                    @change="handleFileChange"
                                    :class="{ 'border-red-500': form.errors.file }"
                                />
                                <Upload class="h-5 w-5 text-muted-foreground" />
                            </div>
                            <p v-if="form.file" class="text-sm text-muted-foreground">
                                Selected: {{ form.file.name }} ({{ (form.file.size / 1024 / 1024).toFixed(2) }} MB)
                            </p>
                            <p v-if="form.errors.file" class="text-sm text-red-500">{{ form.errors.file }}</p>
                            <p class="text-xs text-muted-foreground">Maximum file size: 50MB</p>
                        </div>

                        <div class="flex gap-3">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Uploading...' : 'Upload Resource' }}
                            </Button>
                            <Button type="button" variant="outline" @click="router.visit('/teacher/resources')">
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
