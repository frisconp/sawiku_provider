<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();

        return $this->sendResponse($menus->load('user'), 'Get all menu.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'photo' => ['required', 'image', 'max:1024'],
            'price' => ['required', 'integer', 'max:500000'],
        ], [
            'name.required' => 'Nama menu tidak boleh kosong.',
            'name.max' => 'Nama menu maksimal 120 karakter.',
            'photo.required' => 'Harap pilih foto menu.',
            'photo.max' => 'Ukuran foto maksimal 1MB',
            'price.max' => 'Harga menu maksimal 500 ribu.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Request invalid.', 403);
        }

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->photo = $request->file('photo')->store('menu_photos');
        $menu->price = $request->price;
        $menu->user_id = $request->user()->id;
        $menu->menu_category_id = $request->menu_category_id;
        $menu->save();

        return $this->sendResponse($menu->load('user'), 'Menu created!', 201);
    }

    public function show(Menu $menu)
    {
        if ($menu) {
            return $this->sendResponse($menu->load('user'), 'Get detail menu.');
        } else {
            return $this->sendError(null, 'Menu not found.');
        }
    }
}
