<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Carbon\Carbon;


class TodoController extends Controller
{
    //

    //To create task
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        $task = Task::create([
            'title' => $validatedData['title'],
            'due_date' => $validatedData['due_date'],
        ]);

         return response()->json($task, 201);
    }

    //To soft delete
    public function delete($id){
        $task = Task::find($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    //To mark task and its sub task completed
    public function completeTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // Mark the task as completed
        $task->update(['status' => 'completed']);

        //This is to Mark all related subtasks as completed
        $task->subtasks()->update(['status' => 'completed']);

        return response()->json(['message' => 'Task and related subtasks marked as complete']);
    }

    //To view the pending tasks and related subtasks
    public function viewPendingTasks()
    {
        $pendingTasks = Task::where('status', 'pending')
            ->with('subtasks')
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json(['data' => $pendingTasks]);
    }

    //To Get task by given date
    public function viewTasksByDueDate(Request $request, $filter = null)
    {
        $query = Task::query();

        switch ($filter) {
            case 'today':
                $query->whereDate('due_date', Carbon::today());
                break;
            case 'this_week':
                $query->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'next_week':
                $query->whereBetween('due_date', [Carbon::now()->addWeek()->startOfWeek(), Carbon::now()->addWeek()->endOfWeek()]);
                break;
            case 'overdue':
                $query->where('due_date', '<', Carbon::today());
                break;
            default:
        }

        $tasks = $query->with('subtasks')
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json(['data' => $tasks]);
    }

    //To search in title column
    public function searchTitle( $filter = null)
    {
        $query = Task::query();

        if ($filter) {
            $query->where('title', 'like', '%' . $filter . '%');
        }
        $tasks = $query->orderBy('due_date', 'asc')
            ->get();

        return response()->json(    ['data' => $tasks]);
    }
}
