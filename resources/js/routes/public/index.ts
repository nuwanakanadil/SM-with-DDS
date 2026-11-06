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
/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
export const timetable = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: timetable.url(options),
    method: 'get',
})

timetable.definition = {
    methods: ["get","head"],
    url: '/timetable',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
timetable.url = (options?: RouteQueryOptions) => {
    return timetable.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
timetable.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: timetable.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
timetable.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: timetable.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
    const timetableForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: timetable.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
        timetableForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: timetable.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
        timetableForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: timetable.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    timetable.form = timetableForm
const publicMethod = {
    results: Object.assign(results, results8ded7a),
timetable: Object.assign(timetable, timetable),
}

export default publicMethod