<?php

namespace Novius\NovaVisualComposer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Novius\NovaVisualComposer\Helpers\Templates;

class TemplateController extends Controller
{
    public function templateCrud(Request $request)
    {
        $templateWanted = (string) $request->get('template', '');

        if (empty($templateWanted)) {
            return response()->json([
                'message' => trans('nova-visual-composer::errors.please_specify_template'),
                'error' => 1,
            ]);
        }

        $templates = Templates::templates();
        $templateDetails = $templates->firstWhere('classname', $templateWanted);

        if (empty($templateDetails)) {
            return response()->json([
                'message' => trans('nova-visual-composer::errors.bad_template'),
                'error' => 1,
            ]);
        }

        $templateClass = $templateDetails['classname'];
        if (!class_exists($templateClass)) {
            throw new \Exception(sprintf('Unavailable visual composer template : class %s does not exists', $templateClass));
        }

        return response()->json([
            'templateHTML' => (string) $templateClass::renderCrud(),
            'rowName' => Templates::template($templateClass)['name_trans'],
            'error' => 0,
        ]);
    }

    public function rowsSummary(Request $request)
    {
        $rows = (string) $request->get('rows', '');

        if (empty($rows)) {
            return response()->json([
                'summaryHTML' => '',
                'error' => 0,
            ]);
        }

        $rows = json_decode($rows, true);
        $summary = '';
        foreach ($rows as $row) {
            $summary .= ($row['template']::details())['name_trans'].'<br>';
        }

        return response()->json([
            'summaryHTML' => $summary,
            'error' => 0,
        ]);
    }
}
