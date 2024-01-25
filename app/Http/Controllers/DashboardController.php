<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Branch;
use App\Models\Purchase;
use App\Models\Pengeluaran;
use App\Models\Sales;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $category = Category::count();
        $product = Product::count();
        $supplier = Supplier::count();
        $branch = Branch::count();
        $purchase = format_uang(Purchase::sum('total_pay'));
        $sales = format_uang(Sales::sum('change'));
        $pengeluaran = format_uang(Pengeluaran::sum('nominal'));

        $date1 = date('Y-m-01');
        $date2 = date('Y-m-d');

        $data1 = array();
        $data2 = array();

        while (strtotime($date1) <= strtotime($date2)) {
            $data1[] = (int) substr($date1, 8, 2);

            $total_sales = Sales::where('created_at', 'LIKE', "%$date1%")->sum('total_pay');
            $total_purchase = Purchase::where('created_at', 'LIKE', "%$date1%")->sum('total_pay');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$date1%")->sum('nominal');

            $sales1 = $total_sales - $total_purchase - $total_pengeluaran;
            $data2[] += $sales1;

            $date1 = date('Y-m-d', strtotime("+1 day", strtotime($date1)));
        }

        $date1 = date('Y-m-01');

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact('category', 'product', 'supplier', 'branch', 'sales', 'pengeluaran', 'purchase', 'date1', 'date2', 'data1', 'data2'));
        } else {
            return view('kasir.dashboard');
        }
    }
}
