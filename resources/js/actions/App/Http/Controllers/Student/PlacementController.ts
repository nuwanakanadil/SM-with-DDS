import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
const PlacementController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlacementController.url(options),
    method: 'get',
})

PlacementController.definition = {
    methods: ["get","head"],
    url: '/my-placement',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
PlacementController.url = (options?: RouteQueryOptions) => {
    return PlacementController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
PlacementController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PlacementController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
PlacementController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PlacementController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
    const PlacementControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: PlacementController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
        PlacementControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PlacementController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\PlacementController::__invoke
 * @see app/Http/Controllers/Student/PlacementController.php:16
 * @route '/my-placement'
 */
        PlacementControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PlacementController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    PlacementController.form = PlacementControllerForm
export default PlacementController