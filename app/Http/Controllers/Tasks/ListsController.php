<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Lists;
use App\Models\Tasks;
use App\Traits\Helper;
use Illuminate\Http\Request;

class ListsController extends Controller
{
    use Helper;
    public $data = [];

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $this->data['items'] = auth()->user()->hasRole('admin') ? Lists::where($request->only((new Lists())->getFillable()))->with('owner')->paginate(20) : auth()->user()->lists()->with('owner')->where($request->only((new Lists())->getFillable()))->paginate(20);
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('lists.list', $this->data);
    }

    public function postForm(Request $request, Lists $item = null)
    {
        if (is_null($item)) {
            $item = new Lists();
        }
        try {
            $item->autoFill($request);
            $list_users = [
                auth()->user()->id => ['connection' => 'user'],
            ];
            if ($request->users) {
                foreach ($request->users as $u) {
                    $list_users[$u] = [
                        'connection' => 'user',
                    ];
                }
            }
            $item->users()->sync($list_users);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($request->wantsJson()) {
            return response()->json($item);
        }

        return $this->success('List was created.', 'lists');
    }

    public function getForm(Request $request, Lists $item = null)
    {
        $this->data['item'] = is_null($item) ? new Lists() : $item;

        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('lists.form', $this->data);
    }


    public function getTasks(Request $request, Lists $item)
    {
        $this->data['list'] = $item;
        $this->data['items'] = $item->tasks()->with('owner')->orderBy('finished_on')->paginate(20);
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('tasks.list', $this->data);
    }

    public function createTask(Request $request, Lists $item)
    {
        try {
            $task = Tasks::create([
                'title'    => $request->title,
                'owner_id' => auth()->user()->id,
            ]);
            $task->users()->attach([
                auth()->user()->id => ['connection' => 'user'],
            ]);
            $item->tasks()->attach([
                $task->id => ['connection' => 'task'],
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($request->wantsJson()) {
            return response()->json($task);
        }

        return $this->success('Task was created in ' . $item->title);
    }

    public function delete(Request $request, Lists $item)
    {
        if ($item->tasks->count()) {
            $item->delete();
        } else {
            $item->forceDelete();
        }
        if ($request->wantsJson()) {
            return response()->json('Deleted');
        }

        return $this->success('List was deleted.', 'lists');
    }

    public function export(Request $request, $format = 'xls')
    {
        $items = auth()->user()->hasRole('admin') ? Lists::with('owner')->get() : auth()->user()->lists()->with('owner')->get();
        $data = $items->map(function ($item) {
            return [
                'id'          => $item->id,
                'title'       => $item->title,
                'description' => $item->description,
                'tasks'       => $item->tasks->count(),
                'created at'  => $item->created_at->format('d.m.Y'),
                'created by'  => $item->owner->name,
            ];
        })->toArray();
        \App\Services\Export::excel($data, 'Tasks lists', 'Lists');
    }

}
