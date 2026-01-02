<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Users, BookOpen, Plus, GraduationCap, Calendar, Pencil, Megaphone, FileText, FileDown } from 'lucide-vue-next';
import { create as createTeacher, index as teachersIndex } from '@/routes/admin/teachers';
import { create as createModule, index as modulesIndex, edit as modulesEdit } from '@/routes/admin/modules';
import { index as schedulesIndex } from '@/routes/admin/schedules';
import { index as studentsIndex } from '@/routes/admin/students';
import { index as attendanceListIndex } from '@/routes/admin/attendance-list';

interface Teacher {
    id: number;
    name: string;
    email: string;
    modules_count: number;
}

interface Module {
    id: number;
    name: string;
    code: string;
    year: number;
    teacher: {
        id: number;
        name: string;
    } | null;
}

interface Props {
    teachers: Teacher[];
    modules: Module[];
    stats: {
        total_teachers: number;
        total_modules: number;
        modules_by_year: Record<number, number>;
    };
}

defineProps<Props>();

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
        <Head title="Admin Dashboard" />

        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Header -->
                <div class="bg-card border rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">Admin Dashboard</h1>
                            <p class="text-muted-foreground">Manage teachers, modules, students and schedules</p>
                        </div>
                        <div class="flex gap-3 flex-wrap">
                            <Link :href="studentsIndex.url()">
                                <Button size="lg">
                                    <Users class="mr-2 h-5 w-5" />
                                    Manage Students
                                </Button>
                            </Link>
                            <Link :href="schedulesIndex.url()">
                                <Button size="lg" variant="outline">
                                    <Calendar class="mr-2 h-5 w-5" />
                                    Manage Schedules
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>

            <!-- Quick Actions -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card class="cursor-pointer hover:shadow-xl transition-all hover:scale-105 border-2" @click="router.visit('/admin/announcements/create')">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Megaphone class="h-5 w-5" />
                            <Plus class="h-4 w-4" />
                            Global Announcements
                        </CardTitle>
                        <CardDescription>Send announcements to all students and teachers</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">Click to create a new announcement</p>
                    </CardContent>
                </Card>

                <Card class="cursor-pointer hover:shadow-xl transition-all hover:scale-105 border-2" @click="router.visit('/admin/justifications')">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <FileText class="h-5 w-5" />
                            Justifications
                        </CardTitle>
                        <CardDescription>Review student absence justifications</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">Click to review pending justifications</p>
                    </CardContent>
                </Card>

                <Card class="cursor-pointer hover:shadow-xl transition-all hover:scale-105 border-2" @click="router.visit(attendanceListIndex.url())">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <FileDown class="h-5 w-5" />
                            Attendance Lists
                        </CardTitle>
                        <CardDescription>Generate printable attendance sheets</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">Create PDF attendance lists for any class</p>
                    </CardContent>
                </Card>

                <Card class="border-2 hover:shadow-xl transition-all hover:scale-105 cursor-pointer" @click="router.visit(studentsIndex.url())">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Users class="h-5 w-5" />
                            Student Management
                        </CardTitle>
                        <CardDescription>Manage student records and information</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Button class="w-full" size="lg">
                            Manage Students
                        </Button>
                    </CardContent>
                </Card>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-semibold">Total Teachers</CardTitle>
                        <Users class="h-5 w-5 text-primary" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats.total_teachers }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Recent teachers in the system</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-semibold">Total Modules</CardTitle>
                        <BookOpen class="h-5 w-5 text-primary" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats.total_modules }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Active modules</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-semibold">Year 1 Modules</CardTitle>
                        <GraduationCap class="h-5 w-5 text-primary" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats.modules_by_year[1] || 0 }}</div>
                        <p class="text-xs text-muted-foreground mt-1">First year courses</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-semibold">Year 5 Modules</CardTitle>
                        <GraduationCap class="h-5 w-5 text-primary" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats.modules_by_year[5] || 0 }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Fifth year courses</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Teachers Section -->
            <Card class="border-2">
                <CardHeader class="rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-2xl flex items-center gap-2">
                                <Users class="h-6 w-6" />
                                Teachers
                            </CardTitle>
                            <CardDescription>Recent teachers in the system</CardDescription>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="createTeacher.url()">
                                <Button>
                                    <Plus class="mr-2 h-4 w-4" />
                                    Add Teacher
                                </Button>
                            </Link>
                            <Link :href="teachersIndex.url()">
                                <Button variant="outline">View All</Button>
                            </Link>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="font-semibold">Name</TableHead>
                                <TableHead class="font-semibold">Email</TableHead>
                                <TableHead class="font-semibold">Modules</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="teacher in teachers" :key="teacher.id" class="hover:bg-muted/50 transition-colors">
                                <TableCell class="font-medium">{{ teacher.name }}</TableCell>
                                <TableCell>{{ teacher.email }}</TableCell>
                                <TableCell>
                                    <Badge>{{ teacher.modules_count }}</Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="teachers.length === 0">
                                <TableCell colspan="3" class="text-center py-8">
                                    No teachers found. Create your first teacher!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>

            <!-- Modules Section -->
            <Card class="border-2">
                <CardHeader class="rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-2xl flex items-center gap-2">
                                <BookOpen class="h-6 w-6" />
                                Modules
                            </CardTitle>
                            <CardDescription>Recent modules in the system</CardDescription>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="createModule.url()">
                                <Button>
                                    <Plus class="mr-2 h-4 w-4" />
                                    Add Module
                                </Button>
                            </Link>
                            <Link :href="modulesIndex.url()">
                                <Button variant="outline">View All</Button>
                            </Link>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="pt-6">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="font-semibold">Code</TableHead>
                                <TableHead class="font-semibold">Name</TableHead>
                                <TableHead class="font-semibold">Year</TableHead>
                                <TableHead class="font-semibold">Teacher</TableHead>
                                <TableHead class="text-right font-semibold">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="module in modules" :key="module.id" class="hover:bg-muted/50 transition-colors">
                                <TableCell class="font-mono font-medium">{{ module.code }}</TableCell>
                                <TableCell>{{ module.name }}</TableCell>
                                <TableCell>
                                    <Badge :class="getYearBadgeColor(module.year)">
                                        Year {{ module.year }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <span v-if="module.teacher">{{ module.teacher.name }}</span>
                                    <span v-else class="text-muted-foreground">Not assigned</span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Link :href="modulesEdit.url({ module: module.id })">
                                        <Button variant="ghost" size="sm">
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="modules.length === 0">
                                <TableCell colspan="5" class="text-center py-8">
                                    No modules found. Create your first module!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
            </div>
        </div>
    </AppLayout>
</template>
