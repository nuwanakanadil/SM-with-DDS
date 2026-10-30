import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
const MyResultController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyResultController.url(options),
    method: 'get',
})

MyResultController.definition = {
    methods: ["get","head"],
    url: '/my-results',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
MyResultController.url = (options?: RouteQueryOptions) => {
    return MyResultController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
MyResultController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: MyResultController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
MyResultController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: MyResultController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
    const MyResultControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: MyResultController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
        MyResultControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: MyResultController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\MyResultController::__invoke
 * @see app/Http/Controllers/Student/MyResultController.php:11
 * @route '/my-results'
 */
        MyResultControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: MyResultController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    MyResultController.form = MyResultControllerForm
export default MyResultController