<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetDefaultWorkspaceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->user()->update([
            'default_workspace_id' => $request->post('workspace_id'),
        ]);

        return response()->noContent();
    }
}
