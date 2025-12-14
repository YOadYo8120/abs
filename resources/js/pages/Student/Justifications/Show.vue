<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Download } from 'lucide-vue-next'

interface JustificationFile {
    id: number
    file_name: string
    file_type: string
    file_size: number
    download_url: string
}

interface Justification {
    id: number
    description: string
    status: string
    admin_notes: string | null
    reviewed_at: string | null
    reviewed_by: string | null
    created_at: string
    attendance: {
        date: string
        module: string
        module_code: string
    }
    files: JustificationFile[]
}

interface Props {
    justification: Justification
}

const props = defineProps<Props>()

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'default'
        case 'rejected': return 'destructive'
        case 'pending': return 'outline'
        default: return 'secondary'
    }
}

const getStatusLabel = (status: string) => {
    return status.charAt(0).toUpperCase() + status.slice(1)
}
</script>

<template>
    <AppLayout>
        <Head title="Justification Details" />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Justification Info -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Justification Details</CardTitle>
                            <Badge :variant="getStatusColor(justification.status)">
                                {{ getStatusLabel(justification.status) }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-2">Absence Details</p>
                            <p class="text-lg font-semibold">{{ justification.attendance.module_code }}</p>
                            <p class="text-sm">{{ justification.attendance.module }}</p>
                            <p class="text-sm text-muted-foreground">{{ new Date(justification.attendance.date).toLocaleString() }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-2">Your Explanation</p>
                            <div class="p-4 bg-muted rounded-lg">
                                <p class="text-sm whitespace-pre-wrap">{{ justification.description }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-2">Submitted</p>
                            <p class="text-sm">{{ new Date(justification.created_at).toLocaleString() }}</p>
                        </div>

                        <div v-if="justification.reviewed_at">
                            <p class="text-sm font-medium text-muted-foreground mb-2">Reviewed</p>
                            <p class="text-sm">{{ new Date(justification.reviewed_at).toLocaleString() }}</p>
                            <p v-if="justification.reviewed_by" class="text-sm text-muted-foreground">
                                By {{ justification.reviewed_by }}
                            </p>
                        </div>

                        <div v-if="justification.admin_notes && justification.status !== 'pending'">
                            <p class="text-sm font-medium text-muted-foreground mb-2">Admin Notes</p>
                            <div class="p-4 bg-muted rounded-lg">
                                <p class="text-sm whitespace-pre-wrap">{{ justification.admin_notes }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Supporting Documents -->
                <Card>
                    <CardHeader>
                        <CardTitle>Supporting Documents ({{ justification.files.length }})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div v-for="file in justification.files" :key="file.id"
                                 class="flex items-center justify-between p-4 border rounded-lg hover:bg-accent transition-colors">
                                <div class="flex-1">
                                    <p class="font-medium">{{ file.file_name }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ file.file_type.toUpperCase() }} • {{ file.file_size }} KB
                                    </p>
                                </div>
                                <a :href="file.download_url" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
                                    <Download class="h-4 w-4" />
                                    Download
                                </a>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Actions -->
                <div class="flex gap-3">
                    <Button variant="outline" @click="router.visit('/student/justifications')">
                        Back to List
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
