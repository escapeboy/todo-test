<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Lists;
use App\Models\Tasks;
use App\Traits\Helper;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    use Helper;
    public $data = [];

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $this->data['items'] = auth()->user()->hasRole('admin') ? Tasks::where($request->only((new Lists())->getFillable()))->orderBy('finished_on')->paginate(20) : auth()->user()->tasks()->where($request->only((new Lists())->getFillable()))->orderBy('finished_on')->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('tasks.list', $this->data);
    }

    public function postForm(Request $request, Tasks $item = null)
    {
        if (is_null($item)) {
            $item = new Tasks();
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

        return $this->success('Task was saved.');
    }

    public function markTask(Request $request, Tasks $item, $status = 'finished')
    {
        if ($status == 'finished') {
            $item->update(['finished_on' => \Carbon\Carbon::now()]);
        } elseif ($status == 'in_progress') {
            $item->update(['finished_on' => null]);
        }

        if ($request->wantsJson()) {
            return response()->json('ok');
        }

        return $this->success('Task marked as "' . $status . '". ');
    }

    public function getForm(Request $request, Tasks $item = null)
    {
        $this->data['item'] = is_null($item) ? new Tasks() : $item;
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('tasks.form', $this->data);
    }

    public function delete(Request $request, Tasks $item)
    {
        $item->delete();
        if ($request->wantsJson()) {
            return response()->json('Deleted');
        }

        return $this->success('Task was deleted.');
    }

    public function export(Request $request, $format = 'xls')
    {
        $items = auth()->user()->hasRole('admin') ? Tasks::with('owner')->get() : auth()->user()->tasks()->with('owner')->get();
        if ($request->has('list')) {
            $list = Lists::find($request->list);
            if ($list) {
                $list_tasks = $list->tasks->pluck('id')->toArray();
                $items = $items->filter(function ($item) use ($list_tasks) {
                    return in_array($item->id, $list_tasks);
                });
            } else {
                abort(400, 'List not found!');
            }
        }
        $data = $items->map(function ($item) {
            return [
                'id'          => $item->id,
                'title'       => $item->title,
                'description' => $item->description,
                'due date'    => $item->due_date ? $item->due_date->format('d.m.Y') : '-',
                'list'        => $item->lists->count() ? $item->lists->first()->title : '-',
                'created at'  => $item->created_at->format('d.m.Y'),
                'created by'  => $item->owner->name,
            ];
        })->toArray();
        \App\Services\Export::excel($data, 'Tasks', 'List of tasks');
    }

}
