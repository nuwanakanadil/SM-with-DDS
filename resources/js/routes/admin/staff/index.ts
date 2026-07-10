import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/staff',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\StaffController::index
 * @see app/Http/Controllers/Admin/StaffController.php:21
 * @route '/admin/staff'
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
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/staff/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\StaffController::create
 * @see app/Http/Controllers/Admin/StaffController.php:34
 * @route '/admin/staff/create'
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
* @see \App\Http\Controllers\Admin\StaffController::store
 * @see app/Http/Controllers/Admin/StaffController.php:39
 * @route '/admin/staff'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/staff',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::store
 * @see app/Http/Controllers/Admin/StaffController.php:39
 * @route '/admin/staff'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::store
 * @see app/Http/Controllers/Admin/StaffController.php:39
 * @route '/admin/staff'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::store
 * @see app/Http/Controllers/Admin/StaffController.php:39
 * @route '/admin/staff'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::store
 * @see app/Http/Controllers/Admin/StaffController.php:39
 * @route '/admin/staff'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
export const edit = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/staff/{staff}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
edit.url = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { staff: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { staff: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    staff: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        staff: typeof args.staff === 'object'
                ? args.staff.id
                : args.staff,
                }

    return edit.definition.url
            .replace('{staff}', parsedArgs.staff.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
edit.get = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
edit.head = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
    const editForm = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
        editForm.get = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\StaffController::edit
 * @see app/Http/Controllers/Admin/StaffController.php:52
 * @route '/admin/staff/{staff}/edit'
 */
        editForm.head = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
export const update = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/staff/{staff}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
update.url = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { staff: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { staff: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    staff: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        staff: typeof args.staff === 'object'
                ? args.staff.id
                : args.staff,
                }

    return update.definition.url
            .replace('{staff}', parsedArgs.staff.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
update.put = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
update.patch = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
    const updateForm = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
        updateForm.put = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\StaffController::update
 * @see app/Http/Controllers/Admin/StaffController.php:61
 * @route '/admin/staff/{staff}'
 */
        updateForm.patch = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\StaffController::destroy
 * @see app/Http/Controllers/Admin/StaffController.php:70
 * @route '/admin/staff/{staff}'
 */
export const destroy = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/staff/{staff}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::destroy
 * @see app/Http/Controllers/Admin/StaffController.php:70
 * @route '/admin/staff/{staff}'
 */
destroy.url = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { staff: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { staff: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    staff: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        staff: typeof args.staff === 'object'
                ? args.staff.id
                : args.staff,
                }

    return destroy.definition.url
            .replace('{staff}', parsedArgs.staff.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::destroy
 * @see app/Http/Controllers/Admin/StaffController.php:70
 * @route '/admin/staff/{staff}'
 */
destroy.delete = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::destroy
 * @see app/Http/Controllers/Admin/StaffController.php:70
 * @route '/admin/staff/{staff}'
 */
    const destroyForm = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::destroy
 * @see app/Http/Controllers/Admin/StaffController.php:70
 * @route '/admin/staff/{staff}'
 */
        destroyForm.delete = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\StaffController::resendLogin
 * @see app/Http/Controllers/Admin/StaffController.php:83
 * @route '/admin/staff/{staff}/resend-login'
 */
export const resendLogin = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: resendLogin.url(args, options),
    method: 'post',
})

resendLogin.definition = {
    methods: ["post"],
    url: '/admin/staff/{staff}/resend-login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\StaffController::resendLogin
 * @see app/Http/Controllers/Admin/StaffController.php:83
 * @route '/admin/staff/{staff}/resend-login'
 */
resendLogin.url = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { staff: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { staff: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    staff: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        staff: typeof args.staff === 'object'
                ? args.staff.id
                : args.staff,
                }

    return resendLogin.definition.url
            .replace('{staff}', parsedArgs.staff.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\StaffController::resendLogin
 * @see app/Http/Controllers/Admin/StaffController.php:83
 * @route '/admin/staff/{staff}/resend-login'
 */
resendLogin.post = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: resendLogin.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\StaffController::resendLogin
 * @see app/Http/Controllers/Admin/StaffController.php:83
 * @route '/admin/staff/{staff}/resend-login'
 */
    const resendLoginForm = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: resendLogin.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\StaffController::resendLogin
 * @see app/Http/Controllers/Admin/StaffController.php:83
 * @route '/admin/staff/{staff}/resend-login'
 */
        resendLoginForm.post = (args: { staff: string | number | { id: string | number } } | [staff: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: resendLogin.url(args, options),
            method: 'post',
        })
    
    resendLogin.form = resendLoginForm
const staff = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
resendLogin: Object.assign(resendLogin, resendLogin),
}

export default staff