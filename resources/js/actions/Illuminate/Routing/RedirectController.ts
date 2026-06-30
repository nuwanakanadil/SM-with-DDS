import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
const RedirectControllere9819db9819a1d19b38dd89a0c4218c4 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'get',
})

RedirectControllere9819db9819a1d19b38dd89a0c4218c4.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/register',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url = (options?: RouteQueryOptions) => {
    return RedirectControllere9819db9819a1d19b38dd89a0c4218c4.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
RedirectControllere9819db9819a1d19b38dd89a0c4218c4.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
    const RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/register'
 */
        RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllere9819db9819a1d19b38dd89a0c4218c4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectControllere9819db9819a1d19b38dd89a0c4218c4.form = RedirectControllere9819db9819a1d19b38dd89a0c4218c4Form
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
const RedirectController35f58437d9250c39f332f5e8e70440b7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})

RedirectController35f58437d9250c39f332f5e8e70440b7.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.url = (options?: RouteQueryOptions) => {
    return RedirectController35f58437d9250c39f332f5e8e70440b7.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
RedirectController35f58437d9250c39f332f5e8e70440b7.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
    const RedirectController35f58437d9250c39f332f5e8e70440b7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin'
 */
        RedirectController35f58437d9250c39f332f5e8e70440b7Form.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController35f58437d9250c39f332f5e8e70440b7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController35f58437d9250c39f332f5e8e70440b7.form = RedirectController35f58437d9250c39f332f5e8e70440b7Form

/**
* Multiple routes resolve to \Illuminate\Routing\RedirectController::RedirectController, so this export is a
* dictionary keyed by URI rather than a callable. Call a specific route with `RedirectController['<uri>'](...)`,
* or import the route by name from your generated `routes/` directory.
*/
const RedirectController = {
    '/register': RedirectControllere9819db9819a1d19b38dd89a0c4218c4,
    '/admin': RedirectController35f58437d9250c39f332f5e8e70440b7,
}

export default RedirectController