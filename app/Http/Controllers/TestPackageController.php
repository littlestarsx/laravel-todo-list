<?php

namespace App\Http\Controllers;

use Www\Facades\PackageTest;

class TestPackageController extends Controller
{
    public function testPackage()
    {
        PackageTest::returnMessage();
    }

}
