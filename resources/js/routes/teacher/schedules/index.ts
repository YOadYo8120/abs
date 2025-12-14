import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/teacher/schedules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::index
* @see app/Http/Controllers/Teacher/ScheduleController.php:17
* @route '/teacher/schedules'
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
* @see \App\Http\Controllers\Teacher\ScheduleController::update
* @see app/Http/Controllers/Teacher/ScheduleController.php:147
* @route '/teacher/schedules'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/teacher/schedules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::update
* @see app/Http/Controllers/Teacher/ScheduleController.php:147
* @route '/teacher/schedules'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::update
* @see app/Http/Controllers/Teacher/ScheduleController.php:147
* @route '/teacher/schedules'
*/
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::update
* @see app/Http/Controllers/Teacher/ScheduleController.php:147
* @route '/teacher/schedules'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::update
* @see app/Http/Controllers/Teacher/ScheduleController.php:147
* @route '/teacher/schedules'
*/
updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::destroy
* @see app/Http/Controllers/Teacher/ScheduleController.php:239
* @route '/teacher/schedules'
*/
export const destroy = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/teacher/schedules',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::destroy
* @see app/Http/Controllers/Teacher/ScheduleController.php:239
* @route '/teacher/schedules'
*/
destroy.url = (options?: RouteQueryOptions) => {
    return destroy.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::destroy
* @see app/Http/Controllers/Teacher/ScheduleController.php:239
* @route '/teacher/schedules'
*/
destroy.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Teacher\ScheduleController::destroy
* @see app/Http/Controllers/Teacher/ScheduleController.php:239
* @route '/teacher/schedules'
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
* @see \App\Http\Controllers\Teacher\ScheduleController::destroy
* @see app/Http/Controllers/Teacher/ScheduleController.php:239
* @route '/teacher/schedules'
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

const schedules = {
    index: Object.assign(index, index),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default schedules