<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { FileUp, FileText, Pencil } from 'lucide-vue-next'

interface Absence {
    id: number
    date: string
    module: string
    module_code: string
    has_justification: boolean
    justification_status: string | null
    justification_id: number | null
}

interface Props {
    absences: Absence[]
}

const props = defineProps<Props>()

const getStatusColor = (status: string | null) => {
    if (!status) return 'secondary'
    switch (status) {
        case 'approved': return 'default'
        case 'rejected': return 'destructive'
        case 'pending': return 'outline'
        default: return 'secondary'
    }
}

const getStatusLabel = (status: string | null) => {
    if (!status) return 'No Justification'
    return status.charAt(0).toUpperCase() + status.slice(1)
}
</script>

<template>
    <AppLayout>
        <Head title="My Justifications" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>My Absences & Justifications</CardTitle>
                        <CardDescription>Submit justifications for your absences (max 5 files per absence)</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table v-if="absences.length > 0">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Module</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="absence in absences" :key="absence.id">
                                    <TableCell>{{ new Date(absence.date).toLocaleString() }}</TableCell>
                                    <TableCell>
                                        <div>
                                            <div class="font-medium">{{ absence.module_code }}</div>
                                            <div class="text-sm text-muted-foreground">{{ absence.module }}</div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusColor(absence.justification_status)">
                                            {{ getStatusLabel(absence.justification_status) }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button
                                                v-if="!absence.has_justification"
                                                size="sm"
                                                @click="router.visit(`/student/justifications/create/${absence.id}`)"
                                            >
                                                <FileUp class="h-4 w-4 mr-1" />
                                                Submit Justification
                                            </Button>
                                            <Button
                                                v-if="absence.has_justification"
                                                size="sm"
                                                variant="outline"
                                                @click="router.visit(`/student/justifications/${absence.justification_id}`)"
                                            >
                                                <FileText class="h-4 w-4 mr-1" />
                                                View Details
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                        <div v-else class="py-8 text-center text-muted-foreground">
                            No absences found. Keep up the good attendance!
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
