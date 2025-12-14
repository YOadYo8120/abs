import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/teacher/announcements',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::index
* @see app/Http/Controllers/Teacher/AnnouncementController.php:17
* @route '/teacher/announcements'
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
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/teacher/announcements/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::create
* @see app/Http/Controllers/Teacher/AnnouncementController.php:34
* @route '/teacher/announcements/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::store
* @see app/Http/Controllers/Teacher/AnnouncementController.php:127
* @route '/teacher/announcements'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/teacher/announcements',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::store
* @see app/Http/Controllers/Teacher/AnnouncementController.php:127
* @route '/teacher/announcements'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::store
* @see app/Http/Controllers/Teacher/AnnouncementController.php:127
* @route '/teacher/announcements'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::store
* @see app/Http/Controllers/Teacher/AnnouncementController.php:127
* @route '/teacher/announcements'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::store
* @see app/Http/Controllers/Teacher/AnnouncementController.php:127
* @route '/teacher/announcements'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
export const show = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/teacher/announcements/{announcement}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
show.url = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { announcement: args }
    }

    if (Array.isArray(args)) {
        args = {
            announcement: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        announcement: args.announcement,
    }

    return show.definition.url
            .replace('{announcement}', parsedArgs.announcement.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
show.get = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
show.head = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
const showForm = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
showForm.get = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::show
* @see app/Http/Controllers/Teacher/AnnouncementController.php:0
* @route '/teacher/announcements/{announcement}'
*/
showForm.head = (args: { announcement: string | number } | [announcement: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
export const edit = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/teacher/announcements/{announcement}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
edit.url = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { announcement: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { announcement: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            announcement: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        announcement: typeof args.announcement === 'object'
        ? args.announcement.id
        : args.announcement,
    }

    return edit.definition.url
            .replace('{announcement}', parsedArgs.announcement.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
edit.get = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
edit.head = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
const editForm = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
editForm.get = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::edit
* @see app/Http/Controllers/Teacher/AnnouncementController.php:192
* @route '/teacher/announcements/{announcement}/edit'
*/
editForm.head = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
export const update = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/teacher/announcements/{announcement}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
update.url = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { announcement: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { announcement: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            announcement: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        announcement: typeof args.announcement === 'object'
        ? args.announcement.id
        : args.announcement,
    }

    return update.definition.url
            .replace('{announcement}', parsedArgs.announcement.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
update.put = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
update.patch = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
const updateForm = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
updateForm.put = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::update
* @see app/Http/Controllers/Teacher/AnnouncementController.php:240
* @route '/teacher/announcements/{announcement}'
*/
updateForm.patch = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::destroy
* @see app/Http/Controllers/Teacher/AnnouncementController.php:264
* @route '/teacher/announcements/{announcement}'
*/
export const destroy = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/teacher/announcements/{announcement}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::destroy
* @see app/Http/Controllers/Teacher/AnnouncementController.php:264
* @route '/teacher/announcements/{announcement}'
*/
destroy.url = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { announcement: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { announcement: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            announcement: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        announcement: typeof args.announcement === 'object'
        ? args.announcement.id
        : args.announcement,
    }

    return destroy.definition.url
            .replace('{announcement}', parsedArgs.announcement.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::destroy
* @see app/Http/Controllers/Teacher/AnnouncementController.php:264
* @route '/teacher/announcements/{announcement}'
*/
destroy.delete = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::destroy
* @see app/Http/Controllers/Teacher/AnnouncementController.php:264
* @route '/teacher/announcements/{announcement}'
*/
const destroyForm = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::destroy
* @see app/Http/Controllers/Teacher/AnnouncementController.php:264
* @route '/teacher/announcements/{announcement}'
*/
destroyForm.delete = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::publish
* @see app/Http/Controllers/Teacher/AnnouncementController.php:282
* @route '/teacher/announcements/{announcement}/publish'
*/
export const publish = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: publish.url(args, options),
    method: 'post',
})

publish.definition = {
    methods: ["post"],
    url: '/teacher/announcements/{announcement}/publish',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::publish
* @see app/Http/Controllers/Teacher/AnnouncementController.php:282
* @route '/teacher/announcements/{announcement}/publish'
*/
publish.url = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { announcement: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { announcement: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            announcement: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        announcement: typeof args.announcement === 'object'
        ? args.announcement.id
        : args.announcement,
    }

    return publish.definition.url
            .replace('{announcement}', parsedArgs.announcement.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::publish
* @see app/Http/Controllers/Teacher/AnnouncementController.php:282
* @route '/teacher/announcements/{announcement}/publish'
*/
publish.post = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: publish.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::publish
* @see app/Http/Controllers/Teacher/AnnouncementController.php:282
* @route '/teacher/announcements/{announcement}/publish'
*/
const publishForm = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: publish.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::publish
* @see app/Http/Controllers/Teacher/AnnouncementController.php:282
* @route '/teacher/announcements/{announcement}/publish'
*/
publishForm.post = (args: { announcement: number | { id: number } } | [announcement: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: publish.url(args, options),
    method: 'post',
})

publish.form = publishForm

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
export const getSchedules = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getSchedules.url(options),
    method: 'get',
})

getSchedules.definition = {
    methods: ["get","head"],
    url: '/teacher/announcements/schedules/get',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
getSchedules.url = (options?: RouteQueryOptions) => {
    return getSchedules.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
getSchedules.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getSchedules.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
getSchedules.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getSchedules.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
const getSchedulesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getSchedules.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
getSchedulesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getSchedules.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\AnnouncementController::getSchedules
* @see app/Http/Controllers/Teacher/AnnouncementController.php:80
* @route '/teacher/announcements/schedules/get'
*/
getSchedulesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getSchedules.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getSchedules.form = getSchedulesForm

const AnnouncementController = { index, create, store, show, edit, update, destroy, publish, getSchedules }

export default AnnouncementController