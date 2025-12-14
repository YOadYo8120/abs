<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { FileText } from 'lucide-vue-next'

interface Student {
    id: number
    first_name: string
    last_name: string
    email: string
    year: number
    year_name: string
    specialization: string
    specialization_name: string
    track: string | null
    track_name: string | null
}

interface Statistics {
    total_sessions: number
    present: number
    absent: number
    late: number
    excused: number
    attendance_percentage: number
}

interface ModuleAttendance {
    module_name: string
    module_code: string
    total_sessions: number
    absent: number
    present: number
    late: number
    excused: number
    attendance_rate: number
}

interface RecentAttendance {
    id: number
    date: string
    status: string
    module_name: string
    module_code: string
    teacher_name: string
}

interface Announcement {
    id: number
    title: string
    content: string
    scope: string
    scope_label: string
    author: string
    published_at: string
}

interface Resource {
    id: number
    title: string
    description: string | null
    file_name: string
    file_size: number
    scope: string
    scope_label: string
    author: string
    published_at: string
}

interface Props {
    student: Student
    statistics: Statistics
    s1Statistics: Statistics
    s2Statistics: Statistics
    attendanceByModule: ModuleAttendance[]
    recentAttendances: RecentAttendance[]
    announcements: Announcement[]
    resources: Resource[]
}

const props = defineProps<Props>()

const getStatusColor = (status: string) => {
    switch (status) {
        case 'present':
            return 'bg-green-500'
        case 'absent':
            return 'bg-red-500'
        case 'late':
            return 'bg-yellow-500'
        case 'excused':
            return 'bg-blue-500'
        default:
            return 'bg-gray-500'
    }
}

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'present':
            return 'Present'
        case 'absent':
            return 'Absent'
        case 'late':
            return 'Late'
        case 'excused':
            return 'Excused'
        default:
            return status
    }
}
</script>

<template>
    <AppLayout>
        <Head title="Student Dashboard" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Student Info -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold">Welcome, {{ student.first_name }} {{ student.last_name }}</h1>
                    <p class="text-muted-foreground">
                        {{ student.year_name }} - {{ student.specialization_name }}
                        <span v-if="student.track_name"> ({{ student.track_name }})</span>
                    </p>
                </div>

                <!-- Statistics Cards -->
                <div class="mb-6 grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Total Sessions</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ statistics.total_sessions }}</div>
                            <p class="text-xs text-muted-foreground mt-1">All recorded sessions</p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Present</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-green-600">{{ statistics.present }}</div>
                            <p class="text-xs text-muted-foreground mt-1">Times attended</p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Absent</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-red-600">{{ statistics.absent }}</div>
                            <p class="text-xs text-muted-foreground mt-1">Times missed</p>
                        </CardContent>
                    </Card>

                    <Card v-if="statistics.late > 0">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Late</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-yellow-600">{{ statistics.late }}</div>
                            <p class="text-xs text-muted-foreground mt-1">Times late</p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Attendance Rate</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold" :class="{
                                'text-green-600': statistics.attendance_percentage >= 80,
                                'text-yellow-600': statistics.attendance_percentage >= 60 && statistics.attendance_percentage < 80,
                                'text-red-600': statistics.attendance_percentage < 60
                            }">
                                {{ statistics.attendance_percentage }}%
                            </div>
                            <p class="text-xs text-muted-foreground mt-1">Overall rate</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Quick Actions -->
                <div class="mb-6">
                    <Card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit('/student/justifications')">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <FileText class="h-5 w-5" />
                                My Justifications
                            </CardTitle>
                            <CardDescription>Submit justifications for your absences</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <p class="text-sm text-muted-foreground">Click to view and submit absence justifications</p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Semester Statistics -->
                <div class="mb-6 grid gap-4 md:grid-cols-2">
                    <!-- Semester 1 Statistics -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Semester 1 Statistics</CardTitle>
                            <CardDescription>Your attendance record for first semester</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Total Sessions:</span>
                                    <span class="text-2xl font-bold">{{ s1Statistics.total_sessions }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Present:</span>
                                    <span class="text-xl font-semibold text-green-600">{{ s1Statistics.present }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Absent:</span>
                                    <span class="text-xl font-semibold text-red-600">{{ s1Statistics.absent }}</span>
                                </div>
                                <div v-if="s1Statistics.late > 0" class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Late:</span>
                                    <span class="text-xl font-semibold text-yellow-600">{{ s1Statistics.late }}</span>
                                </div>
                                <div class="pt-3 border-t">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium">Attendance Rate:</span>
                                        <span class="text-2xl font-bold" :class="{
                                            'text-green-600': s1Statistics.attendance_percentage >= 80,
                                            'text-yellow-600': s1Statistics.attendance_percentage >= 60 && s1Statistics.attendance_percentage < 80,
                                            'text-red-600': s1Statistics.attendance_percentage < 60
                                        }">
                                            {{ s1Statistics.attendance_percentage }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Semester 2 Statistics -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Semester 2 Statistics</CardTitle>
                            <CardDescription>Your attendance record for second semester</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Total Sessions:</span>
                                    <span class="text-2xl font-bold">{{ s2Statistics.total_sessions }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Present:</span>
                                    <span class="text-xl font-semibold text-green-600">{{ s2Statistics.present }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Absent:</span>
                                    <span class="text-xl font-semibold text-red-600">{{ s2Statistics.absent }}</span>
                                </div>
                                <div v-if="s2Statistics.late > 0" class="flex justify-between items-center">
                                    <span class="text-sm font-medium">Late:</span>
                                    <span class="text-xl font-semibold text-yellow-600">{{ s2Statistics.late }}</span>
                                </div>
                                <div class="pt-3 border-t">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium">Attendance Rate:</span>
                                        <span class="text-2xl font-bold" :class="{
                                            'text-green-600': s2Statistics.attendance_percentage >= 80,
                                            'text-yellow-600': s2Statistics.attendance_percentage >= 60 && s2Statistics.attendance_percentage < 80,
                                            'text-red-600': s2Statistics.attendance_percentage < 60
                                        }">
                                            {{ s2Statistics.attendance_percentage }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Announcements -->
                <Card class="mb-6" v-if="announcements.length > 0">
                    <CardHeader>
                        <CardTitle>Announcements</CardTitle>
                        <CardDescription>Recent announcements from your teachers and administration</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="announcement in announcements" :key="announcement.id"
                                 class="p-4 border rounded-lg hover:bg-accent/50 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-lg">{{ announcement.title }}</h3>
                                    <Badge variant="outline">{{ announcement.scope_label }}</Badge>
                                </div>
                                <p class="text-muted-foreground mb-2">{{ announcement.content }}</p>
                                <div class="flex items-center justify-between text-sm text-muted-foreground">
                                    <span>By {{ announcement.author }}</span>
                                    <span>{{ new Date(announcement.published_at).toLocaleDateString() }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Resources -->
                <Card class="mb-6" v-if="resources.length > 0">
                    <CardHeader>
                        <CardTitle>Shared Resources</CardTitle>
                        <CardDescription>Files and materials shared by your teachers</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="resource in resources" :key="resource.id"
                                 class="p-4 border rounded-lg hover:bg-accent/50 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg">{{ resource.title }}</h3>
                                        <p v-if="resource.description" class="text-sm text-muted-foreground mt-1">
                                            {{ resource.description }}
                                        </p>
                                    </div>
                                    <Badge variant="outline">{{ resource.scope_label }}</Badge>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-muted-foreground mb-3">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        {{ resource.file_name }}
                                    </span>
                                    <span>{{ resource.file_size }} KB</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-muted-foreground">
                                        <span>By {{ resource.author }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ new Date(resource.published_at).toLocaleDateString() }}</span>
                                    </div>
                                    <a :href="`/student/resources/${resource.id}/download`"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Attendance by Module -->
                <Card class="mb-6">
                    <CardHeader>
                        <CardTitle>Attendance by Module</CardTitle>
                        <CardDescription>View your attendance statistics for each module</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table v-if="attendanceByModule.length > 0">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Module Code</TableHead>
                                    <TableHead>Module Name</TableHead>
                                    <TableHead class="text-center">Total Sessions</TableHead>
                                    <TableHead class="text-center">Present</TableHead>
                                    <TableHead class="text-center">Absent</TableHead>
                                    <TableHead class="text-center">Late</TableHead>
                                    <TableHead class="text-center">Excused</TableHead>
                                    <TableHead class="text-center">Attendance Rate</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="module in attendanceByModule" :key="module.module_code">
                                    <TableCell class="font-medium">{{ module.module_code }}</TableCell>
                                    <TableCell>{{ module.module_name }}</TableCell>
                                    <TableCell class="text-center">{{ module.total_sessions }}</TableCell>
                                    <TableCell class="text-center text-green-600">{{ module.present }}</TableCell>
                                    <TableCell class="text-center text-red-600">{{ module.absent }}</TableCell>
                                    <TableCell class="text-center text-yellow-600">{{ module.late }}</TableCell>
                                    <TableCell class="text-center text-blue-600">{{ module.excused }}</TableCell>
                                    <TableCell class="text-center">
                                        <Badge :class="{
                                            'bg-green-500': module.attendance_rate >= 80,
                                            'bg-yellow-500': module.attendance_rate >= 60 && module.attendance_rate < 80,
                                            'bg-red-500': module.attendance_rate < 60
                                        }">
                                            {{ module.attendance_rate }}%
                                        </Badge>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                        <div v-else class="py-8 text-center text-muted-foreground">
                            No attendance records found
                        </div>
                    </CardContent>
                </Card>

                <!-- Recent Attendance -->
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Attendance</CardTitle>
                        <CardDescription>Your most recent attendance records</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table v-if="recentAttendances.length > 0">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Module</TableHead>
                                    <TableHead>Teacher</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="attendance in recentAttendances" :key="attendance.id">
                                    <TableCell>{{ attendance.date }}</TableCell>
                                    <TableCell>
                                        <div class="font-medium">{{ attendance.module_code }}</div>
                                        <div class="text-sm text-muted-foreground">{{ attendance.module_name }}</div>
                                    </TableCell>
                                    <TableCell>{{ attendance.teacher_name }}</TableCell>
                                    <TableCell>
                                        <Badge :class="getStatusColor(attendance.status)">
                                            {{ getStatusLabel(attendance.status) }}
                                        </Badge>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                        <div v-else class="py-8 text-center text-muted-foreground">
                            No attendance records found
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
