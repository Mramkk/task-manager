<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function list()
    {
        try {
            $data = Project::orderBy('created_at', 'desc')
                ->withCount('tasks')
                ->withCount('pendingTasks')
                ->withCount('completedTasks')
                ->paginate(10);
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "exception",
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'errors' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();

        try {
            Project::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'description' => $request->description,

                ]
            );

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Project save successfully !",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => "exception",
                'message' => "Error saving project",
            ]);
        }
    }
    public function edit($id)
    {
        try {
            $project = Project::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $project,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "exception",
                'message' => "Error fetching project details.",
            ]);
        }
    }

    public function detail($id)
    {
        try {
            $data = Project::findOrFail($id);
            return view('project.detail', compact('data'));
        } catch (\Throwable $th) {
            toast()->error("Error fetching project details.");
            return back();
        }
    }

    public function delete($id)
    {
        try {
            Project::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => "Project deleted successfully !",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => "exception",
                'message' => "Error please try again later.",
            ]);
        }
    }
}
