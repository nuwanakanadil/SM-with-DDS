import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
const AnalysisController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AnalysisController.url(options),
    method: 'get',
})

AnalysisController.definition = {
    methods: ["get","head"],
    url: '/admin/analysis',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
AnalysisController.url = (options?: RouteQueryOptions) => {
    return AnalysisController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
AnalysisController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AnalysisController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
AnalysisController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: AnalysisController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
    const AnalysisControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: AnalysisController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
        AnalysisControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AnalysisController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
        AnalysisControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AnalysisController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    AnalysisController.form = AnalysisControllerForm
export default AnalysisController