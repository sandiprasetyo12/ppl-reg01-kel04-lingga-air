<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanFeedController extends Controller
{
    public function index(): View
    {
        $pengumumans = Pengumuman::activePublished()
            ->paginate(10);

        return view('pengumuman.index', compact('pengumumans'));
    }
}
