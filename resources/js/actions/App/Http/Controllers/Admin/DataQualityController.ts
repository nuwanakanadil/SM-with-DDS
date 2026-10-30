import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
const DataQualityController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DataQualityController.url(options),
    method: 'get',
})

DataQualityController.definition = {
    methods: ["get","head"],
    url: '/admin/data-quality',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
DataQualityController.url = (options?: RouteQueryOptions) => {
    return DataQualityController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
DataQualityController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DataQualityController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
DataQualityController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: DataQualityController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
    const DataQualityControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: DataQualityController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
        DataQualityControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: DataQualityController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\DataQualityController::__invoke
 * @see app/Http/Controllers/Admin/DataQualityController.php:16
 * @route '/admin/data-quality'
 */
        DataQualityControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: DataQualityController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    DataQualityController.form = DataQualityControllerForm
export default DataQualityController