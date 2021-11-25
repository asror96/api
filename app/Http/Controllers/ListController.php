<?php

namespace App\Http\Controllers;

use App\Events\CreateListEvent;
use App\Models\TaskList;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Auth;
use Illuminate\Support\Has;
use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;
use PhpParser\Node\Expr\New_;
use function PHPUnit\Framework\at;

class ListController extends Controller
{
    public function __construct()
    {
    }

    public function create(Request $request)
    {
        $this -> validate($request,[
            'attributes.name' => 'bail|required|string|between:1,255|unique:lists,name',
            'attributes.is_completed' => 'required|boolean',
            'attributes.is_closed' => 'required|boolean'
        ]);
        $attributes=$request -> get("attributes");
        if($attributes==null){
            return response()-> json([],422);
        }
        else{
            if($attributes["count_tasks"]>5||$attributes["count_tasks"]<1){
                $attributes["count_tasks"]=0;
            }
            TaskList::create($attributes);
        }
        return response()-> json(["data"=>["attributes"=>$attributes], 'massage' => 'Created!'],201);

    }

    public function getItems(Request $request)
    {
        $list = null;
        $filters = $request -> get("filter");
        $orders = $request -> get("order");
        $withs = $request -> get("withs");
        $per_page = $request -> get("per_page");
        $page = $request -> get("page");

        if($filters->isNotEmpty()){
            for ($i = 0; $i < count($filters); $i++) {
                if ($filters [$i] [1] == "=") {
                    $list = TaskList::where($filters[$i][0],$filters[$i][2])-> get();
                }
                else if($filters [$i] [1]=="<"||$filters [$i] [1]==">"||$filters [$i] [1]=="<="||$filters [$i] [1]==">="||$filters [$i] [1]=="!="){
                    $list = TaskList::where($filters[$i][0],$filters[$i][1],$filters[$i][2])-> get();
                }
                else{
                    return response() -> json(["data" => ["attributes" => '',"message" => "Received!"]],401);
                }
            }
        }
        else{
            return response()->json(["data" => null,"message" => "Received!"],422);
        }
        if  (!isset($orders)){
            for( $i = 0; $i < count($orders); $i++){
                if ($orders[$i][1] == "asc"){
                    $list = $list -> sortBy($orders[$i][0]);
                }
                else{
                    $list = $list -> sortByDesc($orders[$i][0]);
                }
            }
        }
        if(!isset($withs)){

        }
        $list=$list -> chunk($per_page);
        return response() -> json(["data" => ["attributes" => $list[$page-1]],"message" => "Received!"]);
    }

    public function getItem(Request $request,int $id){

        if($id != null){
            $attribute = TaskList::find($id);
            $withs = $request -> collect("withs");
            foreach ($withs as $with){
                if (isset ($attribute -> $with)) $attribute[$with] = $attribute -> $with;
            }
            return response() -> json(["data" => ["attributes" => $attribute],"message" => "Received!"]);
        }
        else{
            return response() -> json(["data" => null,"message" => "Task`s not found"],404);
        }
    }

    public function update (Request $request,$id)
    {
        $this -> validate($request,[
            'attributes.name' => 'bail|required|string|between:1,255|unique:lists,name',
            'attributes.count_tasks' => 'required|integer',
            'attributes.is_completed' => 'required|boolean',
            'attributes.is_closed' => 'required|boolean'
        ]);
        $attribute = $request -> get("attributes");
        $list = TaskList::find ($id);
        $list -> name = $attribute['name'];
        $list -> count_tasks = $attribute['count_tasks'];
        $list -> is_completed = $attribute['is_completed'];
        $list -> is_closed = $attribute['is_closed'];
        $list -> save();
        return response() -> json(["data" => $attribute,"massage" => "Update!"]);
    }

    public function delete(Request $req,int $id)
    {
        TaskList::where('id',$id) -> delete();
        //event(new DeleteListEvent());
        return response()-> json(["data" => null,"massage" => "Delete!"]);
    }
}
