<?php

namespace Modules\ChatManager\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ChatManager\Entities\Message;
use Modules\ChatManager\Notifications\MessageSentNotification;

class ChatManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $id = null)
    {
        if (!auth()->user()->can('access', 'chats visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = User::where('id', '!=',  auth()->id());
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', 0)
                ->orWhereHas('company', function($q){
                    $q->where('id', auth()->id());
                });
            }else if(auth()->user()->isRole('Supplier')){
                $query->where('id', auth()->user()->company_id)
                ->orWhereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                })
                ->whereHas('roles', function($q){
                    $q->where('name', '!=', 'Supplier');
                });  
            }else{
                $query->where('id', auth()->user()->company_id)
                ->orWhereHas('company', function($q){
                    $q->where('id', auth()->user()->company_id);
                });
            }
        }else{
            $query->whereNull('company_id');
        }
        $users = $query->where('status', 1)->get(['id', 'full_name', 'avatar', 'created_at']);
        
        if($id){
            $receiver  = User::findOrFail($id);
            $conversations = Message::where(function($q) use($receiver){
                                        $q->where('sender_id', auth()->id())
                                            ->where('receiver_id', $receiver->id);
                                    })
                                    ->orWhere(function($q) use($receiver){
                                        $q->where('sender_id', $receiver->id)
                                            ->where('receiver_id', auth()->id());
                                    })
                                    ->take(100)
                                    ->orderBy('id', 'ASC')
                                    ->get();

            auth()->user()->unreadNotifications->markAsRead();
            
            return view('chatmanager::index', compact('users', 'receiver', 'conversations'));
        }

        return view('chatmanager::index', compact('users'));
    }

    /**
     * Send message to user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request){
        if($request->ajax()){
            $user = User::find($request->receiver_id);
            if($user){
                try{
                    \DB::beginTransaction();
                    $message                = new Message();
                    $message->sender_id     = auth()->id();
                    $message->receiver_id   = $user->id;
                    $message->message       = $request->message;
        
                    $message->save();
        
                    \DB::commit();
        
                    $user->notify((new MessageSentNotification($message)));

                    $html = '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix">';
                    $html .= '<span class="direct-chat-name float-left">'.$message->sender->full_name.'</span>';
                    $html .= '<span class="direct-chat-timestamp float-right">'.$message->created_at->diffForHumans().'</span></div>';
                    if(\Storage::disk('public')->has($message->sender->avatar)){
                        $html .= '<img class="direct-chat-img" src="'.asset('storage/'.$message->sender->avatar).'" alt="" >';
                    }else{
                        $html .= '<img class="direct-chat-img" src="'.asset('images/no-img-100x92.jpg').'" alt="" >';
                    }
                    $html .= '<div class="direct-chat-text">'.$message->message.'</div></div>';

                    return response()->json([
                            'status'=> 'success',
                            'message'=> 'Message has been sent successfully.',
                            'html'=> $html
                        ]);
        
                }catch(\Exception $e){
                    \DB::rollBack();
                }
            }
        }
        
        return response()->json([
            'status'=> 'error',
            'message'=> 'Somthing went wrong. Please try again later.'
            ]);
    }

    /**
     * Get the all noticiations.
     * @param Request $request
     * @return Response
     */
    public function getNotifications(Request $request){
        $view = "";
        if($request->ajax()){
            $view = view('components.notifications')->render();
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get the all contacts.
     * @param Request $request
     * @return Response
     */
    public function getContacts(Request $request){
        $view = "";
        if($request->ajax()){
            $keyword = $request->query('keyword');
            $query = User::where('id', '!=',  auth()->id());
            if(!auth()->user()->isRole('Super Admin')){
                if(auth()->user()->isRole('Admin')){
                    $query->where('company_id', 0)
                    ->orWhereHas('company', function($q){
                        $q->where('id', auth()->id());
                    });
                }else if(auth()->user()->isRole('Supplier')){
                    $query->where('id', auth()->user()->company_id)
                    ->orWhereHas('company', function($q){
                        $q->where('id', auth()->user()->company_id);
                    })
                    ->whereHas('roles', function($q){
                        $q->where('name', '!=', 'Supplier');
                    });  
                }else{
                    $query->where('id', auth()->user()->company_id)
                    ->orWhereHas('company', function($q){
                        $q->where('id', auth()->user()->company_id);
                    });
                }
            }else{
                $query->whereNull('company_id');
            }
            $query->when($keyword, function($q) use($keyword){
                $q->where('full_name', 'like', '%'.$keyword.'%');
            });

            $users = $query->where('status', 1)->get(['id', 'full_name', 'avatar', 'created_at']);
            $view = view('chatmanager::contacts', compact('users'))->render();
        }
        return response()->json(['html'=> $view]);
    }
}
