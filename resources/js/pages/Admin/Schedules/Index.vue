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
import { index as schedulesIndex, update as schedulesUpdate, destroy as schedulesDestroy, replicate as schedulesReplicate, clearWeek as schedulesClearWeek, clearSemester as schedulesClearSemester } from '@/routes/admin/schedules'

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
    s1LastDate?: string | null
    weekDates?: { start: string; end: string } | null
}>()

const selectedYear = ref(props.currentYear)
const selectedSemester = ref(props.currentSemester)
const selectedWeek = ref(props.currentWeek)
const selectedSpecialization = ref<string | null>(props.currentSpecialization || null)
const selectedTrack = ref<string | null>(props.currentTrack || null)
const weeksToReplicate = ref(13) // Default: S1 = 13 weeks, S2 = 14 weeks
const semesterStartDate = ref<string>('')

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

// Editing state for inline form
const editingCell = ref<{ day: string; time: string } | null>(null)
const editForm = ref({
    moduleId: null as number | null,
    scheduleType: null as 'course' | 'TD' | 'TP' | 'exam' | null,
    teacherId: null as number | null,
})

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
    if (!editForm.value.moduleId) {
        console.error('No module selected')
        return
    }

    console.log('Assigning module:', {
        day,
        timeSlot,
        editForm: editForm.value,
        year: selectedYear.value,
        semester: selectedSemester.value,
        week: selectedWeek.value,
        specialization: selectedSpecialization.value,
        track: selectedTrack.value
    })

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

    const form = useForm(formData)

    form.post(schedulesUpdate.url(), {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
            console.log('Schedule saved successfully')
            editingCell.value = null
            editForm.value = {
                moduleId: null,
                scheduleType: 'course',
                teacherId: null,
            }
        },
        onError: (errors) => {
            console.error('Failed to save schedule:', errors)
            alert('Error saving schedule: ' + JSON.stringify(errors))
        },
    })
}

// Start editing a cell
const startEditing = (day: string, timeSlot: TimeSlot) => {
    const schedule = findSchedule(day, timeSlot)
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

    const form = useForm(formData)

    form.delete(schedulesDestroy.url(), {
        preserveState: false, // Force reload to get fresh data
        preserveScroll: true,
    })
}

// Replicate template to all weeks
const replicateTemplate = () => {
    // Validate required fields
    if (!semesterStartDate.value) {
        alert('Please select the start date for the first week of the semester.')
        return
    }

    if (!weeksToReplicate.value || weeksToReplicate.value < 1) {
        alert('Please enter a valid number of weeks (at least 1).')
        return
    }

    if (weeksToReplicate.value > 30) {
        alert('Number of weeks cannot exceed 30.')
        return
    }

    // For S2, validate that start date is after S1 end date
    if (selectedSemester.value === 2 && props.s1LastDate) {
        const s2Start = new Date(semesterStartDate.value)
        const s1End = new Date(props.s1LastDate)

        // S2 must start at least 1 day after S1 ends
        if (s2Start <= s1End) {
            const minDate = new Date(s1End)
            minDate.setDate(minDate.getDate() + 1)
            alert(
                `Semester 2 start date must be AFTER Semester 1 end date.\n\n` +
                `S1 ends on: ${s1End.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}\n` +
                `S2 must start on or after: ${minDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}\n\n` +
                `(For academic year 2025-2026, S2 typically starts on February 2, 2026)`
            )
            return
        }
    }

    if (!confirm(`This will replicate the template to ${weeksToReplicate.value} weeks starting from ${semesterStartDate.value}. Existing schedules will be replaced. Continue?`)) {
        return
    }

    const formData: Record<string, any> = {
        year: selectedYear.value,
        semester: selectedSemester.value,
        weeks_count: weeksToReplicate.value,
        start_date: semesterStartDate.value,
    }

    // Add specialization for years 3-5
    if (selectedYear.value >= 3 && selectedSpecialization.value) {
        formData.specialization = selectedSpecialization.value
    }

    // Add track for GI years 4-5
    if (showTrackSelector.value && selectedTrack.value) {
        formData.track = selectedTrack.value
    }

    const form = useForm(formData)

    form.post(schedulesReplicate.url(), {
        preserveState: false,
        onSuccess: () => {
            // Clear the form
            semesterStartDate.value = ''
            // Redirect to week 1 after replication
            const params: Record<string, any> = {
                year: selectedYear.value,
                semester: selectedSemester.value,
                week: 1,
            }
            if (selectedYear.value >= 3 && selectedSpecialization.value) {
                params.specialization = selectedSpecialization.value
            }
            if (showTrackSelector.value && selectedTrack.value) {
                params.track = selectedTrack.value
            }
            router.get(schedulesIndex.url(), params)
        },
        onError: (errors) => {
            console.error('Replication failed:', errors)
            let errorMessage = 'Failed to replicate template.\n\n'
            if (typeof errors === 'object') {
                errorMessage += Object.values(errors).join('\n')
            } else {
                errorMessage += String(errors)
            }
            alert(errorMessage)
        },
    })
}

// Clear current week
const clearCurrentWeek = () => {
    const weekLabel = selectedWeek.value === 0 ? 'template week' : `week ${selectedWeek.value}`
    if (!confirm(`Are you sure you want to clear all schedules for ${weekLabel}? This cannot be undone.`)) {
        return
    }

    const formData: Record<string, any> = {
        year: selectedYear.value,
        semester: selectedSemester.value,
        week_number: selectedWeek.value,
    }

    // Add specialization for years 3-5
    if (selectedYear.value >= 3 && selectedSpecialization.value) {
        formData.specialization = selectedSpecialization.value
    }

    // Add track for GI years 4-5
    if (showTrackSelector.value && selectedTrack.value) {
        formData.track = selectedTrack.value
    }

    const form = useForm(formData)

    form.post(schedulesClearWeek.url(), {
        preserveState: false,
    })
}

// Clear entire semester
const clearEntireSemester = () => {
    if (!confirm(`Are you sure you want to clear ALL schedules (template + all weeks) for Year ${selectedYear.value} Semester ${selectedSemester.value}? This cannot be undone.`)) {
        return
    }

    const formData: Record<string, any> = {
        year: selectedYear.value,
        semester: selectedSemester.value,
    }

    // Add specialization for years 3-5
    if (selectedYear.value >= 3 && selectedSpecialization.value) {
        formData.specialization = selectedSpecialization.value
    }

    // Add track for GI years 4-5
    if (showTrackSelector.value && selectedTrack.value) {
        formData.track = selectedTrack.value
    }

    const form = useForm(formData)

    form.post(schedulesClearSemester.url(), {
        preserveState: false,
        onSuccess: () => {
            // Redirect to template week after clearing
            const params: Record<string, any> = {
                year: selectedYear.value,
                semester: selectedSemester.value,
                week: 0,
            }
            if (selectedYear.value >= 3 && selectedSpecialization.value) {
                params.specialization = selectedSpecialization.value
            }
            if (showTrackSelector.value && selectedTrack.value) {
                params.track = selectedTrack.value
            }
            router.get(schedulesIndex.url(), params)
        },
    })
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
        return 'Template Week (will be replicated)'
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
                        <h2 class="text-2xl font-semibold text-gray-900">Schedule Management</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Create a template week, replicate it, then edit individual weeks
                        </p>
                    </div>
                </div>

                <!-- Instructions Alert -->
                <Alert v-if="isTemplateWeek && !templateExists">
                    <AlertDescription>
                        <strong>Step 1:</strong> Create your template week by assigning modules to time slots below.
                        <br>
                        <strong>Step 2:</strong> Once complete, use the "Replicate Template" button to copy this schedule to all weeks of the semester.
                        <br>
                        <strong>Step 3:</strong> After replication, you can select specific weeks and modify them as needed.
                    </AlertDescription>
                </Alert>

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
                                        <!-- Show template week ONLY if no replication has been done -->
                                        <SelectItem v-if="replicatedWeeks.length === 0" :value="0">Template Week</SelectItem>
                                        <!-- Show actual weeks ONLY if replication has been done -->
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

                <!-- Replication Section -->
                <Card v-if="isTemplateWeek && templateExists">
                    <CardHeader>
                        <CardTitle>Replicate Template</CardTitle>
                        <CardDescription>Copy this template to all weeks of the semester</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Start Date (First Monday)</label>
                                    <Input
                                        v-model="semesterStartDate"
                                        type="date"
                                        required
                                    />
                                    <p v-if="selectedSemester === 2 && s1LastDate" class="text-xs text-muted-foreground mt-1">
                                        Must be after S1 end: {{ s1LastDate }}
                                    </p>
                                    <p v-else class="text-xs text-muted-foreground mt-1">
                                        Select the Monday of the first week
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Number of Weeks</label>
                                    <Input
                                        v-model.number="weeksToReplicate"
                                        type="number"
                                        min="1"
                                        max="30"
                                        :placeholder="selectedSemester === 1 ? '13' : '14'"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Typical: S1 = 13 weeks, S2 = 14 weeks (max: 30)
                                    </p>
                                </div>
                            </div>
                            <Button @click="replicateTemplate" variant="default" size="lg" class="w-full">
                                Replicate to {{ weeksToReplicate }} Weeks Starting {{ semesterStartDate || '(select date)' }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Clear Actions Section -->
                <Card v-if="replicatedWeeks.length > 0 || templateExists">
                    <CardHeader>
                        <CardTitle>Clear Schedules</CardTitle>
                        <CardDescription>Remove schedules to start fresh</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex gap-4 flex-wrap">
                            <Button @click="clearCurrentWeek" variant="outline" size="default">
                                Clear Current {{ isTemplateWeek ? 'Template Week' : `Week ${selectedWeek}` }}
                            </Button>
                            <Button @click="clearEntireSemester" variant="destructive" size="default">
                                Clear Entire Semester (Template + All Weeks)
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Schedule Grid -->
                <Card>
                    <CardHeader>
                        <CardTitle>
                            Year {{ selectedYear }} - Semester {{ selectedSemester }} - {{ weekTitle }}
                        </CardTitle>
                        <CardDescription>
                            <span v-if="isTemplateWeek">
                                This is your template week. Changes here will be used when replicating.
                            </span>
                            <span v-else>
                                Editing Week {{ selectedWeek }}. Changes only affect this specific week.
                            </span>
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
                                                class="p-2 rounded bg-blue-100 text-blue-900 text-xs cursor-pointer"
                                                @click="startEditing(day, timeSlot)"
                                            >
                                                <div class="font-semibold">
                                                    {{ findSchedule(day, timeSlot)?.module?.code }}
                                                    <Badge v-if="findSchedule(day, timeSlot)?.schedule_type" variant="secondary" class="ml-1">
                                                        {{ findSchedule(day, timeSlot)?.schedule_type }}
                                                    </Badge>
                                                </div>
                                                <div class="truncate text-[11px]">
                                                    {{ findSchedule(day, timeSlot)?.module?.name }}
                                                </div>
                                                <div v-if="findSchedule(day, timeSlot)?.teacher" class="mt-1 text-[10px] text-blue-700 font-medium">
                                                    👤 {{ findSchedule(day, timeSlot)?.teacher?.name }}
                                                </div>
                                            </div>

                                            <!-- Empty cell: Show quick assign button -->
                                            <div
                                                v-else-if="!isEditing(day, timeSlot)"
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
    </AppLayout>
</template>
