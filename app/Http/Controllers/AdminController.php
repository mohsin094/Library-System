<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Http\Requests\AddRackRequest;
use App\Http\Requests\RemoveRackRequest;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Authentication;
use App\Models\User;
use App\Models\Rack;
use App\Models\Book;

class AdminController extends Controller
{
    //admin login
    public function login(LoginRequest $request){
        try{
            $input = $request->validated();

            if ($user = Auth::attempt(['email' => $input['email'], 'password' => $input['password'], 'role' => $input['role']])) {
                $user = auth()->user();

                $authentication = new Authentication($user);
                $jwt = $authentication->getToken();
                $user->remember_token = $jwt;
                User::where("email", $user->email)->update(["remember_token" => $jwt]);
                    $success = [
                        "status" => "success",
                        "token" => $jwt,
                        "data" => $user,
                    ];
                    return response()->success($success, 200);
            }else{
                return response()->error('Wrong credential!', 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }


    //Rack manage
    public function addRack (AddRackRequest $request){
        try{
            $input = $request->validated();

            $rack = new Rack;
            $rack->rack_name = $input['rack_name'];
            if($rack->save()){
                return response()->success("Rack added successfully", 200);
            }else{
                return response()->error("Error", 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }

    public function removeRack(RemoveRackRequest $request){
        try{
            $input = $request->validated();

            $rack = Rack::find($input['rack_id']);
            if($rack){
                $rack->delete();
                return response()->success("Rack removed successfully", 200);
            }else{
                return response()->error("Error in removing rack", 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }

    //Books manage
    public function addBook(AddBookRequest $request){
        try{
            $input = $request->validated();

            $rack = Rack::find($input['rack_id']);
            if($rack){
                if($rack['total_books'] < 10){
                    $book = new Book;
                    $book->book_title = $input['book_title'];
                    $book->author = $input['author'];
                    $book->published_year = $input['published_year'];

                    $result = $rack->books()->save($book);
                    if($result){
                        Rack::where('id', $input['rack_id'])->increment('total_books');
                        return response()->success("Book added", 200);
                    }
                }else{
                    return response()->error("Rack is full", 400);
                }
            }else{
                return response()->error("Rack not exist", 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }
}
