import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
const PublicTimetableController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PublicTimetableController.url(options),
    method: 'get',
})

PublicTimetableController.definition = {
    methods: ["get","head"],
    url: '/timetable',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
PublicTimetableController.url = (options?: RouteQueryOptions) => {
    return PublicTimetableController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
PublicTimetableController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PublicTimetableController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
PublicTimetableController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PublicTimetableController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
    const PublicTimetableControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: PublicTimetableController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
        PublicTimetableControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PublicTimetableController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\PublicTimetableController::__invoke
 * @see app/Http/Controllers/PublicTimetableController.php:10
 * @route '/timetable'
 */
        PublicTimetableControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PublicTimetableController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    PublicTimetableController.form = PublicTimetableControllerForm
export default PublicTimetableController