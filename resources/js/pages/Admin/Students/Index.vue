<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { create as studentsCreate, edit as studentsEdit, destroy as studentsDestroy, index as studentsIndex } from '@/routes/admin/students'
import { router } from '@inertiajs/vue3'

interface User {
    id: number
    name: string
    email: string
}

interface Student {
    id: number
    year: number
    specialization: string | null
    track: string | null
    user: User
    year_name: string
    specialization_name: string | null
    track_name: string | null
}

interface PaginatedStudents {
    data: Student[]
    current_page: number
    last_page: number
    per_page: number
    total: number
}

const props = defineProps<{
    students: PaginatedStudents
}>()

const deleteStudent = (student: Student) => {
    if (!confirm(`Are you sure you want to delete ${student.user.name}? This will also delete their user account.`)) {
        return
    }

    router.delete(studentsDestroy.url({ student: student.id }), {
        preserveScroll: true,
    })
}

const getYearBadgeColor = (year: number) => {
    if (year <= 2) return 'secondary'
    return 'default'
}
</script>

<template>
    <AppLayout>
        <Head title="Students" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Students Management</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Manage student accounts and their academic information
                        </p>
                    </div>
                    <Link :href="studentsCreate.url()">
                        <Button>Add New Student</Button>
                    </Link>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>All Students ({{ students.total }})</CardTitle>
                        <CardDescription>List of all registered students</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Year</TableHead>
                                    <TableHead>Specialization</TableHead>
                                    <TableHead>Track</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="student in students.data" :key="student.id">
                                    <TableCell class="font-medium">{{ student.user.name }}</TableCell>
                                    <TableCell>{{ student.user.email }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="getYearBadgeColor(student.year)">
                                            {{ student.year_name }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <Badge v-if="student.specialization_name" variant="outline">
                                            {{ student.specialization_name }}
                                        </Badge>
                                        <span v-else class="text-gray-400 text-sm">N/A</span>
                                    </TableCell>
                                    <TableCell>
                                        <Badge v-if="student.track_name" variant="outline">
                                            {{ student.track_name }}
                                        </Badge>
                                        <span v-else class="text-gray-400 text-sm">N/A</span>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="studentsEdit.url({ student: student.id })">
                                                <Button variant="outline" size="sm">Edit</Button>
                                            </Link>
                                            <Button
                                                @click="deleteStudent(student)"
                                                variant="destructive"
                                                size="sm"
                                            >
                                                Delete
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="students.data.length === 0">
                                    <TableCell colspan="6" class="text-center text-gray-500 py-8">
                                        No students found. Click "Add New Student" to create one.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>

                        <!-- Pagination -->
                        <div v-if="students.last_page > 1" class="flex justify-center gap-2 mt-4">
                            <Button
                                v-for="page in students.last_page"
                                :key="page"
                                :variant="page === students.current_page ? 'default' : 'outline'"
                                size="sm"
                                @click="router.get(studentsIndex.url({ page }))"
                            >
                                {{ page }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

