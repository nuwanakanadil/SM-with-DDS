import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import students from './students'
import assessments from './assessments'
import results from './results'
import staff from './staff'
/**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\DashboardController::__invoke
 * @see app/Http/Controllers/Admin/DashboardController.php:16
 * @route '/admin'
 */
        dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
export const analysis = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analysis.url(options),
    method: 'get',
})

analysis.definition = {
    methods: ["get","head"],
    url: '/admin/analysis',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
analysis.url = (options?: RouteQueryOptions) => {
    return analysis.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
analysis.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analysis.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
analysis.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: analysis.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
    const analysisForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: analysis.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
        analysisForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analysis.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\AnalysisController::__invoke
 * @see app/Http/Controllers/Admin/AnalysisController.php:14
 * @route '/admin/analysis'
 */
        analysisForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analysis.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    analysis.form = analysisForm
const admin = {
    dashboard: Object.assign(dashboard, dashboard),
analysis: Object.assign(analysis, analysis),
students: Object.assign(students, students),
assessments: Object.assign(assessments, assessments),
results: Object.assign(results, results),
staff: Object.assign(staff, staff),
}

export default admin