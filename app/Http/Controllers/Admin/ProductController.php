<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Validators\ValidationException;

class ProductController extends Controller
{
    // ... các phương thức khác ...

    /**
     * Import sản phẩm từ Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('admin.products.index')
                ->with('success', 'Nhập sản phẩm thành công.');
                
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            
            foreach ($failures as $failure) {
                $errorMessages[] = "Dòng {$failure->row()}: {$failure->errors()[0]}";
            }
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Nhập thất bại: ' . implode(', ', $errorMessages));
                
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Nhập thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Xuất sản phẩm ra Excel
     */
    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}