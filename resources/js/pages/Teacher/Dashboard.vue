<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { BookOpen, Calendar, User, ClipboardCheck, Megaphone, Upload, FileDown } from 'lucide-vue-next'
import { index as schedulesIndex } from '@/routes/teacher/schedules'
import { index as attendanceIndex } from '@/routes/teacher/attendance'
import { index as attendanceListIndex } from '@/routes/teacher/attendance-list'

interface Module {
    id: number
    name: string
    code: string
    year: number
    specialization: string | null
    track: string | null
}

interface Schedule {
    id: number
    year: number
    semester: number
    week_number: number
    day: string
    start_time: string
    end_time: string
    module: Module
    schedule_type: string | null
}

interface Teacher {
    id: number
    name: string
    email: string
}

defineProps<{
    modules: Module[]
    schedules: Schedule[]
    teacher: Teacher
}>()

const getYearBadgeVariant = (year: number) => {
    const variants = ['default', 'secondary', 'outline', 'default', 'secondary']
    return variants[year - 1] || 'default'
}
</script>

<template>
    <AppLayout>
        <Head title="Teacher Dashboard" />

        <div class="container mx-auto py-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold flex items-center gap-2">
                    <User class="h-8 w-8" />
                    Welcome, {{ teacher.name }}
                </h1>
                <p class="text-muted-foreground mt-2">Manage your modules and schedules</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid gap-6 md:grid-cols-2 mb-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">My Modules</CardTitle>
                        <BookOpen class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ modules.length }}</div>
                        <p class="text-xs text-muted-foreground">
                            Modules assigned to you
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Scheduled Sessions</CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ schedules.length }}</div>
                        <p class="text-xs text-muted-foreground">
                            Upcoming sessions
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-6 md:grid-cols-4 mb-8">
                <Card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit('/teacher/announcements/create')">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Megaphone class="h-5 w-5" />
                            Announcements
                        </CardTitle>
                        <CardDescription>Send announcements to your students</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">Click to create a new announcement</p>
                    </CardContent>
                </Card>

                <Card class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit('/teacher/resources/create')">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Upload class="h-5 w-5" />
                            Share Resources
                        </CardTitle>
                        <CardDescription>Upload files for your students</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">Click to upload a new file</p>
                    </CardContent>
                </Card>

                <Card class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950 dark:to-purple-900 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit(attendanceListIndex.url())">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <FileDown class="h-5 w-5" />
                            Attendance Lists
                        </CardTitle>
                        <CardDescription>Generate printable attendance sheets</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">Create PDF lists for your classes</p>
                    </CardContent>
                </Card>

                <Card class="cursor-pointer hover:bg-accent transition-colors" @click="$inertia.visit(schedulesIndex.url())">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Calendar class="h-5 w-5" />
                            Manage Schedules
                        </CardTitle>
                        <CardDescription>View and edit your teaching schedules</CardDescription>
                    </CardHeader>
                </Card>
            </div>

            <!-- My Modules Section -->
            <Card class="mb-8">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>My Modules</CardTitle>
                            <CardDescription>Modules you are teaching</CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div v-if="modules.length === 0" class="text-center py-8 text-muted-foreground">
                        No modules assigned yet
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="module in modules"
                            :key="module.id"
                            class="flex items-center justify-between p-4 border rounded-lg hover:bg-accent"
                        >
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <Badge :variant="getYearBadgeVariant(module.year)">
                                        Year {{ module.year }}
                                    </Badge>
                                    <span class="font-mono font-medium text-sm">{{ module.code }}</span>
                                    <Badge v-if="module.specialization" variant="outline" class="text-xs">
                                        {{ module.specialization }}
                                    </Badge>
                                    <Badge v-if="module.track" variant="secondary" class="text-xs">
                                        {{ module.track }}
                                    </Badge>
                                </div>
                                <h3 class="font-semibold mt-2">{{ module.name }}</h3>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Schedules Management -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Recent Sessions</CardTitle>
                            <CardDescription>Your upcoming teaching sessions</CardDescription>
                        </div>
                        <Link :href="schedulesIndex.url()">
                            <Button variant="outline" size="sm">
                                View All
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div v-if="schedules.length === 0" class="text-center py-8 text-muted-foreground">
                        No scheduled sessions yet. Go to schedules to add sessions.
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="schedule in schedules.slice(0, 10)"
                            :key="schedule.id"
                            class="flex items-center justify-between p-3 border rounded hover:bg-accent"
                        >
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <Badge variant="outline" class="text-xs">
                                        Year {{ schedule.year }} - Sem {{ schedule.semester }}
                                    </Badge>
                                    <Badge variant="secondary" class="text-xs">
                                        Week {{ schedule.week_number }}
                                    </Badge>
                                </div>
                                <div class="font-medium">{{ schedule.module.code }} - {{ schedule.module.name }}</div>
                                <div class="text-sm text-muted-foreground">
                                    {{ schedule.day }}, {{ schedule.start_time.substring(0, 5) }} - {{ schedule.end_time.substring(0, 5) }}
                                    <span v-if="schedule.schedule_type" class="ml-2">
                                        ({{ schedule.schedule_type }})
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-if="schedules.length > 10" class="text-center pt-4">
                            <Link :href="schedulesIndex.url()">
                                <Button variant="outline" size="sm">
                                    View All {{ schedules.length }} Sessions
                                </Button>
                            </Link>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
