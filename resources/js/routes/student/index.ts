import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
export const results = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: results.url(options),
    method: 'get',
})

results.definition = {
    methods: ["get","head"],
    url: '/my-results',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
results.url = (options?: RouteQueryOptions) => {
    return results.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
results.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: results.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
results.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: results.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
    const resultsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: results.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
        resultsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: results.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
        resultsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: results.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    results.form = resultsForm
/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
export const placement = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: placement.url(options),
    method: 'get',
})

placement.definition = {
    methods: ["get","head"],
    url: '/my-placement',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
placement.url = (options?: RouteQueryOptions) => {
    return placement.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
placement.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: placement.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
placement.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: placement.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
    const placementForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: placement.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
        placementForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: placement.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
        placementForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: placement.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    placement.form = placementForm
const student = {
    results: Object.assign(results, results),
placement: Object.assign(placement, placement),
}

export default student