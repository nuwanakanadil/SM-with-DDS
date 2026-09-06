import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import results8ded7a from './results'
/**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
export const results = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: results.url(options),
    method: 'get',
})

results.definition = {
    methods: ["get","head"],
    url: '/results',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
results.url = (options?: RouteQueryOptions) => {
    return results.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
results.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: results.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
results.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: results.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
    const resultsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: results.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
        resultsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: results.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PublicResultController::results
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
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
const publicMethod = {
    results: Object.assign(results, results8ded7a),
}

export default publicMethod