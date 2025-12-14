<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Checkbox } from '@/components/ui/checkbox'
import { index as schedulesIndex, update as schedulesUpdate, destroy as schedulesDestroy } from '@/routes/teacher/schedules'

interface Module {
    id: number
    name: string
    code: string
    year: number
    teacher_id?: number
}

interface Teacher {
    id: number
    name: string
}

interface Schedule {
    id: number
    year: number
    semester: number
    week_number: number
    day: string
    start_time: string
    end_time: string
    module_id: number | null
    module?: Module
    schedule_type?: 'course' | 'TD' | 'TP' | 'exam' | null
    teacher_id?: number | null
    teacher?: Teacher
}

interface Student {
    id: number
    code: string
    first_name: string
    last_name: string
    email: string
    year: number
    specialization: string | null
    track: string | null
}

interface Attendance {
    id: number
    schedule_id: number
    student_id: number
    status: 'present' | 'absent' | 'late' | 'excused'
    notes: string | null
}

interface TimeSlot {
    start: string
    end: string
}

const props = defineProps<{
    schedules: Schedule[]
    modules: Module[]
    teachers: Teacher[]
    timeSlots: TimeSlot[]
    days: string[]
    currentYear: number
    currentSemester: number
    currentWeek: number
    templateExists: boolean
    replicatedWeeks: number[]
    currentSpecialization?: string | null
    currentTrack?: string | null
    teacherId: number
    weekDates?: { start: string; end: string } | null
}>()

const currentTeacherId = props.teacherId
const selectedYear = ref(props.currentYear)
const selectedSemester = ref(props.currentSemester)
// Teachers should only see replicated weeks, default to week 1
const selectedWeek = ref(props.currentWeek === 0 ? (props.replicatedWeeks.length > 0 ? props.replicatedWeeks[0] : 1) : props.currentWeek)
const selectedSpecialization = ref<string | null>(props.currentSpecialization || null)
const selectedTrack = ref<string | null>(props.currentTrack || null)

// Specializations for years 3-5
const specializations = [
    { value: 'GI', label: 'Génie Informatique' },
    { value: 'GRT', label: 'Génie des Réseaux et Télécommunications' },
    { value: 'GInd', label: 'Génie Industriel' },
    { value: 'GM', label: 'Génie Mécanique' },
    { value: 'GA', label: 'Génie de l\'Eau et de l\'Environnement' },
    { value: 'GPM', label: 'Génie des Procédés et Management de la Qualité' },
]

// Tracks for GI specialization (years 4-5)
const tracks = [
    { value: 'DEV', label: 'Development' },
    { value: 'AI', label: 'Artificial Intelligence' },
]

// Show specialization selector for years 3-5
const showSpecializationSelector = computed(() => selectedYear.value >= 3)

// Show track selector for GI specialization in years 4-5
const showTrackSelector = computed(() =>
    selectedYear.value >= 4 &&
    selectedYear.value <= 5 &&
    selectedSpecialization.value === 'GI'
)

// Check if user can edit schedules (years 3-5 require specialization, GI years 4-5 also require track)
const canEditSchedule = computed(() => {
    if (selectedYear.value >= 3) {
        if (!selectedSpecialization.value) {
            return false
        }
        // GI specialization in years 4-5 also requires track
        if (showTrackSelector.value && !selectedTrack.value) {
            return false
        }
        return true
    }
    return true // Years 1-2 can always edit
})

// Check if teacher can edit a specific schedule cell
const canEditScheduleCell = (schedule: Schedule | null) => {
    if (!canEditSchedule.value) return false

    if (schedule) {
        // For existing schedules, teacher can edit if:
        // 1. They own the module (schedule.module.teacher_id === currentTeacherId)
        // 2. They are assigned as teacher (schedule.teacher_id === currentTeacherId)
        const ownsModule = schedule.module && (schedule.module as any).teacher_id === currentTeacherId
        const assignedToTeacher = schedule.teacher_id === currentTeacherId

        return ownsModule || assignedToTeacher
    }

    // Can add to empty cells (will only show their own modules)
    return true
}

// Editing state for inline form
const editingCell = ref<{ day: string; time: string } | null>(null)
const editForm = ref({
    moduleId: null as number | null,
    scheduleType: null as 'course' | 'TD' | 'TP' | 'exam' | null,
    teacherId: null as number | null,
})

// Attendance dialog state
const showAttendanceDialog = ref(false)
const attendanceSchedule = ref<Schedule | null>(null)
const attendanceStudents = ref<Student[]>([])
const attendanceRecords = ref<Record<number, boolean>>({}) // student_id => is_absent
const loadingStudents = ref(false)

// Schedule actions menu dialog state
const showScheduleActionsDialog = ref(false)
const selectedScheduleForActions = ref<Schedule | null>(null)

// Debug: Log props
console.log('Props received:', {
    modules: props.modules,
    days: props.days,
    currentYear: props.currentYear,
    currentSemester: props.currentSemester
})

// Watch for year/semester/week/specialization/track changes and auto-reload
watch([selectedYear, selectedSemester, selectedWeek, selectedSpecialization, selectedTrack], ([newYear, newSemester, newWeek, newSpec, newTrack], [oldYear, oldSemester, oldWeek, oldSpec, oldTrack]) => {
    // Reset specialization when switching to years 1-2
    if (newYear <= 2 && newSpec) {
        selectedSpecialization.value = null
        selectedTrack.value = null
        return
    }

    // Reset track when not GI or not years 4-5
    if ((!showTrackSelector.value) && newTrack) {
        selectedTrack.value = null
        return
    }

    // Only reload if values actually changed (not initial load)
    if (newYear !== oldYear || newSemester !== oldSemester || newWeek !== oldWeek || newSpec !== oldSpec || newTrack !== oldTrack) {
        console.log('Filter changed, reloading:', { newYear, newSemester, newWeek, newSpec, newTrack })
        updateFilter()
    }
})

// Find schedule for specific day and time
const findSchedule = (day: string, timeSlot: TimeSlot): Schedule | undefined => {
    return props.schedules.find(
        s => s.day === day && s.start_time === timeSlot.start && s.end_time === timeSlot.end
    )
}

// Calculate actual date for a schedule (using academic calendar)
const getScheduleDate = (day: string): Date | null => {
    // S1 starts 2025-09-22 (Monday), S2 starts 2026-02-02 (Monday)
    const semester1Start = new Date('2025-09-22')
    const semester2Start = new Date('2026-02-02')

    const startDate = selectedSemester.value === 1 ? semester1Start : semester2Start

    // Calculate week offset (selectedWeek - 1) because week 1 = 0 offset
    const weekOffset = selectedWeek.value - 1

    // Calculate day offset (Monday = 0, Tuesday = 1, etc.)
    const dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    const dayOffset = dayNames.indexOf(day)

    if (dayOffset === -1) return null

    // Calculate total days to add
    const totalDays = (weekOffset * 7) + dayOffset

    const scheduleDate = new Date(startDate)
    scheduleDate.setDate(scheduleDate.getDate() + totalDays)

    return scheduleDate
}

// Check if schedule is in the future (can't mark attendance for future dates)
const isScheduleInFuture = (day: string): boolean => {
    const scheduleDate = getScheduleDate(day)
    if (!scheduleDate) return true

    const today = new Date()
    today.setHours(0, 0, 0, 0)
    scheduleDate.setHours(0, 0, 0, 0)

    return scheduleDate > today
}


// Update filter
const updateFilter = () => {
    console.log('Updating filter with:', {
        year: selectedYear.value,
        semester: selectedSemester.value,
        week: selectedWeek.value,
        specialization: selectedSpecialization.value,
        track: selectedTrack.value
    })

    const params: Record<string, any> = {
        year: selectedYear.value,
        semester: selectedSemester.value,
        week: selectedWeek.value,
    }

    // Add specialization for years 3-5
    if (selectedYear.value >= 3 && selectedSpecialization.value) {
        params.specialization = selectedSpecialization.value
    }

    // Add track for GI years 4-5
    if (showTrackSelector.value && selectedTrack.value) {
        params.track = selectedTrack.value
    }

    router.get(schedulesIndex.url(), params, {
        preserveState: false, // Force reload to get new modules for the year
        preserveScroll: true,
    })
}

// Assign module to time slot
const assignModule = (day: string, timeSlot: TimeSlot) => {
    if (!editForm.value.moduleId) return

    console.log('Assigning module:', { day, timeSlot, editForm: editForm.value })

    const formData: Record<string, any> = {
        year: selectedYear.value,
        semester: selectedSemester.value,
        week_number: selectedWeek.value,
        day,
        start_time: timeSlot.start,
        end_time: timeSlot.end,
        module_id: editForm.value.moduleId,
        schedule_type: editForm.value.scheduleType,
        teacher_id: editForm.value.teacherId,
    }

    // Add specialization for years 3-5
    if (selectedYear.value >= 3 && selectedSpecialization.value) {
        formData.specialization = selectedSpecialization.value
    }

    // Add track for GI years 4-5
    if (showTrackSelector.value && selectedTrack.value) {
        formData.track = selectedTrack.value
    }

    router.post(schedulesUpdate.url(), formData, {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
            editingCell.value = null
            editForm.value = {
                moduleId: null,
                scheduleType: 'course',
                teacherId: null,
            }
        },
        onError: (errors) => {
            console.error('Schedule update errors:', errors)
            alert('Failed to update schedule: ' + JSON.stringify(errors))
        }
    })
}

// Start editing a cell
const startEditing = (day: string, timeSlot: TimeSlot) => {
    const schedule = findSchedule(day, timeSlot)

    // Check if teacher can edit this cell
    if (!canEditScheduleCell(schedule)) {
        return
    }

    editingCell.value = { day, time: timeSlot.start }

    if (schedule) {
        editForm.value = {
            moduleId: schedule.module_id,
            scheduleType: schedule.schedule_type || null,
            teacherId: schedule.teacher_id || null,
        }
    } else {
        editForm.value = {
            moduleId: null,
            scheduleType: 'course',
            teacherId: null,
        }
    }
}

// Watch for module selection to auto-select teacher and set default type
watch(() => editForm.value.moduleId, (newModuleId) => {
    if (newModuleId) {
        const selectedModule = props.modules.find(m => m.id === newModuleId)
        if (selectedModule && (selectedModule as any).teacher_id) {
            editForm.value.teacherId = (selectedModule as any).teacher_id
        }
        // Set default schedule type to 'course' if not already set
        if (!editForm.value.scheduleType) {
            editForm.value.scheduleType = 'course'
        }
    }
})

// Cancel editing
const cancelEditing = () => {
    editingCell.value = null
    editForm.value = {
        moduleId: null,
        scheduleType: null,
        teacherId: null,
    }
}

// Check if cell is being edited
const isEditing = (day: string, timeSlot: TimeSlot) => {
    return editingCell.value?.day === day && editingCell.value?.time === timeSlot.start
}

// Remove module from time slot
const removeSchedule = (day: string, timeSlot: TimeSlot) => {
    const schedule = findSchedule(day, timeSlot)

    // Check if teacher can delete this schedule
    if (!canEditScheduleCell(schedule)) {
        alert('You can only delete your own schedules.')
        return
    }

    if (!confirm('Are you sure you want to remove this module from the schedule?')) {
        return
    }

    const formData: Record<string, any> = {
        year: selectedYear.value,
        semester: selectedSemester.value,
        week_number: selectedWeek.value,
        day,
        start_time: timeSlot.start,
        end_time: timeSlot.end,
    }

    // Add specialization for years 3-5
    if (selectedYear.value >= 3 && selectedSpecialization.value) {
        formData.specialization = selectedSpecialization.value
    }

    // Add track for GI years 4-5
    if (showTrackSelector.value && selectedTrack.value) {
        formData.track = selectedTrack.value
    }

    router.delete(schedulesDestroy.url(), {
        data: formData,
        preserveState: false, // Force reload to get fresh data
        preserveScroll: true,
        onError: (errors) => {
            console.error('Schedule delete errors:', errors)
            alert('Failed to delete schedule: ' + JSON.stringify(errors))
        }
    })
}

// Open schedule actions menu
const openScheduleActionsMenu = (schedule: Schedule) => {
    selectedScheduleForActions.value = schedule
    showScheduleActionsDialog.value = true
}

// Close schedule actions menu
const closeScheduleActionsMenu = () => {
    showScheduleActionsDialog.value = false
    selectedScheduleForActions.value = null
}

// Handle action selection from menu
const handleScheduleAction = (action: 'attendance' | 'edit' | 'delete') => {
    const schedule = selectedScheduleForActions.value
    if (!schedule) return

    closeScheduleActionsMenu()

    if (action === 'attendance') {
        openAttendanceDialog(schedule)
    } else if (action === 'edit') {
        const timeSlot = props.timeSlots.find(ts => ts.start === schedule.start_time && ts.end === schedule.end_time)
        if (timeSlot) {
            startEditing(schedule.day, timeSlot)
        }
    } else if (action === 'delete') {
        const timeSlot = { start: schedule.start_time, end: schedule.end_time }
        removeSchedule(schedule.day, timeSlot)
    }
}

// Open attendance dialog for a schedule
const openAttendanceDialog = async (schedule: Schedule) => {
    // Check if teacher is assigned to this schedule
    if (schedule.teacher_id !== currentTeacherId) {
        alert('You can only mark attendance for sessions you are teaching.')
        return
    }

    // Check if schedule is in the future
    if (isScheduleInFuture(schedule.day)) {
        const scheduleDate = getScheduleDate(schedule.day)
        const dateStr = scheduleDate?.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        })
        alert(`You cannot mark attendance for future classes.\nThis class is scheduled for: ${dateStr}\n\nYou can only mark attendance for today or past dates.`)
        return
    }

    attendanceSchedule.value = schedule
    showAttendanceDialog.value = true
    loadingStudents.value = true
    attendanceRecords.value = {}

    try {
        // Fetch students and existing attendance
        const response = await fetch(`/teacher/attendance?schedule_id=${schedule.id}`, {
            headers: {
                'Accept': 'application/json',
            }
        })
        const data = await response.json()

        if (data.props) {
            attendanceStudents.value = data.props.students || []
            const attendances = data.props.attendances || {}

            // Initialize attendance records (checked = absent, unchecked = present)
            attendanceStudents.value.forEach(student => {
                const attendance = attendances[student.id]
                // If attendance exists and status is 'absent', check the box
                attendanceRecords.value[student.id] = !!(attendance && attendance.status === 'absent')
            })
        }
    } catch (error) {
        console.error('Failed to load students:', error)
        alert('Failed to load students')
    } finally {
        loadingStudents.value = false
    }
}

// Save attendance for a student
const toggleStudentAttendance = async (studentId: number) => {
    if (!attendanceSchedule.value) return

    const isAbsent = attendanceRecords.value[studentId]
    const status = isAbsent ? 'absent' : 'present'

    try {
        // Use Inertia router for better CSRF handling
        router.post('/teacher/attendance', {
            schedule_id: attendanceSchedule.value.id,
            student_id: studentId,
            status: status,
        }, {
            preserveState: true,
            preserveScroll: true,
            onError: (errors) => {
                console.error('Failed to update attendance:', errors)
                // Revert the checkbox on error
                attendanceRecords.value[studentId] = !isAbsent

                // Show user-friendly error message
                if (errors.message) {
                    alert(errors.message)
                } else {
                    alert('Failed to update attendance. Please try again.')
                }
            },
            onSuccess: () => {
                console.log('Attendance updated successfully')
            },
        })
    } catch (error) {
        console.error('Error updating attendance:', error)
        // Revert the checkbox on error
        attendanceRecords.value[studentId] = !isAbsent
    }
}// Close attendance dialog
const closeAttendanceDialog = () => {
    showAttendanceDialog.value = false
    attendanceSchedule.value = null
    attendanceStudents.value = []
    attendanceRecords.value = {}
}

// Computed properties
const isTemplateWeek = computed(() => selectedWeek.value === 0)

// Format date for display (DD/MM/YYYY)
const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()
    return `${day}/${month}/${year}`
}

const weekTitle = computed(() => {
    if (isTemplateWeek.value) {
        return 'Template Week'
    }

    // Add date range if available
    if (props.weekDates && props.weekDates.start && props.weekDates.end) {
        return `Week ${selectedWeek.value}: ${formatDate(props.weekDates.start)} - ${formatDate(props.weekDates.end)}`
    }

    return `Week ${selectedWeek.value}`
})
</script>

<template>
    <AppLayout>
        <Head title="Schedules" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">My Schedule</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            View all schedules and manage your own teaching hours
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <Card>
                    <CardHeader>
                        <CardTitle>Filters</CardTitle>
                        <CardDescription>Select year, semester, and week</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex gap-4 items-end flex-wrap">
                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-sm font-medium mb-2">Year</label>
                                <Select v-model="selectedYear">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select year" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem :value="1">Year 1</SelectItem>
                                        <SelectItem :value="2">Year 2</SelectItem>
                                        <SelectItem :value="3">Year 3</SelectItem>
                                        <SelectItem :value="4">Year 4</SelectItem>
                                        <SelectItem :value="5">Year 5</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="flex-1 min-w-[150px]">
                                <label class="block text-sm font-medium mb-2">Semester</label>
                                <Select v-model="selectedSemester">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select semester" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem :value="1">Semester 1</SelectItem>
                                        <SelectItem :value="2">Semester 2</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div v-if="showSpecializationSelector" class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium mb-2">Specialization</label>
                                <Select v-model="selectedSpecialization">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select specialization" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="spec in specializations"
                                            :key="spec.value"
                                            :value="spec.value"
                                        >
                                            {{ spec.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div v-if="showTrackSelector" class="flex-1 min-w-[150px]">
                                <label class="block text-sm font-medium mb-2">Track</label>
                                <Select v-model="selectedTrack">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select track" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="track in tracks"
                                            :key="track.value"
                                            :value="track.value"
                                        >
                                            {{ track.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium mb-2">Week</label>
                                <Select v-model="selectedWeek">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select week" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="week in replicatedWeeks"
                                            :key="week"
                                            :value="week"
                                        >
                                            Week {{ week }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Changes automatically update when you select a different option</p>
                        <Alert v-if="showSpecializationSelector && !selectedSpecialization" class="mt-4">
                            <AlertDescription>
                                Please select a specialization to view schedules for Year {{ selectedYear }}.
                            </AlertDescription>
                        </Alert>
                        <Alert v-if="showTrackSelector && !selectedTrack" class="mt-4">
                            <AlertDescription>
                                Please select a track (DEV or AI) to view schedules for GI Year {{ selectedYear }}.
                            </AlertDescription>
                        </Alert>
                    </CardContent>
                </Card>

                <!-- No schedules alert -->
                <Alert v-if="replicatedWeeks.length === 0">
                    <AlertDescription>
                        No schedules available yet. The admin needs to create and replicate the template first.
                    </AlertDescription>
                </Alert>

                <!-- Schedule Grid -->
                <Card v-if="replicatedWeeks.length > 0">
                    <CardHeader>
                        <CardTitle>
                            Year {{ selectedYear }} - Semester {{ selectedSemester }} - {{ weekTitle }}
                        </CardTitle>
                        <CardDescription>
                            View all schedules. You can only edit your own teaching hours (shown in blue).
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <!-- Warning when specialization is required but not selected -->
                        <Alert v-if="!canEditSchedule" class="mb-4" variant="destructive">
                            <AlertDescription>
                                ⚠️ Please select a specialization above to view and edit schedules for Year {{ selectedYear }}.
                            </AlertDescription>
                        </Alert>

                        <div v-if="canEditSchedule" class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr>
                                        <th class="border p-2 bg-gray-100 text-sm font-medium sticky left-0 z-10 text-gray-900">Time</th>
                                        <th
                                            v-for="day in days"
                                            :key="day"
                                            class="border p-2 bg-gray-100 text-sm font-medium text-gray-900"
                                        >
                                            {{ day }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="timeSlot in timeSlots" :key="timeSlot.start">
                                        <td class="border p-2 bg-gray-100 text-xs text-center whitespace-nowrap sticky left-0 z-10 text-gray-900 font-medium">
                                            {{ timeSlot.start.substring(0, 5) }} - {{ timeSlot.end.substring(0, 5) }}
                                        </td>
                                        <td
                                            v-for="day in days"
                                            :key="`${day}-${timeSlot.start}`"
                                            class="border p-1 relative group hover:bg-gray-50 min-w-[200px]"
                                        >
                                            <!-- Display mode: Show module + type + teacher -->
                                            <div
                                                v-if="!isEditing(day, timeSlot) && findSchedule(day, timeSlot)?.module"
                                                :class="[
                                                    'p-2 rounded text-xs',
                                                    isScheduleInFuture(day) && findSchedule(day, timeSlot)?.teacher_id === currentTeacherId
                                                        ? 'bg-gray-200 text-gray-500 cursor-not-allowed opacity-60'
                                                        : canEditScheduleCell(findSchedule(day, timeSlot))
                                                        ? 'bg-blue-100 text-blue-900 cursor-pointer hover:bg-blue-200'
                                                        : 'bg-gray-100 text-gray-700 cursor-default'
                                                ]"
                                                @click="
                                                    findSchedule(day, timeSlot)?.teacher_id === currentTeacherId
                                                        ? openScheduleActionsMenu(findSchedule(day, timeSlot)!)
                                                        : startEditing(day, timeSlot)
                                                "
                                            >
                                                <div class="font-semibold">
                                                    {{ findSchedule(day, timeSlot)?.module?.code }}
                                                    <Badge v-if="findSchedule(day, timeSlot)?.schedule_type" variant="secondary" class="ml-1">
                                                        {{ findSchedule(day, timeSlot)?.schedule_type }}
                                                    </Badge>
                                                    <Badge v-if="isScheduleInFuture(day) && findSchedule(day, timeSlot)?.teacher_id === currentTeacherId" variant="outline" class="ml-1 text-[10px]">
                                                        🔒 Future
                                                    </Badge>
                                                </div>
                                                <div class="truncate text-[11px]">
                                                    {{ findSchedule(day, timeSlot)?.module?.name }}
                                                </div>
                                                <div v-if="findSchedule(day, timeSlot)?.teacher" class="mt-1 text-[10px] text-blue-700 font-medium">
                                                    👤 {{ findSchedule(day, timeSlot)?.teacher?.name }}
                                                </div>
                                                <div v-if="findSchedule(day, timeSlot)?.teacher_id === currentTeacherId && !isScheduleInFuture(day)" class="mt-1 text-[10px] text-green-700 font-medium">
                                                    📋 Click for options
                                                </div>
                                                <div v-if="findSchedule(day, timeSlot)?.teacher_id === currentTeacherId && isScheduleInFuture(day)" class="mt-1 text-[10px] text-gray-500 font-medium">
                                                    🔒 Click for options (attendance locked)
                                                </div>
                                            </div>

                                            <!-- Empty cell: Show quick assign button if can edit -->
                                            <div
                                                v-else-if="!isEditing(day, timeSlot) && canEditScheduleCell(null)"
                                                class="p-2 text-center"
                                            >
                                                <Button
                                                    @click="startEditing(day, timeSlot)"
                                                    variant="ghost"
                                                    size="sm"
                                                    class="w-full text-xs opacity-0 group-hover:opacity-100"
                                                >
                                                    + Assign
                                                </Button>
                                            </div>

                                            <!-- Edit mode: Show form -->
                                            <div v-else class="p-2 space-y-2 bg-gray-50 rounded">
                                                <!-- Module Selection -->
                                                <select
                                                    v-model="editForm.moduleId"
                                                    class="w-full h-8 text-xs border border-gray-300 rounded px-2 bg-white text-gray-900"
                                                >
                                                    <option :value="null">Select module</option>
                                                    <option
                                                        v-for="module in modules"
                                                        :key="module.id"
                                                        :value="module.id"
                                                    >
                                                        {{ module.code }} - {{ module.name }}
                                                    </option>
                                                </select>

                                                <!-- Schedule Type Selection -->
                                                <select
                                                    v-model="editForm.scheduleType"
                                                    class="w-full h-8 text-xs border border-gray-300 rounded px-2 bg-white text-gray-900"
                                                    :disabled="!editForm.moduleId"
                                                >
                                                    <option :value="null">Type (optional)</option>
                                                    <option value="course">Course</option>
                                                    <option value="TD">TD</option>
                                                    <option value="TP">TP</option>
                                                    <option value="exam">Exam</option>
                                                </select>

                                                <!-- Teacher Selection -->
                                                <select
                                                    v-model="editForm.teacherId"
                                                    class="w-full h-8 text-xs border border-gray-300 rounded px-2 bg-white text-gray-900"
                                                    :disabled="!editForm.moduleId"
                                                >
                                                    <option :value="null">Teacher (optional)</option>
                                                    <option
                                                        v-for="teacher in teachers"
                                                        :key="teacher.id"
                                                        :value="teacher.id"
                                                    >
                                                        {{ teacher.name }}
                                                    </option>
                                                </select>

                                                <!-- Action Buttons -->
                                                <div class="flex gap-1">
                                                    <Button
                                                        @click="assignModule(day, timeSlot)"
                                                        variant="default"
                                                        size="sm"
                                                        class="flex-1 text-xs h-7"
                                                        :disabled="!editForm.moduleId"
                                                    >
                                                        {{ findSchedule(day, timeSlot) ? 'Update' : 'Save' }}
                                                    </Button>
                                                    <Button
                                                        v-if="findSchedule(day, timeSlot)?.module"
                                                        @click="removeSchedule(day, timeSlot)"
                                                        variant="destructive"
                                                        size="sm"
                                                        class="flex-1 text-xs h-7"
                                                    >
                                                        Delete
                                                    </Button>
                                                    <Button
                                                        @click="cancelEditing()"
                                                        variant="ghost"
                                                        size="sm"
                                                        class="text-xs h-7"
                                                    >
                                                        ✕
                                                    </Button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Legend -->
                        <div class="mt-4 flex items-center gap-4">
                            <div class="text-sm text-gray-600">
                                <strong>Available Modules ({{ modules.length }}):</strong>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="module in modules" :key="module.id" variant="outline">
                                    {{ module.code }} - {{ module.name }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Week Navigation -->
                <Card v-if="replicatedWeeks.length > 0">
                    <CardHeader>
                        <CardTitle>Quick Week Navigation</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-wrap gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="selectedWeek = 0; updateFilter()"
                                :class="{ 'bg-purple-100 border-purple-500': isTemplateWeek }"
                            >
                                Template
                            </Button>
                            <Button
                                v-for="week in replicatedWeeks"
                                :key="week"
                                variant="outline"
                                size="sm"
                                @click="selectedWeek = week; updateFilter()"
                                :class="{ 'bg-blue-100 border-blue-500': selectedWeek === week && !isTemplateWeek }"
                            >
                                Week {{ week }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- Schedule Actions Menu Dialog -->
        <Dialog :open="showScheduleActionsDialog" @update:open="(open) => !open && closeScheduleActionsMenu()">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Schedule Actions</DialogTitle>
                    <DialogDescription v-if="selectedScheduleForActions">
                        {{ selectedScheduleForActions.module?.code }} - {{ selectedScheduleForActions.module?.name }}
                        <br />
                        {{ selectedScheduleForActions.day }}, {{ selectedScheduleForActions.start_time.substring(0, 5) }} - {{ selectedScheduleForActions.end_time.substring(0, 5) }}
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-3">
                    <Button
                        @click="handleScheduleAction('attendance')"
                        class="w-full justify-start h-auto py-4"
                        variant="outline"
                        :disabled="selectedScheduleForActions && isScheduleInFuture(selectedScheduleForActions.day)"
                    >
                        <div class="flex items-start gap-3 text-left w-full">
                            <span class="text-2xl">📋</span>
                            <div class="flex-1">
                                <div class="font-semibold">Mark Attendance</div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    {{ selectedScheduleForActions && isScheduleInFuture(selectedScheduleForActions.day)
                                        ? 'Cannot mark attendance for future classes'
                                        : 'Record student attendance for this session' }}
                                </div>
                            </div>
                        </div>
                    </Button>

                    <Button
                        @click="handleScheduleAction('edit')"
                        class="w-full justify-start h-auto py-4"
                        variant="outline"
                    >
                        <div class="flex items-start gap-3 text-left w-full">
                            <span class="text-2xl">✏️</span>
                            <div class="flex-1">
                                <div class="font-semibold">Edit Schedule</div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    Modify time, type, or teacher assignment
                                </div>
                            </div>
                        </div>
                    </Button>

                    <Button
                        @click="handleScheduleAction('delete')"
                        class="w-full justify-start h-auto py-4"
                        variant="outline"
                    >
                        <div class="flex items-start gap-3 text-left w-full">
                            <span class="text-2xl">🗑️</span>
                            <div class="flex-1">
                                <div class="font-semibold text-red-600">Delete Schedule</div>
                                <div class="text-xs text-muted-foreground mt-1">
                                    Remove this session from the schedule
                                </div>
                            </div>
                        </div>
                    </Button>
                </div>

                <div class="mt-4 flex justify-end">
                    <Button @click="closeScheduleActionsMenu" variant="ghost">
                        Cancel
                    </Button>
                </div>
            </DialogContent>
        </Dialog>

        <!-- Attendance Dialog -->
        <Dialog :open="showAttendanceDialog" @update:open="(open) => !open && closeAttendanceDialog()">
            <DialogContent class="max-w-2xl max-h-[80vh] overflow-y-auto">
                <DialogHeader>
                    <DialogTitle>Mark Attendance</DialogTitle>
                    <DialogDescription v-if="attendanceSchedule">
                        {{ attendanceSchedule.module?.code }} - {{ attendanceSchedule.module?.name }}
                        <br />
                        {{ attendanceSchedule.day }}, {{ attendanceSchedule.start_time.substring(0, 5) }} - {{ attendanceSchedule.end_time.substring(0, 5) }}
                    </DialogDescription>
                </DialogHeader>

                <div v-if="loadingStudents" class="text-center py-8">
                    Loading students...
                </div>

                <div v-else-if="attendanceStudents.length === 0" class="text-center py-8 text-gray-500">
                    No students found for this class.
                </div>

                <div v-else class="space-y-2">
                    <div class="text-sm text-gray-600 mb-4">
                        Check the box for students who are <strong>absent</strong>. Leave unchecked for present students.
                    </div>
                    <div
                        v-for="student in attendanceStudents"
                        :key="student.id"
                        class="flex items-center space-x-3 p-3 border rounded hover:bg-gray-50"
                    >
                        <Checkbox
                            :id="`student-${student.id}`"
                            :model-value="!!attendanceRecords[student.id]"
                            @update:model-value="(checked) => {
                                attendanceRecords[student.id] = checked;
                                toggleStudentAttendance(student.id)
                            }"
                        />
                        <label
                            :for="`student-${student.id}`"
                            class="flex-1 cursor-pointer"
                        >
                            <div class="font-medium">{{ student.first_name }} {{ student.last_name }}</div>
                            <div class="text-xs text-gray-500">{{ student.code }} - {{ student.email }}</div>
                        </label>
                        <Badge :variant="attendanceRecords[student.id] ? 'destructive' : 'default'">
                            {{ attendanceRecords[student.id] ? 'Absent' : 'Present' }}
                        </Badge>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <Button @click="closeAttendanceDialog" variant="outline">
                        Close
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
