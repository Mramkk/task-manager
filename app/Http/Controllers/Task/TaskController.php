<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function list(Request $request, $id)
    {
        try {

            $query = Task::query()
                ->where("project_id", $id)
                ->latest();
            if (!empty($request->search)) {
                $query->where('title', "like", '%' . $request->input('search') . '%');
            }
            if (!empty($request->status)) {
                $query->where("status", $request->status);
            }
            $data = $query->paginate(10);
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => "Failed to retrieve tasks: ",
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }


        // Logic to store a new task
        DB::beginTransaction();
        try {
            Task::updateOrCreate(
                ['id' => $request->id],
                [
                    'project_id' => $request->project_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'due_date' => $request->due_date,
                ]
            );
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Task saved successfully',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => "Failed to save task: " . $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $data = Task::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => "Failed to retrieve task.",
            ]);
        }
    }

    public function status(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,completed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ]);
        }

        // Logic to update the status of a specific task
        DB::beginTransaction();
        try {
            $task = Task::findOrFail($id);
            $task->status = $request->status;
            $task->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Task status updated successfully",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => "Failed to update task status: " . $th->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            Task::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => "Task deleted successfully !",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => "exception",
                'message' => "Error please try again later.",
            ]);
        }
    }
}
