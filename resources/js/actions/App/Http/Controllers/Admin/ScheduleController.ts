import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/schedules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::index
* @see app/Http/Controllers/Admin/ScheduleController.php:17
* @route '/admin/schedules'
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
* @see \App\Http\Controllers\Admin\ScheduleController::update
* @see app/Http/Controllers/Admin/ScheduleController.php:246
* @route '/admin/schedules'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/admin/schedules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::update
* @see app/Http/Controllers/Admin/ScheduleController.php:246
* @route '/admin/schedules'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::update
* @see app/Http/Controllers/Admin/ScheduleController.php:246
* @route '/admin/schedules'
*/
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::update
* @see app/Http/Controllers/Admin/ScheduleController.php:246
* @route '/admin/schedules'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::update
* @see app/Http/Controllers/Admin/ScheduleController.php:246
* @route '/admin/schedules'
*/
updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
* @see app/Http/Controllers/Admin/ScheduleController.php:296
* @route '/admin/schedules'
*/
export const destroy = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/schedules',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
* @see app/Http/Controllers/Admin/ScheduleController.php:296
* @route '/admin/schedules'
*/
destroy.url = (options?: RouteQueryOptions) => {
    return destroy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
* @see app/Http/Controllers/Admin/ScheduleController.php:296
* @route '/admin/schedules'
*/
destroy.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
* @see app/Http/Controllers/Admin/ScheduleController.php:296
* @route '/admin/schedules'
*/
const destroyForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
* @see app/Http/Controllers/Admin/ScheduleController.php:296
* @route '/admin/schedules'
*/
destroyForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\Admin\ScheduleController::replicate
* @see app/Http/Controllers/Admin/ScheduleController.php:338
* @route '/admin/schedules/replicate'
*/
export const replicate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: replicate.url(options),
    method: 'post',
})

replicate.definition = {
    methods: ["post"],
    url: '/admin/schedules/replicate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::replicate
* @see app/Http/Controllers/Admin/ScheduleController.php:338
* @route '/admin/schedules/replicate'
*/
replicate.url = (options?: RouteQueryOptions) => {
    return replicate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::replicate
* @see app/Http/Controllers/Admin/ScheduleController.php:338
* @route '/admin/schedules/replicate'
*/
replicate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: replicate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::replicate
* @see app/Http/Controllers/Admin/ScheduleController.php:338
* @route '/admin/schedules/replicate'
*/
const replicateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: replicate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::replicate
* @see app/Http/Controllers/Admin/ScheduleController.php:338
* @route '/admin/schedules/replicate'
*/
replicateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: replicate.url(options),
    method: 'post',
})

replicate.form = replicateForm

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearWeek
* @see app/Http/Controllers/Admin/ScheduleController.php:469
* @route '/admin/schedules/clear-week'
*/
export const clearWeek = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearWeek.url(options),
    method: 'post',
})

clearWeek.definition = {
    methods: ["post"],
    url: '/admin/schedules/clear-week',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearWeek
* @see app/Http/Controllers/Admin/ScheduleController.php:469
* @route '/admin/schedules/clear-week'
*/
clearWeek.url = (options?: RouteQueryOptions) => {
    return clearWeek.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearWeek
* @see app/Http/Controllers/Admin/ScheduleController.php:469
* @route '/admin/schedules/clear-week'
*/
clearWeek.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearWeek.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearWeek
* @see app/Http/Controllers/Admin/ScheduleController.php:469
* @route '/admin/schedules/clear-week'
*/
const clearWeekForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearWeek.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearWeek
* @see app/Http/Controllers/Admin/ScheduleController.php:469
* @route '/admin/schedules/clear-week'
*/
clearWeekForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearWeek.url(options),
    method: 'post',
})

clearWeek.form = clearWeekForm

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearSemester
* @see app/Http/Controllers/Admin/ScheduleController.php:510
* @route '/admin/schedules/clear-semester'
*/
export const clearSemester = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearSemester.url(options),
    method: 'post',
})

clearSemester.definition = {
    methods: ["post"],
    url: '/admin/schedules/clear-semester',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearSemester
* @see app/Http/Controllers/Admin/ScheduleController.php:510
* @route '/admin/schedules/clear-semester'
*/
clearSemester.url = (options?: RouteQueryOptions) => {
    return clearSemester.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearSemester
* @see app/Http/Controllers/Admin/ScheduleController.php:510
* @route '/admin/schedules/clear-semester'
*/
clearSemester.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clearSemester.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearSemester
* @see app/Http/Controllers/Admin/ScheduleController.php:510
* @route '/admin/schedules/clear-semester'
*/
const clearSemesterForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearSemester.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ScheduleController::clearSemester
* @see app/Http/Controllers/Admin/ScheduleController.php:510
* @route '/admin/schedules/clear-semester'
*/
clearSemesterForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: clearSemester.url(options),
    method: 'post',
})

clearSemester.form = clearSemesterForm

const ScheduleController = { index, update, destroy, replicate, clearWeek, clearSemester }

export default ScheduleController