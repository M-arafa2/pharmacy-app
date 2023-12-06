<?php

namespace App\Http\Controllers;

use App\Models\pharmacy;
use App\Models\order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function RevenueCard()
    {
        $role = Auth::user()->role;
        if(Auth::user()->role == 'pharmacy') {
            $phar = pharmacy::where('staff_id', Auth::user()->id)->with('staff')->first();
            $orders = order::where('status', ['Confirmed','Delivered'])->where('pharmacy_id', $phar->id)->get();
            $revenue [] = [
                'pharmacy_name' => $phar->staff->name,
                'pharmacy_image' => $phar->staff->image,
                'count' => $orders->count(),
                'total' => $orders->sum('Total_price') / 100,
            ];
            $females = [];
            $males = [];
            $monthlyLabels = [];
            $monthlyRevenue = [];

        } elseif(Auth::user()->role == 'admin') {
            $pharmacies = pharmacy::with('staff')->get();
            foreach($pharmacies as $phar) {
                $orders = order::whereIn('status', ['Confirmed','Delivered'])->where('pharmacy_id', $phar->id)->get();
                $revenue [] = [
                    'pharmacy_name' => $phar->staff->name,
                    'pharmacy_image' => $phar->staff->image,
                    'count' => $orders->count(),
                    'total' => $orders->sum('Total_price') / 100,
                ];
            }
            /* $month = Carbon::now()->startOfMonth();
             $nwm = Carbon::now()->startOfMonth();
             $monthlyRevenue = [];
             $monthlyLabels = [];
             for($i = 0;$i < 12;$i++) {
                 $sum = order::select('Total_price')->whereIn('status', ['confirmed','Delivered'])
                         ->whereBetween('created_at', [$month->subMonth(),$nwm ])->get()->sum('Total_price');
                 $nwm->subMonth();
                 $monthlyRevenue[$i] = $sum;
                 //$monthlyLabels = [$i] = $nwm->format('M');
             }
             dump($monthlyRevenue);
             dump($monthlyLabels);
             */
            //$monthlyRev = DB::raw('MONTH(created_at) as `month`')->groupby('month')->get();
            $result = order::selectRaw('year(created_at) year, monthname(created_at) month, sum(Total_price) data')
                ->whereIn('status', ['confirmed','Delivered'])
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->get();
            $monthlyRevenue = [];
            $monthlyLabels = [];
            foreach($result as $res) {
                array_push($monthlyLabels, $res->month);
                array_push($monthlyRevenue, $res->data);
            }
            $females = User::where('gender', 'female')->get()->count();
            $males = User::where('gender', 'male')->get()->count();

        } else {
            $revenue = [];
            $females = [];
            $males = [];
            $monthlyLabels = [];
            $monthlyRevenue = [];
        }


        return view('home', compact('revenue', 'role', 'males', 'females', 'monthlyLabels', 'monthlyRevenue'));
    }
}
