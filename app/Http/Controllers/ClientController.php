<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\RackBooksRequest;
use App\Http\Requests\SearchBookRequest;
use App\Models\User;
use App\Models\Rack;
use App\Models\Book;
class ClientController extends Controller
{
   public function registration(RegistrationRequest $request){
       try{
            $input = $request->validated();

            $client = new User;
            $client->name = $input['name'];
            $client->email = $input['email'];
            $client->password = bcrypt($input['password']);

            $result = $client->save();
            if ($result)
            {
                return response()->success('Successfuly registered!', 200);
            } else {
                return response()->error('Registration failed!', 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
   }

   public function listRacks(Request $request){
       try{
            $rack = Rack::all();

            if($rack){
                return response()->success($rack, 200);
            }else{
                return response()->error("No rack exist", 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }

    public function checkRackBooks(RackBooksRequest $request){
        try{
            $input = $request->validated();

            $rack = Rack::find($input['rack_id']);
            if($rack){
                $books =Book::where('rack_id',$input['rack_id'])->get();
                return response()->success($books, 200);
            }else{
                return response()->error("Rack not exist", 400);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }

    public function searchBook(SearchBookRequest $request){
        try{
            $input = $request->validated();

            $book_rack = Book::where('book_title', $input['search_text'])->orwhere('author', $input['search_text'])->orwhere('published_year',$input['search_text'])->value("rack_id");
            if($book_rack){
                $book = Book::where('rack_id',$book_rack)->get();
                 $rack = Rack::find($book_rack);

                 $data=$book->merge(array('date' => $rack));

                return response()->success($data,200);
            }
        }catch(\Exception $ex){
            return response()->error($ex->getMessage(),400);
        }
    }
}
