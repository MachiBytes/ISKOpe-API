<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $items = $category
            ? Item::where('category', $category)->get()
            : Item::all();

        return response()->json([
            'message' => $category
                ? "Items successfully retrieved for category: $category."
                : "All items successfully retrieved.",
            'items' => $items
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'status' => 'required|in:lost,found',
                'category' => 'required|in:electronics,wallets,bags,ids,documents,books,clothing,fashion,jewelry,others',
                'date' => 'required|date_format:F d Y',
                'time' => 'nullable|date_format:h:i A',
                'last_seen' => 'required|string',
                'pickup_point' => 'nullable|string',
                'file' => 'required|file|mimes:jpg,png|max:10240',
            ]);
            
    
            $date = Carbon::createFromFormat('F d Y', $validatedData['date'])->format('Y-m-d');
            $time = isset($validatedData['time']) ? Carbon::createFromFormat('h:i A', $validatedData['time'])->format('H:i:s') : null;
    
    
            $fileController = new FileController();
            $uploadResponse = $fileController->upload($request);
            $uploadData = $uploadResponse->getData();
    
            if ($uploadResponse->status() !== 200) {
                return response()->json(['error' => 'Failed to upload image.'], 400);
            }
    
            $imageUrl = $uploadData->url;
    
            $item = new Item();
            $item->name = $validatedData['name'];
            $item->status = $validatedData['status'];
            $item->category = $validatedData['category'];
            $item->date_status = $date;
            $item->time_status = $time;
            $item->place_last_seen = $validatedData['last_seen'];
            $item->pickup_point = $validatedData['pickup_point'] ?? null;
            $item->image_url = $imageUrl;
            $item->save();
    
    
            return response()->json([
                'message' => 'Item successfully stored.',
                'item' => $item
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'error' => 'Validation failed.',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()  // Optional: Return the trace for debugging
            ], 500);
        }
    }


    public function get($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Item retrieved successfully.',
            'item' => $item
        ], 200);
    }

    public function delete($id)
    {
        // Find the item by ID
        $item = Item::find($id);

        // Check if the item exists
        if (!$item) {
            return response()->json([
                'message' => 'Item not found.'
            ], 404);
        }

        // Delete the item
        $item->delete();

        // Return a success response
        return response()->json([
            'message' => 'Item deleted successfully.'
        ], 200);
    }

    public function recent()
    {
        $items = Item::orderBy('created_at', 'desc')->take(5)->get();

        if ($items->isEmpty()) {
            return response()->json([
                'message' => 'No items found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Recent items retrieved successfully.',
            'items' => $items
        ], 200);
    }

}
