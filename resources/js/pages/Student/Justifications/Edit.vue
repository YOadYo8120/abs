<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Badge } from '@/components/ui/badge'
import { FileUp, X, File, AlertCircle } from 'lucide-vue-next'

const props = defineProps<{
    justification: {
        id: number
        description: string
        admin_notes?: string
        files: Array<{
            id: number
            file_name: string
            file_type: string
            file_size: number
        }>
    }
    attendance: {
        id: number
        date: string
        module: string
        module_code: string
    }
}>()

const description = ref(props.justification.description)
const newFiles = ref<File[]>([])
const filesToDelete = ref<number[]>([])
const errors = ref<Record<string, string>>({})

const existingFiles = computed(() => {
    return props.justification.files.filter(file => !filesToDelete.value.includes(file.id))
})

const totalFileCount = computed(() => {
    return existingFiles.value.length + newFiles.value.length
})

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files) {
        const selectedFiles = Array.from(target.files)

        // Check if total would exceed 5
        if (totalFileCount.value + selectedFiles.length > 5) {
            errors.value.files = 'Maximum 5 files allowed (including existing files)'
            return
        }

        // Validate each file
        for (const file of selectedFiles) {
            if (!['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                errors.value.files = 'Only PDF, JPG, JPEG, and PNG files are allowed'
                return
            }
            if (file.size > 10 * 1024 * 1024) { // 10MB
                errors.value.files = 'Each file must be less than 10MB'
                return
            }
        }

        newFiles.value = [...newFiles.value, ...selectedFiles]
        errors.value.files = ''
        target.value = ''
    }
}

const removeNewFile = (index: number) => {
    newFiles.value.splice(index, 1)
}

const markForDeletion = (fileId: number) => {
    filesToDelete.value.push(fileId)
}

const unmarkForDeletion = (fileId: number) => {
    const index = filesToDelete.value.indexOf(fileId)
    if (index > -1) {
        filesToDelete.value.splice(index, 1)
    }
}

const submit = () => {
    errors.value = {}

    if (!description.value.trim()) {
        errors.value.description = 'Description is required'
        return
    }

    if (totalFileCount.value === 0) {
        errors.value.files = 'At least one file is required'
        return
    }

    if (totalFileCount.value > 5) {
        errors.value.files = 'Maximum 5 files allowed'
        return
    }

    const formData = new FormData()
    formData.append('_method', 'PUT')
    formData.append('description', description.value)

    newFiles.value.forEach((file) => {
        formData.append('files[]', file)
    })

    filesToDelete.value.forEach((fileId) => {
        formData.append('delete_files[]', String(fileId))
    })

    router.post(`/student/justifications/${props.justification.id}`, formData, {
        onSuccess: () => {
            router.visit('/student/justifications')
        },
        onError: (errors) => {
            console.error('Update errors:', errors)
        }
    })
}
</script>

<template>
    <AppLayout title="Edit Justification">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit Justification</CardTitle>
                        <CardDescription>
                            Update your justification for absence on {{ attendance.date }} - {{ attendance.module }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Rejection Notice -->
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                            <AlertCircle class="h-5 w-5 text-red-600 mt-0.5 flex-shrink-0" />
                            <div class="flex-1">
                                <h4 class="font-semibold text-red-900 mb-1">Your justification was rejected</h4>
                                <p v-if="justification.admin_notes" class="text-sm text-red-800 whitespace-pre-wrap">
                                    {{ justification.admin_notes }}
                                </p>
                                <p v-else class="text-sm text-red-800">
                                    No reason provided by admin.
                                </p>
                            </div>
                        </div>

                        <!-- Absence Info -->
                        <div class="p-4 bg-muted rounded-lg">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-muted-foreground">Module</p>
                                    <p class="font-medium">{{ attendance.module }} ({{ attendance.module_code }})</p>
                                </div>
                                <div>
                                    <p class="text-muted-foreground">Date</p>
                                    <p class="font-medium">{{ attendance.date }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description *</Label>
                            <Textarea
                                id="description"
                                v-model="description"
                                placeholder="Explain the reason for your absence..."
                                rows="5"
                                :class="{ 'border-destructive': errors.description }"
                            />
                            <p v-if="errors.description" class="text-sm text-destructive">
                                {{ errors.description }}
                            </p>
                        </div>

                        <!-- Existing Files -->
                        <div v-if="justification.files.length > 0" class="space-y-2">
                            <Label>Current Files</Label>
                            <div class="space-y-2">
                                <div
                                    v-for="file in justification.files"
                                    :key="file.id"
                                    class="flex items-center justify-between p-3 border rounded-lg"
                                    :class="filesToDelete.includes(file.id) ? 'bg-red-50 border-red-200' : 'bg-muted'"
                                >
                                    <div class="flex items-center gap-2 flex-1">
                                        <File class="h-4 w-4 text-muted-foreground" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate" :class="filesToDelete.includes(file.id) ? 'line-through text-muted-foreground' : ''">
                                                {{ file.file_name }}
                                            </p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ file.file_type.toUpperCase() }} • {{ file.file_size }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <Button
                                        v-if="!filesToDelete.includes(file.id)"
                                        variant="ghost"
                                        size="sm"
                                        @click="markForDeletion(file.id)"
                                        class="text-destructive hover:text-destructive"
                                    >
                                        <X class="h-4 w-4" />
                                    </Button>
                                    <Button
                                        v-else
                                        variant="ghost"
                                        size="sm"
                                        @click="unmarkForDeletion(file.id)"
                                        class="text-green-600 hover:text-green-600"
                                    >
                                        Undo
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- New Files Upload -->
                        <div class="space-y-2">
                            <Label for="files">Add New Files</Label>
                            <div class="flex items-center gap-2">
                                <Input
                                    id="files"
                                    type="file"
                                    multiple
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    @change="handleFileChange"
                                    :class="{ 'border-destructive': errors.files }"
                                />
                                <Badge variant="secondary">{{ totalFileCount }}/5</Badge>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                PDF, JPG, JPEG, PNG (max 10MB each)
                            </p>
                            <p v-if="errors.files" class="text-sm text-destructive">
                                {{ errors.files }}
                            </p>
                        </div>

                        <!-- New Files List -->
                        <div v-if="newFiles.length > 0" class="space-y-2">
                            <Label>New Files to Upload</Label>
                            <div class="space-y-2">
                                <div
                                    v-for="(file, index) in newFiles"
                                    :key="index"
                                    class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg"
                                >
                                    <div class="flex items-center gap-2 flex-1">
                                        <FileUp class="h-4 w-4 text-green-600" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate">{{ file.name }}</p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ (file.size / 1024).toFixed(2) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="removeNewFile(index)"
                                        class="text-destructive hover:text-destructive"
                                    >
                                        <X class="h-4 w-4" />
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-4">
                            <Button @click="submit" class="flex-1">
                                Update & Resubmit
                            </Button>
                            <Button
                                variant="outline"
                                @click="router.visit('/student/justifications')"
                            >
                                Cancel
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
