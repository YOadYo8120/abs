import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/student/justifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::index
* @see app/Http/Controllers/Student/JustificationController.php:21
* @route '/student/justifications'
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
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
export const create = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/student/justifications/create/{attendance}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
create.url = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { attendance: args }
    }

    if (Array.isArray(args)) {
        args = {
            attendance: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        attendance: args.attendance,
    }

    return create.definition.url
            .replace('{attendance}', parsedArgs.attendance.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
create.get = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
create.head = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
const createForm = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
createForm.get = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::create
* @see app/Http/Controllers/Student/JustificationController.php:68
* @route '/student/justifications/create/{attendance}'
*/
createForm.head = (args: { attendance: string | number } | [attendance: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Student\JustificationController::store
* @see app/Http/Controllers/Student/JustificationController.php:245
* @route '/student/justifications'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/student/justifications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::store
* @see app/Http/Controllers/Student/JustificationController.php:245
* @route '/student/justifications'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::store
* @see app/Http/Controllers/Student/JustificationController.php:245
* @route '/student/justifications'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::store
* @see app/Http/Controllers/Student/JustificationController.php:245
* @route '/student/justifications'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::store
* @see app/Http/Controllers/Student/JustificationController.php:245
* @route '/student/justifications'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
export const edit = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/student/justifications/{justification}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
edit.url = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { justification: args }
    }

    if (Array.isArray(args)) {
        args = {
            justification: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        justification: args.justification,
    }

    return edit.definition.url
            .replace('{justification}', parsedArgs.justification.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
edit.get = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
edit.head = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
const editForm = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
editForm.get = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::edit
* @see app/Http/Controllers/Student/JustificationController.php:118
* @route '/student/justifications/{justification}/edit'
*/
editForm.head = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Student\JustificationController::update
* @see app/Http/Controllers/Student/JustificationController.php:176
* @route '/student/justifications/{justification}'
*/
export const update = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/student/justifications/{justification}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::update
* @see app/Http/Controllers/Student/JustificationController.php:176
* @route '/student/justifications/{justification}'
*/
update.url = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { justification: args }
    }

    if (Array.isArray(args)) {
        args = {
            justification: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        justification: args.justification,
    }

    return update.definition.url
            .replace('{justification}', parsedArgs.justification.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::update
* @see app/Http/Controllers/Student/JustificationController.php:176
* @route '/student/justifications/{justification}'
*/
update.put = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::update
* @see app/Http/Controllers/Student/JustificationController.php:176
* @route '/student/justifications/{justification}'
*/
const updateForm = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::update
* @see app/Http/Controllers/Student/JustificationController.php:176
* @route '/student/justifications/{justification}'
*/
updateForm.put = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
export const show = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/student/justifications/{justification}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
show.url = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { justification: args }
    }

    if (Array.isArray(args)) {
        args = {
            justification: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        justification: args.justification,
    }

    return show.definition.url
            .replace('{justification}', parsedArgs.justification.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
show.get = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
show.head = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
const showForm = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
showForm.get = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::show
* @see app/Http/Controllers/Student/JustificationController.php:306
* @route '/student/justifications/{justification}'
*/
showForm.head = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
export const download = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/student/justifications/{file}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
download.url = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { file: args }
    }

    if (Array.isArray(args)) {
        args = {
            file: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        file: args.file,
    }

    return download.definition.url
            .replace('{file}', parsedArgs.file.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
download.get = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
download.head = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
const downloadForm = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
downloadForm.get = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Student\JustificationController::download
* @see app/Http/Controllers/Student/JustificationController.php:355
* @route '/student/justifications/{file}/download'
*/
downloadForm.head = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download.form = downloadForm

const justifications = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    show: Object.assign(show, show),
    download: Object.assign(download, download),
}

export default justifications