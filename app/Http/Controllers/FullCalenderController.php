<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
    	if($request->ajax())
    	{
    		$data = Event::whereDate('start', '>=', $request->start)
                       ->whereDate('end',   '<=', $request->end)
                       ->get(['id', 'color', 'title', 'start', 'end']);
            return response()->json($data);
    	}

    }

    public function action(Request $request)
    {
    	if($request->ajax())
    	{
    		if($request->type == 'add')
    		{//print_r($request->all());die;
                $users = '';
                if($request->users != ''){
                    $users = json_encode(explode(',',$request->users));
                }
    			$event = Event::create([
    				'title'		    =>	$request->title,
					'color'		    =>	$request->color,
    				'start'		    =>	date('Y-m-d H:i:s', strtotime($request->start)),
    				'end'		    =>	date('Y-m-d H:i:s', strtotime($request->end)),
                    'description'	=>	$request->description,
                    'referenceto'	=>	$request->referenceto,
                    'users'		    =>	$users,
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'update')
    		{
    			$event = Event::find($request->id)->update([
    				'title'		=>	$request->title,
					'color'		=>	$request->color,
    				'start'		=>	date('Y-m-d H:i:s', strtotime($request->start)),
    				'end'		=>	date('Y-m-d H:i:s', strtotime($request->start))
    			]);

    			return response()->json($event);
    		}

    		if($request->type == 'delete')
    		{
    			$event = Event::find($request->id)->delete();

    			return response()->json($event);
    		}
    	}
    }
}
