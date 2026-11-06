import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/results',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PublicResultController::index
 * @see app/Http/Controllers/PublicResultController.php:18
 * @route '/results'
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
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/results/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
    const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: search.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
        searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: search.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PublicResultController::search
 * @see app/Http/Controllers/PublicResultController.php:25
 * @route '/results/search'
 */
        searchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: search.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    search.form = searchForm
const PublicResultController = { index, search }

export default PublicResultController