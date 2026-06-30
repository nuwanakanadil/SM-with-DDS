import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/results',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::index
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:21
 * @route '/admin/results'
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
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/results/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::create
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:36
 * @route '/admin/results/create'
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
* @see \App\Http\Controllers\Admin\AssessmentResultController::store
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:44
 * @route '/admin/results'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/results',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::store
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:44
 * @route '/admin/results'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::store
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:44
 * @route '/admin/results'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::store
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:44
 * @route '/admin/results'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::store
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:44
 * @route '/admin/results'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
export const edit = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/results/{result}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
edit.url = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { result: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { result: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    result: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        result: typeof args.result === 'object'
                ? args.result.id
                : args.result,
                }

    return edit.definition.url
            .replace('{result}', parsedArgs.result.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
edit.get = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
edit.head = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
    const editForm = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
        editForm.get = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::edit
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:51
 * @route '/admin/results/{result}/edit'
 */
        editForm.head = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
export const update = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/results/{result}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
update.url = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { result: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { result: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    result: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        result: typeof args.result === 'object'
                ? args.result.id
                : args.result,
                }

    return update.definition.url
            .replace('{result}', parsedArgs.result.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
update.put = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
update.patch = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
    const updateForm = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
        updateForm.put = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::update
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:60
 * @route '/admin/results/{result}'
 */
        updateForm.patch = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\AssessmentResultController::destroy
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:67
 * @route '/admin/results/{result}'
 */
export const destroy = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/results/{result}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::destroy
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:67
 * @route '/admin/results/{result}'
 */
destroy.url = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { result: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { result: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    result: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        result: typeof args.result === 'object'
                ? args.result.id
                : args.result,
                }

    return destroy.definition.url
            .replace('{result}', parsedArgs.result.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AssessmentResultController::destroy
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:67
 * @route '/admin/results/{result}'
 */
destroy.delete = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::destroy
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:67
 * @route '/admin/results/{result}'
 */
    const destroyForm = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\AssessmentResultController::destroy
 * @see app/Http/Controllers/Admin/AssessmentResultController.php:67
 * @route '/admin/results/{result}'
 */
        destroyForm.delete = (args: { result: number | { id: number } } | [result: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const AssessmentResultController = { index, create, store, edit, update, destroy }

export default AssessmentResultController