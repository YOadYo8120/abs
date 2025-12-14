import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/teacher/resources',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::index
* @see app/Http/Controllers/Teacher/ResourceController.php:18
* @route '/teacher/resources'
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
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/teacher/resources/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::create
* @see app/Http/Controllers/Teacher/ResourceController.php:35
* @route '/teacher/resources/create'
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
* @see \App\Http\Controllers\Teacher\ResourceController::store
* @see app/Http/Controllers/Teacher/ResourceController.php:81
* @route '/teacher/resources'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/teacher/resources',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::store
* @see app/Http/Controllers/Teacher/ResourceController.php:81
* @route '/teacher/resources'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::store
* @see app/Http/Controllers/Teacher/ResourceController.php:81
* @route '/teacher/resources'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::store
* @see app/Http/Controllers/Teacher/ResourceController.php:81
* @route '/teacher/resources'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::store
* @see app/Http/Controllers/Teacher/ResourceController.php:81
* @route '/teacher/resources'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
export const show = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/teacher/resources/{resource}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
show.url = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    if (Array.isArray(args)) {
        args = {
            resource: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        resource: args.resource,
    }

    return show.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
show.get = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
show.head = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
const showForm = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
showForm.get = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::show
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
showForm.head = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
export const edit = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/teacher/resources/{resource}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
edit.url = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    if (Array.isArray(args)) {
        args = {
            resource: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        resource: args.resource,
    }

    return edit.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
edit.get = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
edit.head = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
const editForm = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
editForm.get = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::edit
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}/edit'
*/
editForm.head = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
export const update = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/teacher/resources/{resource}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
update.url = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    if (Array.isArray(args)) {
        args = {
            resource: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        resource: args.resource,
    }

    return update.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
update.put = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
update.patch = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
const updateForm = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
updateForm.put = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::update
* @see app/Http/Controllers/Teacher/ResourceController.php:0
* @route '/teacher/resources/{resource}'
*/
updateForm.patch = (args: { resource: string | number } | [resource: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Teacher\ResourceController::destroy
* @see app/Http/Controllers/Teacher/ResourceController.php:162
* @route '/teacher/resources/{resource}'
*/
export const destroy = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/teacher/resources/{resource}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::destroy
* @see app/Http/Controllers/Teacher/ResourceController.php:162
* @route '/teacher/resources/{resource}'
*/
destroy.url = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { resource: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            resource: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        resource: typeof args.resource === 'object'
        ? args.resource.id
        : args.resource,
    }

    return destroy.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::destroy
* @see app/Http/Controllers/Teacher/ResourceController.php:162
* @route '/teacher/resources/{resource}'
*/
destroy.delete = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::destroy
* @see app/Http/Controllers/Teacher/ResourceController.php:162
* @route '/teacher/resources/{resource}'
*/
const destroyForm = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::destroy
* @see app/Http/Controllers/Teacher/ResourceController.php:162
* @route '/teacher/resources/{resource}'
*/
destroyForm.delete = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
export const download = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/teacher/resources/{resource}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
download.url = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { resource: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            resource: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        resource: typeof args.resource === 'object'
        ? args.resource.id
        : args.resource,
    }

    return download.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
download.get = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
download.head = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
const downloadForm = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
downloadForm.get = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/teacher/resources/{resource}/download'
*/
downloadForm.head = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download.form = downloadForm

const resources = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    download: Object.assign(download, download),
}

export default resources