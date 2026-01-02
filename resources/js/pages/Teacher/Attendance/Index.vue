<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Textarea } from '@/components/ui/textarea';

interface Student {
    id: number;
    code: string;
    first_name: string;
    last_name: string;
    email: string;
    year: number;
    specialization: string | null;
    track: string | null;
}

interface Schedule {
    id: number;
    year: number;
    semester: number;
    specialization: string | null;
    track: string | null;
    week_number: number;
    day: string;
    start_time: string;
    end_time: string;
    module: {
        id: number;
        name: string;
        code: string;
    };
    teacher: {
        id: number;
        name: string;
    };
}

interface Attendance {
    id: number;
    schedule_id: number;
    student_id: number;
    status: 'present' | 'absent' | 'late' | 'excused';
    notes: string | null;
}

const year = ref<number>(1);
const semester = ref<number>(1);
const specialization = ref<string>('');
const track = ref<string>('');
const weekNumber = ref<number>(1);
const selectedSchedule = ref<Schedule | null>(null);
const schedules = ref<Schedule[]>([]);
const students = ref<Student[]>([]);
const attendances = ref<Record<number, Attendance>>({});
const loading = ref(false);

const specializationOptions = ['GI', 'GC', 'GE', 'GM'];
const trackOptions = ['DEV', 'AI'];
const dayNames: Record<string, string> = {
    monday: 'Monday',
    tuesday: 'Tuesday',
    wednesday: 'Wednesday',
    thursday: 'Thursday',
    friday: 'Friday',
    saturday: 'Saturday',
};

const showSpecialization = computed(() => year.value >= 3);
const showTrack = computed(() => year.value >= 4 && specialization.value === 'GI');

// Watch year changes and reset specialization/track when needed
watch(year, (newYear) => {
    if (newYear < 3) {
        specialization.value = '';
        track.value = '';
    }
    if (newYear < 4) {
        track.value = '';
    }
    selectedSchedule.value = null;
    schedules.value = [];
    students.value = [];
    loadSchedules();
});

// Watch specialization changes and reset track if not GI
watch(specialization, (newSpec) => {
    if (newSpec !== 'GI') {
        track.value = '';
    }
    selectedSchedule.value = null;
    schedules.value = [];
    students.value = [];
});

// Load teacher's schedules
const loadSchedules = async () => {
    if (!year.value || !semester.value || !weekNumber.value) return;
    if (year.value >= 3 && !specialization.value) return;
    if (showTrack.value && !track.value) return;

    try {
        const response = await fetch(
            `/teacher/attendance/schedules?year=${year.value}&semester=${semester.value}&week_number=${weekNumber.value}&specialization=${specialization.value}&track=${track.value}`
        );
        const data = await response.json();
        schedules.value = data;

        if (schedules.value.length > 0 && !selectedSchedule.value) {
            selectSchedule(schedules.value[0]);
        }
    } catch (error) {
        console.error('Failed to load schedules:', error);
    }
};

// Select a schedule and load students
const selectSchedule = (schedule: Schedule) => {
    selectedSchedule.value = schedule;
    loadAttendance(schedule.id);
};

// Load students and attendance for selected schedule
const loadAttendance = async (scheduleId: number) => {
    loading.value = true;
    try {
        router.get('/teacher/attendance',
            { schedule_id: scheduleId },
            {
                preserveScroll: true,
                onSuccess: (page: any) => {
                    students.value = page.props.students;
                    attendances.value = page.props.attendances;
                },
            }
        );
    } catch (error) {
        console.error('Failed to load attendance:', error);
    } finally {
        loading.value = false;
    }
};

// Update student attendance
const updateAttendance = async (studentId: number, status: string) => {
    if (!selectedSchedule.value) return;

    try {
        const response = await fetch('/teacher/attendance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
            body: JSON.stringify({
                schedule_id: selectedSchedule.value.id,
                student_id: studentId,
                status: status,
            }),
        });

        const data = await response.json();

        if (data.success) {
            attendances.value[studentId] = data.attendance;
        }
    } catch (error) {
        console.error('Failed to update attendance:', error);
        alert('Failed to update attendance');
    }
};

// Get attendance status for a student
const getAttendanceStatus = (studentId: number): string => {
    return attendances.value[studentId]?.status || 'absent';
};

// Get status badge variant
const getStatusVariant = (status: string) => {
    switch (status) {
        case 'present':
            return 'default';
        case 'absent':
            return 'destructive';
        case 'late':
            return 'secondary';
        case 'excused':
            return 'outline';
        default:
            return 'outline';
    }
};

// Quick mark all as present
const markAllPresent = () => {
    students.value.forEach(student => {
        updateAttendance(student.id, 'present');
    });
};

// Quick mark all as absent
const markAllAbsent = () => {
    students.value.forEach(student => {
        updateAttendance(student.id, 'absent');
    });
};

onMounted(() => {
    loadSchedules();
});
</script>

<template>
    <Head title="Attendance Management" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Attendance Management</h1>
            </div>

            <!-- Filters -->
            <div class="bg-card p-6 rounded-lg shadow border space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Year -->
                    <div>
                        <Label>Year</Label>
                        <Select v-model="year">
                            <SelectTrigger>
                                <SelectValue placeholder="Select year" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem :value="1">1st Year</SelectItem>
                                <SelectItem :value="2">2nd Year</SelectItem>
                                <SelectItem :value="3">3rd Year</SelectItem>
                                <SelectItem :value="4">4th Year</SelectItem>
                                <SelectItem :value="5">5th Year</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Semester -->
                    <div>
                        <Label>Semester</Label>
                        <Select v-model="semester" @update:model-value="loadSchedules">
                            <SelectTrigger>
                                <SelectValue placeholder="Select semester" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem :value="1">Semester 1</SelectItem>
                                <SelectItem :value="2">Semester 2</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Specialization -->
                    <div v-if="showSpecialization">
                        <Label>Specialization</Label>
                        <Select v-model="specialization">
                            <SelectTrigger>
                                <SelectValue placeholder="Select specialization" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="spec in specializationOptions" :key="spec" :value="spec">
                                    {{ spec }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Track -->
                    <div v-if="showTrack">
                        <Label>Track</Label>
                        <Select v-model="track" @update:model-value="loadSchedules">
                            <SelectTrigger>
                                <SelectValue placeholder="Select track" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="t in trackOptions" :key="t" :value="t">
                                    {{ t }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Week -->
                    <div>
                        <Label>Week</Label>
                        <Select v-model="weekNumber" @update:model-value="loadSchedules">
                            <SelectTrigger>
                                <SelectValue placeholder="Select week" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="w in 20" :key="w" :value="w">
                                    Week {{ w }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!-- Schedule Selection -->
                <div v-if="schedules.length > 0">
                    <Label>Select Session</Label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-2">
                        <Button
                            v-for="schedule in schedules"
                            :key="schedule.id"
                            :variant="selectedSchedule?.id === schedule.id ? 'default' : 'outline'"
                            @click="selectSchedule(schedule)"
                            class="text-left flex flex-col items-start h-auto py-3"
                        >
                            <div class="font-semibold">{{ schedule.module.name }}</div>
                            <div class="text-xs opacity-75">
                                {{ dayNames[schedule.day] }} - {{ schedule.start_time }} to {{ schedule.end_time }}
                            </div>
                        </Button>
                    </div>
                </div>

                <div v-else class="text-center py-4 text-muted-foreground">
                    No sessions found for the selected filters.
                </div>
            </div>

            <!-- Attendance Table -->
            <div v-if="selectedSchedule && students.length > 0" class="bg-card p-6 rounded-lg shadow border">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">
                        {{ selectedSchedule.module.name }} - {{ dayNames[selectedSchedule.day] }}
                    </h2>
                    <div class="space-x-2">
                        <Button @click="markAllPresent" variant="outline" size="sm">
                            Mark All Present
                        </Button>
                        <Button @click="markAllAbsent" variant="outline" size="sm">
                            Mark All Absent
                        </Button>
                    </div>
                </div>

                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Student Code</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="student in students" :key="student.id">
                            <TableCell>{{ student.code }}</TableCell>
                            <TableCell>{{ student.first_name }} {{ student.last_name }}</TableCell>
                            <TableCell>
                                <Badge :variant="getStatusVariant(getAttendanceStatus(student.id))">
                                    {{ getAttendanceStatus(student.id) }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <div class="flex gap-2">
                                    <Button
                                        @click="updateAttendance(student.id, 'present')"
                                        size="sm"
                                        :variant="getAttendanceStatus(student.id) === 'present' ? 'default' : 'outline'"
                                    >
                                        Present
                                    </Button>
                                    <Button
                                        @click="updateAttendance(student.id, 'absent')"
                                        size="sm"
                                        :variant="getAttendanceStatus(student.id) === 'absent' ? 'destructive' : 'outline'"
                                    >
                                        Absent
                                    </Button>
                                    <Button
                                        @click="updateAttendance(student.id, 'late')"
                                        size="sm"
                                        :variant="getAttendanceStatus(student.id) === 'late' ? 'secondary' : 'outline'"
                                    >
                                        Late
                                    </Button>
                                    <Button
                                        @click="updateAttendance(student.id, 'excused')"
                                        size="sm"
                                        :variant="getAttendanceStatus(student.id) === 'excused' ? 'default' : 'outline'"
                                    >
                                        Excused
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
