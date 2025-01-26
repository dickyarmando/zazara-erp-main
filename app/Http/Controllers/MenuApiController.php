<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuApiController extends Controller
{
    public function save_order(Request $request)
    {
        if( isset($request->order)){
            $orders = json_decode($request->order);
            if( is_array($orders) AND count($orders) > 0 ){
                foreach($orders as $key => $order){
                    $menu = Menu::find($order);
                    $menu->update([ 'ord' => $key + 1 ]);
                }
            }
        }

        //session()->flash('success','Menu saved.');
        return response()->json([ 'success' => '1' ]);
    }

    public function save_parent(Request $request)
    {
        $menu = App\Models\Menu::class;
        $id = $request->id;
        $parent_id = $request->parent_id;
        $menu = Menu::find($id);
        if($menu->parent_id == $parent_id)
        {
            return response()->json([ 'success' => '0' ]);
        }
        else
        {
            $affected = $menu->update([ 'parent_id' => $parent_id ]);
            if($affected){
                return response()->json([ 'success' => '1' ]);
            }else{
                return response()->json([ 'success' => '0' ]);
            }
        }
    }
}
