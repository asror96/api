<?php

namespace App\Http\Controllers;
use App\Events\CreatTaskEvent;
use App\Events\DeleteTaskEvent;
use App\Events\UpdateTaskEvent;
use App\Models\Task;
use App\Models\TaskList;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facedes\Auth;
use Illuminate\Support\Facedes\Hash;

class TaskController extends Controller
{
    public function __construct()
    {

    }

    public function create(Request $request)
    {
        $this -> validate($request,[
            'attributes.name' => 'bail|required|string|between:1,255',
            'attributes.list_id' => 'required|integer|exists:lists,id',
            'attributes.executor_user_id' => 'integer|exists:users,id',
            'attributes.urgency' => 'required|integer|between:1,5',
            'attributes.description' => 'string',
            'attributes.is_completed' => 'required|boolean'
        ]);
        $attributes = $request-> get("attributes");
        $task = new Task();
        $task -> create($attributes);
        event(new CreatTaskEvent($attributes["list_id"]));
        return response() -> json
        ([
            "data"=>[
                "attributes"=>$attributes
            ],
            'massage' => 'Created!'
        ],201);
    }

    public function getItems(Request $request)
    {
        $task =null;
        $filters = $request-> get("filter");
        $orders = $request-> get("order");
        $withs = $request-> get("withs");
        $per_page=$request-> get("per_page");
        $page=$request-> get("page");

        if(!isset($filters)){
            for ($i = 0; $i < count($filters); $i++) {
                if ($filters [$i] [1] == "=") {
                    $task = Task::where($filters[$i][0],$filters[$i][2])->get();
                }
                else if($filters [$i] [1]=="<"||$filters [$i] [1]==">"||$filters [$i] [1]=="<="||$filters [$i] [1]==">="||$filters [$i] [1]=="!="){
                    $task = Task::where($filters[$i][0],$filters[$i][1],$filters[$i][2])->get();
                }
                else{
                    $task = Task::where($filters[$i][0],$filters[$i][1],$filters[$i][2])-> get();
                }
            }
        }
        else{
            return response()->json(["data" => null,"message" => "Received!"],422);
        }


        if (!isset($orders)) {
            for ($i = 0; $i < count($orders);$i++){
                if ($orders[$i][1] == "asc"){
                    $task = $task -> sortBy($orders[$i][0]);
                }
                else{
                    $task = $task -> sortByDesc($orders[$i][0]);
                }
            }
        }

        if(!isset($withs)){

        }
        $task = $task -> chunk($per_page);
        return response()->json(["data" => ["attributes" => $task[$page-1]],"message" => "Received!"]);
    }

    public function getItem(Request $request,int $id)
    {
        if( $id != null){
            $attribute = Task::find($id);
            $withs = $request->collect("withs");
            foreach ($withs as $with){
               if(isset($attribute -> $with))$attribute[$with]=$attribute->$with;
            }
            return response()->json(["data"=>["attributes"=>$attribute],"message"=>"Received!"],201);
        }
        else{
            return response()->json(["data"=>null,"message"=>"Task`s not found"],404);
        }
    }

    public function update(Request $request,$id)
    {
        $attribute=$request->get("attributes");
        $this->validate($request,[
            'attributes.name' => 'bail|required|string|between:1,255',
            'attributes.list_id' => 'required|integer|exists:lists,id',
            'attributes.executor_user_id' => 'integer|exists:users,id',
            'attributes.urgency' => 'required|integer|between:1,5',
            'attributes.description' => 'string',
            'attributes.is_completed' => 'required|boolean'
        ]);
        $task = Task::find($id);
        $task ->name = $attribute['name'];
        $task->list_id=$attribute['list_id'];
        $task->executor_user_id=$attribute['executor_user_id'];
        $task->is_completed=$attribute['is_completed'];
        $task->description=$attribute['description'];
        $task->urgency=$attribute['urgency'];
        $task-> save();
        event(new UpdateTaskEvent($attribute['list_id']));
        return response() -> json(["data" => $attribute,"massage" => "Update!"]);
    }

    public function delete($id)
    {
       //$list_id = Task:: where('id',$id)-> value('list_id');
        Task::where('id',$id) -> delete();
        //event(new  DeleteTaskEvent($list_id));
        return response() -> json(["data" => null,"massage" => "Delete!"]);
    }
}
