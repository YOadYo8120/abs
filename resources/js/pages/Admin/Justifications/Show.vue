<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Download, CheckCircle, XCircle } from 'lucide-vue-next'

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
    student: {
        id: number
        name: string
        email: string
        year: number
        specialization: string | null
    }
    attendance: {
        id: number
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

const form = useForm({
    status: props.justification.status,
    admin_notes: props.justification.admin_notes || '',
})

const approve = () => {
    form.status = 'approved'
    form.patch(`/admin/justifications/${props.justification.id}`, {
        onSuccess: () => {
            router.visit('/admin/justifications')
        },
    })
}

const reject = () => {
    form.status = 'rejected'
    form.patch(`/admin/justifications/${props.justification.id}`, {
        onSuccess: () => {
            router.visit('/admin/justifications')
        },
    })
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'default'
        case 'rejected': return 'destructive'
        case 'pending': return 'outline'
        default: return 'secondary'
    }
}
</script>

<template>
    <AppLayout>
        <Head title="Review Justification" />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Student & Absence Info -->
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>Justification Review</CardTitle>
                            <Badge :variant="getStatusColor(justification.status)">
                                {{ justification.status.charAt(0).toUpperCase() + justification.status.slice(1) }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Student</p>
                                <p class="text-lg font-semibold">{{ justification.student.name }}</p>
                                <p class="text-sm text-muted-foreground">{{ justification.student.email }}</p>
                                <p class="text-sm text-muted-foreground">
                                    Year {{ justification.student.year }}
                                    <span v-if="justification.student.specialization"> - {{ justification.student.specialization }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Absence Details</p>
                                <p class="text-lg font-semibold">{{ justification.attendance.module_code }}</p>
                                <p class="text-sm">{{ justification.attendance.module }}</p>
                                <p class="text-sm text-muted-foreground">{{ new Date(justification.attendance.date).toLocaleString() }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-2">Student's Explanation</p>
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

                <!-- Admin Notes & Actions -->
                <Card>
                    <CardHeader>
                        <CardTitle>Admin Review</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="admin_notes">Notes (Optional)</Label>
                            <Textarea
                                id="admin_notes"
                                v-model="form.admin_notes"
                                placeholder="Add notes about your decision..."
                                rows="3"
                                :disabled="justification.status !== 'pending'"
                            />
                        </div>

                        <div class="flex gap-3">
                            <Button
                                @click="approve"
                                :disabled="form.processing"
                                class="bg-green-600 hover:bg-green-700"
                            >
                                <CheckCircle class="h-4 w-4 mr-2" />
                                {{ justification.status === 'approved' ? 'Already Approved' : 'Approve Justification' }}
                            </Button>
                            <Button
                                @click="reject"
                                :disabled="form.processing"
                                variant="destructive"
                            >
                                <XCircle class="h-4 w-4 mr-2" />
                                {{ justification.status === 'rejected' ? 'Already Rejected' : 'Reject Justification' }}
                            </Button>
                            <Button
                                variant="outline"
                                @click="router.visit('/admin/justifications')"
                            >
                                Back to List
                            </Button>
                        </div>                        <div v-if="justification.admin_notes && justification.status !== 'pending'" class="mt-4">
                            <p class="text-sm font-medium text-muted-foreground mb-2">Admin Notes</p>
                            <div class="p-4 bg-muted rounded-lg">
                                <p class="text-sm whitespace-pre-wrap">{{ justification.admin_notes }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
