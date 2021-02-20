<?php
   
namespace App\Http\Controllers\API\V1;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use App\Models\TodoList;
use Validator;
use App\Http\Resources\TodoList as TodoListResource;
use Auth;
   
class TodoListController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;

        //$todolist = TodoList::where('user_id',$user_id)->get();
        $todolist = TodoList::where('user_id',$user_id)->paginate(10);
        
        return $this->sendResponse($todolist, 'Todo list');
        //return response(['todos' => TodoListResource::collection($todolist), 'status' => 200, 'message' => 'Todolist successfully retrived.' ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::user()->id;
   
        $validator = Validator::make($input, [
            'body' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $todoitem = TodoList::create(['body' =>$input['body'],'user_id' => $user_id]);
   
        //return response(['todo' => $todoitem, 'status' => 200, 'message' => 'Todo Item created successfully.' ]);
        return $this->sendResponse($todoitem, 'Todo Item created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todoitem = TodoList::find($id);
  
        if (is_null($todoitem)) {
            return $this->sendError('Todoitem not found.');
        }
   
        return response(['Todos' => new TodoListResource($todoitem), 'status' => 200, 'message' => 'Todo item retrieved successfully.']);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $todo = TodoList::findOrFail($id);

        $request = array_filter($request->all());


        $todo->update($request);

        return $this->sendResponse($todo, 'User Information has been updated');
        
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $todoitem = TodoList::findOrFail($id);
        if (is_null($todoitem)) {
            return $this->sendError('Todoitem not found.');
        }
        $todoitem->delete();

        //return response(['message' => 'Deleted successfully' , 'status' => 204]);
        return $this->sendResponse([$todoitem], 'Todoitem has been Deleted');
   
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, $id)
    {
        $todo = TodoList::findOrFail($id);

        $todo->is_complete = 1;
        $todo->save();

        return $this->sendResponse($todo, 'Todo is marked as completed');
    }
}