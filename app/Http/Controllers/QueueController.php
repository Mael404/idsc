<?php

namespace App\Http\Controllers;

use App\Models\TransanctionWindow;
use Illuminate\Http\Request;
use App\Models\Queue;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    //


    public function saveQueue(Request $request)
    {
        try {
            Queue::whereDate('date_created', '!=', now()->toDateString())
                ->delete();
            $validatedData = $request->validate([
                'transaction_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'purpose' => 'required|string|max:255',
            ]);
            $transactionId = $validatedData['transaction_id'];
            $name = $validatedData['name'];
            $purpose = $validatedData['purpose'];

            $size = Queue::all()->count();
            $queueNumber = ($size >= 9999) ? 1000 : 1000 + $size;
            $newQuueuNo = str_pad($queueNumber, 4, '0', STR_PAD_LEFT);

            $nextQueue = Queue::create([
                'queue_no' => $newQuueuNo,
                'name' => $name,
                'window_id' => 0,
                'status' => 0,
                'transaction_id' => $transactionId,
                'status_trigger' => '0',
                'purpose' => $purpose,
            ]);

            return response()->json(['status' => 1, 'data' => $nextQueue]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }
    public function updateQueue(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'transaction_id' => 'required|integer',
            ]);


            Queue::whereDate('date_created', '!=', now()->toDateString())->delete();

            $transactionId = $validatedData['transaction_id'];


            $window = TransanctionWindow::where('transaction_id', $transactionId)
                ->where('status', 1)
                ->where('user_id', $user->id)
                ->first();

            if (!$window) {
                return response()->json(['status' => 0, 'error' => 'Window not found for this cashier.']);
            }


            $currentQueue = Queue::whereDate('date_created', now()->toDateString())
                ->where('window_id', $window->id)
                ->where('status', 1)
                ->where('status_trigger', 0)
                ->first();

            if ($currentQueue) {
                $currentQueue->status_trigger = '1';
                $currentQueue->save();
            }


            $nextQueue = Queue::whereDate('date_created', now()->toDateString())
                ->where('transaction_id', $transactionId)
                ->where('status', 0)
                ->orderBy('id', 'asc')
                ->first();

            if (!$nextQueue) {
                return response()->json(['status' => 0, 'error' => 'No pending queue.']);
            }


            $nextQueue->status = 1;
            $nextQueue->status_trigger = '0';
            $nextQueue->window_id = $window->id;
            $nextQueue->save();

            return response()->json(['status' => 1, 'data' => $nextQueue]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }


    public function updateRecall($sid, Request $request)
    {
        try {
            $user = Auth::user();

     
            $window = TransanctionWindow::where('user_id', $user->id)->first();
            if (!$window) {
                return response()->json(['status' => 0, 'error' => 'Cashier window not found.']);
            }

         
            $queueItem = Queue::where('id', $sid)
                ->where('window_id', $window->id)
                ->first();

            if (!$queueItem) {
                return response()->json(['status' => 0, 'error' => 'Queue not found or not yours.']);
            }

            $queueItem->status_trigger = '0';
            $queueItem->save();

            return response()->json([
                'status' => 1,
                'data' => [
                    'id' => $queueItem->id,
                    'name' => $queueItem->name,
                    'queue_no' => $queueItem->queue_no,
                    'purpose' => $queueItem->purpose,
                    'wname' => optional($queueItem->window)->name,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }


    public function getLatestQueue(Request $request)
    {
        try {
            Queue::whereDate('date_created', '!=', now()->toDateString())
                ->delete();
            $queueItem = Queue::whereDate('date_created', now()->toDateString())
                ->where('status', 1)
                ->where('status_trigger', '0')
                ->orderBy('id', 'asc')
                ->first();

            if (!$queueItem) {
                return response()->json(['status' => 0, 'error' => 'No queue available.']);
            }
            $queueItem->status_trigger = '1';
            $queueItem->save();
            return response()->json(['status' => 1, 'data' => [
                'name' => $queueItem->name,
                'window_id' => $queueItem->window_id,
                'queue_no' => $queueItem->queue_no,
            ]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'error' => $e->getMessage()]);
        }
    }
}
