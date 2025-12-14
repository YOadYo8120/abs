<script setup lang="ts">
import { ref } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Input } from '@/components/ui/input'
import { FileUp, X } from 'lucide-vue-next'

interface Attendance {
    id: number
    date: string
    module: string
    module_code: string
}

interface Props {
    attendance: Attendance
}

const props = defineProps<Props>()

const form = useForm({
    attendance_id: props.attendance.id,
    description: '',
    files: [] as File[],
})

const fileInput = ref<HTMLInputElement | null>(null)

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files) {
        const newFiles = Array.from(target.files)
        const remainingSlots = 5 - form.files.length
        const filesToAdd = newFiles.slice(0, remainingSlots)
        form.files = [...form.files, ...filesToAdd]
    }
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const removeFile = (index: number) => {
    form.files = form.files.filter((_, i) => i !== index)
}

const submit = () => {
    const formData = new FormData()
    formData.append('attendance_id', String(form.attendance_id))
    formData.append('description', form.description)

    // Append each file
    form.files.forEach((file) => {
        formData.append('files[]', file)
    })

    router.post('/student/justifications', formData, {
        onSuccess: () => {
            router.visit('/student/justifications')
        },
        onError: (errors) => {
            console.error('Submission errors:', errors)
            form.errors = errors
        },
    })
}
</script>

<template>
    <AppLayout>
        <Head title="Submit Justification" />

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Submit Justification for Absence</CardTitle>
                        <CardDescription>
                            <div class="mt-2">
                                <strong>Module:</strong> {{ attendance.module_code }} - {{ attendance.module }}
                            </div>
                            <div class="mt-1">
                                <strong>Date:</strong> {{ new Date(attendance.date).toLocaleString() }}
                            </div>
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Description -->
                            <div class="space-y-2">
                                <Label for="description">Description</Label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Explain the reason for your absence..."
                                    rows="4"
                                    :class="{ 'border-red-500': form.errors.description }"
                                    required
                                />
                                <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                                <p class="text-xs text-muted-foreground">Maximum 1000 characters</p>
                            </div>

                            <!-- File Upload -->
                            <div class="space-y-2">
                                <Label for="files">Supporting Documents</Label>
                                <div class="flex items-center gap-4">
                                    <Input
                                        ref="fileInput"
                                        id="files"
                                        type="file"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        multiple
                                        @change="handleFileChange"
                                        :disabled="form.files.length >= 5"
                                        :class="{ 'border-red-500': form.errors.files }"
                                    />
                                    <FileUp class="h-5 w-5 text-muted-foreground" />
                                </div>
                                <p v-if="form.errors.files" class="text-sm text-red-500">{{ form.errors.files }}</p>
                                <p class="text-xs text-muted-foreground">
                                    Upload 1-5 files (PDF, JPG, PNG). Maximum 10MB per file.
                                </p>

                                <!-- Selected Files -->
                                <div v-if="form.files.length > 0" class="mt-4 space-y-2">
                                    <p class="text-sm font-medium">Selected Files ({{ form.files.length }}/5):</p>
                                    <div v-for="(file, index) in form.files" :key="index"
                                         class="flex items-center justify-between p-3 border rounded-lg">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium">{{ file.name }}</p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ (file.size / 1024 / 1024).toFixed(2) }} MB
                                            </p>
                                        </div>
                                        <Button
                                            type="button"
                                            size="sm"
                                            variant="ghost"
                                            @click="removeFile(index)"
                                        >
                                            <X class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <Button
                                    type="submit"
                                    :disabled="form.processing || form.files.length === 0"
                                >
                                    {{ form.processing ? 'Submitting...' : 'Submit Justification' }}
                                </Button>
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="router.visit('/student/justifications')"
                                >
                                    Cancel
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
