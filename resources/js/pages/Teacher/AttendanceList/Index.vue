<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { FileDown, AlertCircle } from 'lucide-vue-next'

interface Week {
    number: number
    start_date: string
    end_date: string
}

const props = defineProps<{
    weeks: Week[]
    currentYear: number
    currentSemester: number
    currentSpecialization?: string | null
    currentTrack?: string | null
}>()

const selectedYear = ref(props.currentYear)
const selectedSemester = ref(props.currentSemester)
const selectedSpecialization = ref<string | null>(props.currentSpecialization || null)
const selectedTrack = ref<string | null>(props.currentTrack || null)
const selectedDuration = ref<string | null>(null)
const selectedWeek = ref<number | null>(null)
const selectedDay = ref<string | null>(null)
const startTime = ref<string>('')
const isGenerating = ref(false)

const page = usePage()
const errors = computed(() => page.props.errors as Record<string, string>)
const flashError = computed(() => (page.props as any).error as string | undefined)

const hasErrors = computed(() => Object.keys(errors.value).length > 0 || flashError.value)

// Generate time slots from 8:30 to 18:30 in 30-minute intervals
const timeSlots = [
    '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
    '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00',
    '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30'
]

const specializations = [
    { value: 'GI', label: 'Génie Informatique' },
    { value: 'GRT', label: 'Génie des Réseaux et Télécommunications' },
    { value: 'GInd', label: 'Génie Industriel' },
    { value: 'GM', label: 'Génie Mécanique' },
    { value: 'GA', label: 'Génie de l\'Eau et de l\'Environnement' },
    { value: 'GPM', label: 'Génie des Procédés et Management de la Qualité' },
]

const tracks = [
    { value: 'DEV', label: 'Development' },
    { value: 'AI', label: 'Artificial Intelligence' },
]

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

const durations = [
    { value: '1h', label: '1 Hour' },
    { value: '2h', label: '2 Hours' },
]

const showSpecializationSelector = computed(() => selectedYear.value >= 3)
const showTrackSelector = computed(() =>
    selectedYear.value >= 4 &&
    selectedYear.value <= 5 &&
    selectedSpecialization.value === 'GI'
)

const canGenerate = computed(() => {
    if (selectedYear.value >= 3 && !selectedSpecialization.value) return false
    if (showTrackSelector.value && !selectedTrack.value) return false
    return selectedDuration.value && selectedWeek.value && selectedDay.value && startTime.value
})

const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = date.getFullYear()
    return `${day}/${month}/${year}`
}

watch([selectedYear, selectedSemester, selectedSpecialization, selectedTrack], ([newYear, , newSpec]) => {
    if (newYear <= 2 && newSpec) {
        selectedSpecialization.value = null
        selectedTrack.value = null
    }
    if (!showTrackSelector.value) {
        selectedTrack.value = null
    }
    updateFilter()
})

const updateFilter = () => {
    router.get('/teacher/attendance-list', {
        year: selectedYear.value,
        semester: selectedSemester.value,
        specialization: selectedSpecialization.value,
        track: selectedTrack.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const generatePDF = () => {
    if (!canGenerate.value) return

    isGenerating.value = true

    const params = {
        year: selectedYear.value.toString(),
        semester: selectedSemester.value.toString(),
        week_number: selectedWeek.value!.toString(),
        day: selectedDay.value!,
        duration: selectedDuration.value!,
        start_time: startTime.value,
    }

    if (selectedSpecialization.value) {
        params.specialization = selectedSpecialization.value
    }
    if (selectedTrack.value) {
        params.track = selectedTrack.value
    }

    // Use window location for PDF download
    const queryString = new URLSearchParams(params).toString()
    window.location.href = `/teacher/attendance-list/generate?${queryString}`

    setTimeout(() => {
        isGenerating.value = false
    }, 2000)
}
</script>

<template>
    <AppLayout>
        <Head title="Generate Attendance List" />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Generate Attendance List</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Generate a printable PDF attendance list for your class sessions
                    </p>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Select Session Details</CardTitle>
                        <CardDescription>Choose your module, week, and day to generate the attendance list</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Error Messages -->
                        <Alert v-if="hasErrors" class="border-red-500 bg-red-50">
                            <AlertCircle class="h-4 w-4 text-red-600" />
                            <AlertDescription class="text-red-600">
                                <div v-if="flashError" class="font-medium">
                                    {{ flashError }}
                                </div>
                                <div v-for="(error, key) in errors" :key="key" class="mt-1">
                                    {{ error }}
                                </div>
                            </AlertDescription>
                        </Alert>

                        <!-- Year Selection -->
                        <div class="space-y-2">
                            <Label>Academic Year</Label>
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

                        <!-- Semester Selection -->
                        <div class="space-y-2">
                            <Label>Semester</Label>
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

                        <!-- Specialization (Years 3-5) -->
                        <div v-if="showSpecializationSelector" class="space-y-2">
                            <Label>Specialization</Label>
                            <Select v-model="selectedSpecialization">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select specialization" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="spec in specializations" :key="spec.value" :value="spec.value">
                                        {{ spec.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Track (GI Years 4-5) -->
                        <div v-if="showTrackSelector" class="space-y-2">
                            <Label>Track</Label>
                            <Select v-model="selectedTrack">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select track" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="track in tracks" :key="track.value" :value="track.value">
                                        {{ track.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <Alert v-if="selectedYear >= 3 && !selectedSpecialization">
                            <AlertDescription>
                                Please select a specialization to view your modules for Year {{ selectedYear }}
                            </AlertDescription>
                        </Alert>

                        <Alert v-if="showTrackSelector && !selectedTrack">
                            <AlertDescription>
                                Please select a track (DEV or AI) for GI Year {{ selectedYear }}
                            </AlertDescription>
                        </Alert>

                        <!-- Duration Selection -->
                        <div class="space-y-2">
                            <Label>Session Duration</Label>
                            <Select v-model="selectedDuration">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select duration" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="duration in durations" :key="duration.value" :value="duration.value">
                                        {{ duration.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Start Time -->
                        <div class="space-y-2">
                            <Label>Start Time</Label>
                            <Select v-model="startTime">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select start time" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="time in timeSlots" :key="time" :value="time">
                                        {{ time }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Week Selection -->
                        <div v-if="weeks.length > 0" class="space-y-2">
                            <Label>Week</Label>
                            <Select v-model="selectedWeek">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select week" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="week in weeks" :key="week.number" :value="week.number">
                                        Week {{ week.number }} ({{ formatDate(week.start_date) }} - {{ formatDate(week.end_date) }})
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <Alert v-if="weeks.length === 0">
                            <AlertDescription>
                                No weeks found in academic calendar for Year {{ selectedYear }}, Semester {{ selectedSemester }}
                            </AlertDescription>
                        </Alert>

                        <!-- Day Selection -->
                        <div class="space-y-2">
                            <Label>Day</Label>
                            <Select v-model="selectedDay">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select day" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="day in days" :key="day" :value="day">
                                        {{ day }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Generate Button -->
                        <div class="pt-4">
                            <Button
                                @click="generatePDF"
                                :disabled="!canGenerate || isGenerating"
                                class="w-full"
                                size="lg"
                            >
                                <FileDown class="mr-2 h-5 w-5" />
                                {{ isGenerating ? 'Generating PDF...' : 'Generate Attendance List PDF' }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
