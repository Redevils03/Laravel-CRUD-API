<?php

namespace App\Http\Controllers;

use App\Models\book;
use Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class bookController extends Controller
{
    public function index()
    {
        $books = book::orderBy('id')->paginate(10);

        if (Str::contains(url()->current(), 'api')) {
            return new response(true, 'List of Books Data', $books);

        } else {
            return view('welcome', ['books' => $books]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cover'         => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'title'         => 'required',
            'author'        => 'required',
            'pages'         => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails() && Str::contains(url()->current(), 'api')) {
            return response()->json($validator->errors(), 422);
        }

        $cover = $request->file('cover');
        $cover->storeAs('public/books', $cover->hashName());

        $books = book::create([
            'cover'         => $cover->hashName(),
            'title'         => $request->title,
            'author'        => $request->author,
            'pages'         => $request->pages,
            'description'   => $request->description
        ]);

        if (Str::contains(url()->current(), 'api')) {
            return new response(true, 'Data added successfully!', $books);

        } else {
            return redirect()->route('index');
        }
    }

    public function show(book $book)
    {
        return new response(true, 'Data Found!', $book);
    }
    
    public function update(Request $request, book $book)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required',
            'author'        => 'required',
            'pages'         => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $cover->storeAs('public/books', $cover->hashName());

            Storage::delete('public/books/'.$book->cover);
            $book->update([
                'cover'         => $cover->hashName(),
                'title'         => $request->title,
                'author'        => $request->author,
                'pages'         => $request->pages,
                'description'   => $request->description
            ]);

        } else {
            $book->update([
                'title'         => $request->title,
                'author'        => $request->author,
                'pages'         => $request->pages,
                'description'   => $request->description
            ]);
        }

        if (Str::contains(url()->current(), 'api')) { 
            return new response(true, 'Data updated!', $book);

        } else {
            return redirect()->route('index');
        }
    }

    public function destroy(book $book)
    {
        Storage::delete('public/books/'.$book->cover);
        $book->delete();

        if (Str::contains(url()->current(), 'api')) { 
            return new response(true, 'Data deleted!', null);

        } else {
            return redirect()->route('index');
        }
    }
}
