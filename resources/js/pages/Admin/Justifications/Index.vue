<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { FileText } from 'lucide-vue-next'
import { ref } from 'vue'

interface Justification {
    id: number
    student_name: string
    student_email: string
    description: string
    status: string
    file_count: number
    created_at: string
    reviewed_at: string | null
    attendance: {
        date: string
        module: string
        module_code: string
    }
}

interface Props {
    justifications: {
        data: Justification[]
        links: any[]
        current_page: number
        last_page: number
    }
    currentStatus: string
}

const props = defineProps<Props>()

const statusFilter = ref(props.currentStatus)

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'default'
        case 'rejected': return 'destructive'
        case 'pending': return 'outline'
        default: return 'secondary'
    }
}

const filterByStatus = (status: string) => {
    router.visit(`/admin/justifications?status=${status}`, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <AppLayout>
        <Head title="Justifications Management" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Absence Justifications</CardTitle>
                                <CardDescription>Review and manage student absence justifications</CardDescription>
                            </div>
                            <div class="w-48">
                                <Select v-model="statusFilter" @update:model-value="filterByStatus">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Filter by status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">All Justifications</SelectItem>
                                        <SelectItem value="pending">Pending</SelectItem>
                                        <SelectItem value="approved">Approved</SelectItem>
                                        <SelectItem value="rejected">Rejected</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <Table v-if="justifications.data.length > 0">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Student</TableHead>
                                    <TableHead>Module</TableHead>
                                    <TableHead>Absence Date</TableHead>
                                    <TableHead>Submitted</TableHead>
                                    <TableHead>Files</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="justification in justifications.data" :key="justification.id">
                                    <TableCell>
                                        <div>
                                            <div class="font-medium">{{ justification.student_name }}</div>
                                            <div class="text-sm text-muted-foreground">{{ justification.student_email }}</div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <div>
                                            <div class="font-medium">{{ justification.attendance.module_code }}</div>
                                            <div class="text-sm text-muted-foreground">{{ justification.attendance.module }}</div>
                                        </div>
                                    </TableCell>
                                    <TableCell>{{ new Date(justification.attendance.date).toLocaleString() }}</TableCell>
                                    <TableCell>{{ new Date(justification.created_at).toLocaleDateString() }}</TableCell>
                                    <TableCell>{{ justification.file_count }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusColor(justification.status)">
                                            {{ justification.status.charAt(0).toUpperCase() + justification.status.slice(1) }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            @click="router.visit(`/admin/justifications/${justification.id}`)"
                                        >
                                            <FileText class="h-4 w-4 mr-1" />
                                            Review
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                        <div v-else class="py-8 text-center text-muted-foreground">
                            No justifications found
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
