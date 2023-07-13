<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function index()
    {
        return BookResource::collection(Book::get());
    }

    public function store(Request $request)
    {
        if (!Gate::any(['owner', 'staff'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'date_of_published' => 'required',
            'image' => 'image|file|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 406);
        }
        $image = $request->file('image');
        $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/books'), $imageName);
        $path = public_path('images/books/') . $imageName;
        $book = Book::create([
            'id' => $request->id,
            'name' => $request->name,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'date_of_published' => $request->date_of_published,
            'image' => $imageName,
        ]);
        return new BookResource($book);
    }
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    public function update(Request $request, Book $book)
    {
        // var_dump($request->all());
        if (!Gate::any(['owner', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'date_of_published' => 'required',
            'image' => 'image|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 406);
        }


        if ($request->hasFile("image")) {
            if ($book->image != null) {
                Storage::delete($book->image);
            }
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/books'), $imageName);
            // $path = public_path('images/users/') . $imageName;

            // $book->name = $request->name;
            // $book->author = $request->author;
            // $book->publisher = $request->publisher;
            // $book->date_of_published = $request->date_of_published;
            // $book->image = $image;
            $book->update($request->except("_method"));
            $book->image = $imageName;
            $book->save();
        } else {

            $book->name = $request->name;
            $book->author = $request->author;
            $book->publisher = $request->publisher;
            $book->date_of_published = $request->date_of_published;
            $book->save();
        }
        return new BookResource(Book::find($book->id));
    }
    public function destroy(Book $book)
    {
        if (!Gate::any(['owner', 'manager'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($book == null) {
            return response()->json(["success" => false, 'message' => 'Not found'], 404);
        }
        $book->delete();
        return response()->json(["success" => "deleted"], 204);
    }
}
