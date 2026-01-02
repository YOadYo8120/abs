<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { create as studentsCreate, edit as studentsEdit, destroy as studentsDestroy, index as studentsIndex } from '@/routes/admin/students'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { GraduationCap, Mail, UserCircle, Building2, Route, BookOpen } from 'lucide-vue-next'

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
    const colors: Record<number, string> = {
        1: 'bg-blue-100 text-blue-800 hover:bg-blue-100',
        2: 'bg-green-100 text-green-800 hover:bg-green-100',
        3: 'bg-amber-100 text-amber-800 hover:bg-amber-100',
        4: 'bg-purple-100 text-purple-800 hover:bg-purple-100',
        5: 'bg-pink-100 text-pink-800 hover:bg-pink-100',
    }
    return colors[year] || 'bg-gray-100 text-gray-800 hover:bg-gray-100'
}

// Smart pagination logic
const paginationPages = computed(() => {
    const current = props.students.current_page
    const last = props.students.last_page
    const delta = 1 // Number of pages to show around current page
    const pages: (number | string)[] = []

    if (last <= 7) {
        // Show all pages if total pages <= 7
        for (let i = 1; i <= last; i++) {
            pages.push(i)
        }
    } else {
        // Always show first page
        pages.push(1)

        // Calculate range around current page
        const rangeStart = Math.max(2, current - delta)
        const rangeEnd = Math.min(last - 1, current + delta)

        // Add ellipsis after first page if needed
        if (rangeStart > 2) {
            pages.push('...')
        }

        // Add pages around current
        for (let i = rangeStart; i <= rangeEnd; i++) {
            pages.push(i)
        }

        // Add ellipsis before last page if needed
        if (rangeEnd < last - 1) {
            pages.push('...')
        }

        // Always show last page
        pages.push(last)
    }

    return pages
})
</script>

<template>
    <AppLayout>
        <Head title="Students" />

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Header Section -->
                <div class="bg-card border rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-primary/10 rounded-xl p-4">
                                <GraduationCap class="w-10 h-10 text-primary" />
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold mb-2">Student Registry</h1>
                                <p class="text-muted-foreground text-sm flex items-center gap-2">
                                    <UserCircle class="w-4 h-4" />
                                    Comprehensive student information management system
                                </p>
                            </div>
                        </div>
                        <Link :href="studentsCreate.url()">
                            <Button class="shadow-md">
                                <GraduationCap class="w-4 h-4 mr-2" />
                                Add New Student
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Main Content Card -->
                <Card class="shadow-xl border overflow-hidden">
                    <CardHeader class="border-b">
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle class="text-2xl font-bold flex items-center gap-2">
                                    <Building2 class="w-6 h-6 text-primary" />
                                    All Students
                                    <Badge class="ml-2 text-lg px-3 py-1">
                                        {{ students.total }}
                                    </Badge>
                                </CardTitle>
                                <CardDescription class="mt-2">
                                    Complete list of enrolled students with their academic details
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow class="hover:bg-transparent">
                                        <TableHead class="font-semibold">
                                            <div class="flex items-center gap-2">
                                                <UserCircle class="w-4 h-4" />
                                                Student Name
                                            </div>
                                        </TableHead>
                                        <TableHead class="font-semibold">
                                            <div class="flex items-center gap-2">
                                                <Mail class="w-4 h-4" />
                                                Email Address
                                            </div>
                                        </TableHead>
                                        <TableHead class="font-semibold">
                                            <div class="flex items-center gap-2">
                                                <BookOpen class="w-4 h-4" />
                                                Academic Year
                                            </div>
                                        </TableHead>
                                        <TableHead class="font-semibold">
                                            <div class="flex items-center gap-2">
                                                <Building2 class="w-4 h-4" />
                                                Specialization
                                            </div>
                                        </TableHead>
                                        <TableHead class="font-semibold">
                                            <div class="flex items-center gap-2">
                                                <Route class="w-4 h-4" />
                                                Track
                                            </div>
                                        </TableHead>
                                        <TableHead class="text-right font-semibold">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="student in students.data"
                                        :key="student.id"
                                        class="hover:bg-muted/50 transition-colors"
                                    >
                                        <TableCell class="font-medium">
                                            {{ student.user.name }}
                                        </TableCell>
                                        <TableCell>
                                            {{ student.user.email }}
                                        </TableCell>
                                        <TableCell>
                                            <Badge
                                                :class="getYearBadgeColor(student.year)"
                                                class="font-medium"
                                            >
                                                {{ student.year_name || `Year ${student.year}` }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell>
                                            <Badge
                                                v-if="student.specialization_name"
                                                class="bg-emerald-100 text-emerald-700 hover:bg-emerald-100 font-medium"
                                            >
                                                {{ student.specialization_name }}
                                            </Badge>
                                            <span v-else class="text-gray-400 text-sm italic">Not assigned</span>
                                        </TableCell>
                                        <TableCell>
                                            <Badge
                                                v-if="student.track_name"
                                                class="font-medium"
                                            >
                                                {{ student.track_name }}
                                            </Badge>
                                            <span v-else class="text-muted-foreground text-sm italic">Not assigned</span>
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <Link :href="studentsEdit.url({ student: student.id })">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                    >
                                                        Edit
                                                    </Button>
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
                                        <TableCell colspan="6" class="text-center py-12">
                                            <div class="flex flex-col items-center gap-3">
                                                <GraduationCap class="w-12 h-12 text-muted-foreground" />
                                                <p class="text-lg font-medium">No students found</p>
                                                <p class="text-sm text-muted-foreground">Click "Add New Student" to register a student</p>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <!-- Smart Pagination -->
                        <div v-if="students.last_page > 1" class="flex justify-center items-center gap-2 py-6 px-4 border-t">
                            <template v-for="(page, index) in paginationPages" :key="index">
                                <Button
                                    v-if="page === '...'"
                                    variant="ghost"
                                    size="sm"
                                    disabled
                                    class="cursor-default"
                                >
                                    {{ page }}
                                </Button>
                                <Link
                                    v-else
                                    :href="`/admin/students?page=${page}`"
                                    preserve-state
                                    preserve-scroll
                                >
                                    <Button
                                        :variant="page === students.current_page ? 'default' : 'outline'"
                                        :class="[
                                            page === students.current_page
                                                ? ''
                                                : ''
                                        ]"
                                        size="sm"
                                    >
                                        {{ page }}
                                    </Button>
                                </Link>
                            </template>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

