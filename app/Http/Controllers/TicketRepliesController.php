<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketReplies;
use Illuminate\Support\Facades\Validator;

class TicketRepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['addReply']]);
    }

    public function addReply(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|max:250|min:4',
            ]);
            if ($validator->fails()) {
                $error = $validator->getMessageBag()->getMessages();
                return response()->json([
                    'success' => false,
                    'Error' => $error
                ]);
            }
            //Create a reply to ticket and send a success message 
            $reply = new TicketReplies;
            $reply->ticket_id = $id;
            $reply->content = $request->input('content');

            $reply->save();
            return response()->json(
                array(
                    'success' => true,
                    'message' => 'Reply added ',
                    'created_reply' => $reply
                ),
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
