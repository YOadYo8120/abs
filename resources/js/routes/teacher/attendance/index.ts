import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/teacher/attendance',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::index
* @see app/Http/Controllers/Teacher/AttendanceController.php:17
* @route '/teacher/attendance'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
export const schedules = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schedules.url(options),
    method: 'get',
})

schedules.definition = {
    methods: ["get","head"],
    url: '/teacher/attendance/schedules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
schedules.url = (options?: RouteQueryOptions) => {
    return schedules.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
schedules.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: schedules.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
schedules.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: schedules.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
const schedulesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: schedules.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
schedulesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: schedules.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::schedules
* @see app/Http/Controllers/Teacher/AttendanceController.php:135
* @route '/teacher/attendance/schedules'
*/
schedulesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: schedules.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

schedules.form = schedulesForm

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::update
* @see app/Http/Controllers/Teacher/AttendanceController.php:91
* @route '/teacher/attendance'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/teacher/attendance',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::update
* @see app/Http/Controllers/Teacher/AttendanceController.php:91
* @route '/teacher/attendance'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::update
* @see app/Http/Controllers/Teacher/AttendanceController.php:91
* @route '/teacher/attendance'
*/
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::update
* @see app/Http/Controllers/Teacher/AttendanceController.php:91
* @route '/teacher/attendance'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AttendanceController::update
* @see app/Http/Controllers/Teacher/AttendanceController.php:91
* @route '/teacher/attendance'
*/
updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

update.form = updateForm

const attendance = {
    index: Object.assign(index, index),
    schedules: Object.assign(schedules, schedules),
    update: Object.assign(update, update),
}

export default attendance