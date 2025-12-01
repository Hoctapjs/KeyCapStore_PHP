<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Sắp xếp
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'asc':
                $query->orderBy('name', 'asc');
                break;
            case 'desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $brands = $query->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
        ]);

        $brand = new Brand();
        $brand->name = $validated['name'];
        $brand->slug = Str::slug($validated['name']);
        $brand->save();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thương hiệu đã được tạo thành công!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
        ]);

        $brand->name = $validated['name'];
        $brand->slug = Str::slug($validated['name']);
        $brand->save();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thương hiệu đã được cập nhật!');
    }

    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được xóa!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể xóa thương hiệu: ' . $e->getMessage());
        }
    }
}
