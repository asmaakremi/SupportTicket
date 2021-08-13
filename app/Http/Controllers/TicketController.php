<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['update', 'store']]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|max:255',
            'subject' => 'required|max:250|min:4'
        ]);
        if ($validator->fails()) {
            $error = $validator->getMessageBag()->getMessages();
            return response()->json(['Error' => $error]);
        }

        try {
            $ticket = new Ticket;
            $ticket->category = $request->input('category');
            $ticket->subject = $request->input('subject');
            $ticket->description = $request->input('description');
            $ticket->user_id = auth()->user()->id;
            $ticket->status = 'pending';

            $ticket->save();

            return response()->json(array('success' => true, 'message' => 'Ticket added', 'created_ticket' => $ticket), 200);
        } catch (\Exception $exception) {
            return response([
                'success' => false, 'message' => $exception->getMessage()
            ]);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::find($id);

            $validator = Validator::make($request->all(), [
                'category' => 'required|max:100|min:4',
                'subject' => 'required|max:250|min:4'
            ]);
            if ($validator->fails()) {
                $error = $validator->getMessageBag()->getMessages();
                return response()->json([
                    'success' => false, 
                    'Error' => $error]);
            }


            $ticket->category = $request->input('category');
            $ticket->subject = $request->input('subject');
            $ticket->description = $request->input('description');
            $ticket->save();
            return response()->json(array(
                'success' => true, 
                'message' => 'Ticket updated',
                'updated_ticket' => $ticket),
                 200
            );
        } catch (\Exception $exception) {
            return response([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                $error = $validator->getMessageBag()->getMessages();
                return response()->json([
                    'success' => false,
                     'Error' => $error
                    ]);
            }
            if ($request->input('status') != 'resolved' and $request->input('status') != 'declined') {
                return response()->json([
                    'success' => false,
                     'message' => 'status must be resolved or declined'
                    ]);
            }
            $ticket = Ticket::find($id);
            $ticket->status = $request->input('status');
            $ticket->save();
            return response()->json(array(
                'success' => true, 
                'message' => 'Status Ticket updated', 
                'updated_ticket' => $ticket),
                 200
                );
        } catch (\Exception $exception) {
            return response([
                'success' => false, 
                'message' => $exception->getMessage()
            ]);
        }
    }
}
