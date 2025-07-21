<?php

namespace App\Http\Controllers;

use App\Models\RefRegion;
use App\Models\RefProvince;
use App\Models\RefCityMun;
use App\Models\RefBrgy;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        // Fetch all regions with their related provinces, cities, and barangays
        $regions = RefRegion::with('provinces.cities.barangays')->get();

        // Pass the data to the view
        return view('registrar.enrollment.enrollment', compact('regions'));
    }


    public function getProvinces($regionCode)
    {
        $provinces = RefProvince::where('regCode', $regionCode)->get();
        return response()->json($provinces);
    }

    public function getCities($provinceCode)
    {
        $cities = RefCityMun::where('provCode', $provinceCode)->get();
        return response()->json($cities);
    }

    public function getBarangays($citymunCode)
    {
        $barangays = RefBrgy::where('citymunCode', $citymunCode)->get();
        return response()->json($barangays);
    }
}
