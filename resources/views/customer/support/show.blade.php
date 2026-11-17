@extends('layouts.customer')

@section('title', 'Ticket #' . $ticket->ticket_number . ' | BharatTravel')

@section('customer_content')
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('customer.support') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Tickets</a>
            <span class="text-slate-300">•</span>
            <span class="text-xs font-bold text-slate-600 uppercase">#{{ $ticket->ticket_number }}</span>
        </div>
        <h1 class="text-xl font-bold text-slate-900">{{ $ticket->subject }}</h1>
        <p class="text-xs text-slate-500 mt-1">Category: <span class="font-bold text-slate-700">{{ $ticket->category }}</span> &bull; Priority: <span class="font-bold uppercase text-slate-700">{{ $ticket->priority }}</span></p>
    </div>
    <span class="px-3 py-1 rounded-full text-xs font-bold 
        {{ $ticket->status == 'open' ? 'bg-amber-100 text-amber-800' : ($ticket->status == 'resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
        {{ ucfirst($ticket->status) }}
    </span>
</div>

<!-- Message History -->
<div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-6">
    <div class="space-y-4">
        @foreach($ticket->messages as $msg)
            <div class="p-4 rounded-xl border {{ $msg->user_id === Auth::id() ? 'bg-slate-50 border-slate-200 ml-6' : 'bg-teal-50/60 border-teal-100 mr-6' }}">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="font-bold {{ $msg->user_id === Auth::id() ? 'text-slate-900' : 'text-teal-900' }}">
                        {{ $msg->user ? $msg->user->name : 'Support Agent' }}
                    </span>
                    <span class="text-[11px] text-slate-400">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
            </div>
        @endforeach
    </div>

    <!-- Reply Box -->
    <div class="pt-6 border-t border-slate-100">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Add Response / Message</h4>
        <form action="{{ route('customer.support.reply', $ticket->id) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="message" rows="3" required
                      class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 text-xs"
                      placeholder="Type your response here..."></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center gap-2">
                    <i data-lucide="send" class="w-3.5 h-3.5"></i> Send Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

