<?php

namespace App\Http\Traits;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait ImageTrait
{
    public function singleImageUpload($img, $for, $name)
    {

        $ext = $img->getClientOriginalExtension();
        $name = str_replace(" ", "_", $name);
        $name = uniqid($name . '_', true);
        $img = $img->move("images/" . $for . "s", $name . "_" . time() . "." . $ext);

        return $img;
    }
}