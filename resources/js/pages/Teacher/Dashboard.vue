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

const getYearBadgeColor = (year: number): string => {
    const colors: Record<number, string> = {
        1: 'bg-blue-100 text-blue-800 border-blue-200',
        2: 'bg-green-100 text-green-800 border-green-200',
        3: 'bg-amber-100 text-amber-800 border-amber-200',
        4: 'bg-violet-100 text-violet-800 border-violet-200',
        5: 'bg-rose-100 text-rose-800 border-rose-200'
    };
    return colors[year] || 'bg-gray-100 text-gray-800 border-gray-200';
};
</script>

<template>
    <AppLayout>
        <Head title="Teacher Dashboard" />

        <div class="container mx-auto py-8">
            <!-- Welcome Section -->
            <div class="bg-card border rounded-2xl shadow-lg p-8 mb-8">
                <h1 class="text-4xl font-bold flex items-center gap-3 mb-2">
                    <User class="h-10 w-10" />
                    Welcome, {{ teacher.name }}
                </h1>
                <p class="text-muted-foreground text-lg">Manage your modules and schedules</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid gap-6 md:grid-cols-2 mb-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-semibold">My Modules</CardTitle>
                        <BookOpen class="h-5 w-5" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ modules.length }}</div>
                        <p class="text-xs text-muted-foreground mt-1">
                            Modules assigned to you
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-semibold">Scheduled Sessions</CardTitle>
                        <Calendar class="h-5 w-5" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ schedules.length }}</div>
                        <p class="text-xs text-muted-foreground mt-1">
                            Upcoming sessions
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-6 md:grid-cols-4 mb-8">
                <Card class="border-2 cursor-pointer hover:shadow-xl transition-all hover:scale-105" @click="router.visit('/teacher/announcements/create')">
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

                <Card class="border-2 cursor-pointer hover:shadow-xl transition-all hover:scale-105" @click="router.visit('/teacher/resources/create')">
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

                <Card class="border-2 cursor-pointer hover:shadow-xl transition-all hover:scale-105" @click="router.visit(attendanceListIndex.url())">
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

                <Card class="border-2 hover:shadow-xl transition-all hover:scale-105 cursor-pointer" @click="$inertia.visit(schedulesIndex.url())">
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
            <Card class="mb-8 border-2">
                <CardHeader class="rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-2xl flex items-center gap-2">
                                <BookOpen class="h-6 w-6" />
                                My Modules
                            </CardTitle>
                            <CardDescription>Modules you are teaching</CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="pt-6">
                    <div v-if="modules.length === 0" class="text-center py-8 text-muted-foreground">
                        No modules assigned yet
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="module in modules"
                            :key="module.id"
                            class="flex items-center justify-between p-4 border-2 rounded-lg hover:bg-muted/50 transition-all"
                        >
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <Badge :class="getYearBadgeColor(module.year)">
                                        Year {{ module.year }}
                                    </Badge>
                                    <span class="font-mono font-medium text-sm">{{ module.code }}</span>
                                    <Badge v-if="module.specialization" class="text-xs">
                                        {{ module.specialization }}
                                    </Badge>
                                    <Badge v-if="module.track" class="text-xs">
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
            <Card class="border-2">
                <CardHeader class="rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-2xl flex items-center gap-2">
                                <Calendar class="h-6 w-6" />
                                Recent Sessions
                            </CardTitle>
                            <CardDescription>Your upcoming teaching sessions</CardDescription>
                        </div>
                        <Link :href="schedulesIndex.url()">
                            <Button variant="outline" size="sm">
                                View All
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent class="pt-6">
                    <div v-if="schedules.length === 0" class="text-center py-8 text-muted-foreground">
                        No scheduled sessions yet. Go to schedules to add sessions.
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="schedule in schedules.slice(0, 10)"
                            :key="schedule.id"
                            class="flex items-center justify-between p-3 border-2 rounded hover:bg-muted/50 transition-all"
                        >
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <Badge class="text-xs">
                                        Year {{ schedule.year }} - Sem {{ schedule.semester }}
                                    </Badge>
                                    <Badge class="text-xs">
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
