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

const getYearBadgeVariant = (year: number) => {
    const variants: Record<number, string> = {
        1: 'default',
        2: 'secondary',
        3: 'outline',
        4: 'destructive',
        5: 'default'
    };
    return variants[year] || 'secondary';
};
</script>

<template>
    <AppLayout>
        <Head title="Admin Dashboard" />

        <div class="container mx-auto py-8 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Admin Dashboard</h1>
                    <p class="text-muted-foreground">Manage teachers, modules, students and schedules</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="studentsIndex.url()">
                        <Button size="lg" variant="outline">
                            <Users class="mr-2 h-5 w-5" />
                            Manage Students
                        </Button>
                    </Link>
                    <Link :href="schedulesIndex.url()">
                        <Button size="lg">
                            <Calendar class="mr-2 h-5 w-5" />
                            Manage Schedules
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 border-blue-200 dark:border-blue-800 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit('/admin/announcements/create')">
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

                <Card class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950 dark:to-purple-900 border-purple-200 dark:border-purple-800 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit('/admin/justifications')">
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

                <Card class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900 border-green-200 dark:border-green-800 cursor-pointer hover:shadow-lg transition-shadow" @click="router.visit(attendanceListIndex.url())">
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

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Users class="h-5 w-5" />
                            Student Management
                        </CardTitle>
                        <CardDescription>Manage student records and information</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="studentsIndex.url()">
                            <Button class="w-full" size="lg" variant="outline">
                                Manage Students
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <!-- Additional Quick Actions -->
            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Calendar class="h-5 w-5" />
                            Schedule Management
                        </CardTitle>
                        <CardDescription>Create and manage class schedules</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="schedulesIndex.url()">
                            <Button class="w-full" size="lg" variant="outline">
                                Manage Schedules
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Teachers</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_teachers }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Modules</CardTitle>
                        <BookOpen class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_modules }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Year 1 Modules</CardTitle>
                        <GraduationCap class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.modules_by_year[1] || 0 }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Year 5 Modules</CardTitle>
                        <GraduationCap class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.modules_by_year[5] || 0 }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Teachers Section -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Teachers</CardTitle>
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
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Modules</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="teacher in teachers" :key="teacher.id">
                                <TableCell class="font-medium">{{ teacher.name }}</TableCell>
                                <TableCell>{{ teacher.email }}</TableCell>
                                <TableCell>
                                    <Badge variant="secondary">{{ teacher.modules_count }}</Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="teachers.length === 0">
                                <TableCell colspan="3" class="text-center text-muted-foreground">
                                    No teachers found. Create your first teacher!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>

            <!-- Modules Section -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Modules</CardTitle>
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
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Code</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Year</TableHead>
                                <TableHead>Teacher</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="module in modules" :key="module.id">
                                <TableCell class="font-mono font-medium">{{ module.code }}</TableCell>
                                <TableCell>{{ module.name }}</TableCell>
                                <TableCell>
                                    <Badge :variant="getYearBadgeVariant(module.year)">
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
                                <TableCell colspan="5" class="text-center text-muted-foreground">
                                    No modules found. Create your first module!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
