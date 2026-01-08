<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     * Return list kategori.
     */
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'List categories retrieved successfully',
            'data' => $categories,
        ], 200);
    }
    
    /**
     * POST /api/categories
     * Menyimpan kategori baru.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();

        // Validasi ID
        if (empty($data['id'])) {
            return $this->validationErrorResponse('ID must be filled');
        }
        if (strlen($data['id']) > 255) {
            return $this->validationErrorResponse('ID may not be greater than 255 characters.'); 
        }

        // Validasi Name
        if (empty($data['name'])) {
            return $this->validationErrorResponse('Name is Required'); 
        }
        if (strlen($data['name']) > 255) {
           return $this->validationErrorResponse('Name may not be greater than 255 characters.'); 
        }

        // Cek ID Unik
        if (Category::where('id', $data['id'])->exists()) {
           return $this->validationErrorResponse('ID already exists.'); 
        }

        // Pembuatan Slug Otomatis
        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        $category = Category::create([
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'slug' => $slug,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * DELETE /api/categories/{id}
     * Menghapus kategori berdasarkan ID.
     * --- TAMBAHAN BARU ---
     */
    public function destroy($id): JsonResponse
    {
        // Mencari kategori berdasarkan ID
        $category = Category::find($id);

        // Jika data tidak ditemukan
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found.',
            ], 404);
        }

        // Proses hapus
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ], 200);
    }
    
    /**
     * Helper untuk membuat response error validasi (422).
     */
    public function validationErrorResponse($message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $message,
        ], 422);
    }
}