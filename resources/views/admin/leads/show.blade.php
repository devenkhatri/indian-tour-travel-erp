@extends('layouts.admin')

@section('title', 'Lead Dossier: ' . $lead->lead_number)

@section('content')
<div class="space-y-6">
    <!-- Dossier Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.leads.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">&larr; Back to Pipeline</a>
                <span class="text-slate-300">•</span>
                <span class="text-xs font-bold text-amber-600 uppercase">#{{ $lead->lead_number }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-xs text-slate-500">{{ $lead->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-slate-900">{{ $lead->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Target: <span class="font-bold text-slate-800">{{ $lead->destination }}</span>
                &bull; Phone: <span class="font-bold text-slate-800">{{ $lead->phone }}</span>
                @if($lead->email) &bull; Email: <span class="text-slate-700">{{ $lead->email }}</span> @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(! $lead->customer_id)
                <form action="{{ route('admin.leads.convert.customer', $lead->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Convert to Customer
                    </button>
                </form>
            @else
                <a href="{{ route('admin.customers.show', $lead->customer_id) }}" class="px-3.5 py-2 bg-teal-50 border border-teal-200 text-teal-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i> View Customer Profile
                </a>
            @endif

            <a href="{{ route('admin.quotations.create', ['lead_id' => $lead->id]) }}" class="px-3.5 py-2 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <i data-lucide="file-plus" class="w-3.5 h-3.5"></i> Generate Quotation
            </a>
        </div>
    </div>

    <!-- Pipeline Stages Visualizer -->
    @php
        $stages = ['new' => 'New Lead', 'contacted' => 'Contacted', 'proposal_sent' => 'Proposal Sent', 'negotiation' => 'Negotiation', 'converted' => 'Won / Converted'];
        $currentIdx = array_search($lead->status, array_keys($stages));
        if ($currentIdx === false) $currentIdx = 0;
    @endphp
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-center text-xs">
            @foreach($stages as $key => $label)
                @php
                    $idx = array_search($key, array_keys($stages));
                    $isPassed = $idx <= $currentIdx && $lead->status !== 'lost';
                    $isCurrent = $key === $lead->status;
                @endphp
                <div class="p-2.5 rounded-xl border {{ $isCurrent ? 'bg-amber-500 text-slate-950 font-bold border-amber-500 shadow-xs' : ($isPassed ? 'bg-emerald-50 text-emerald-800 font-semibold border-emerald-200' : 'bg-slate-50 text-slate-400 border-slate-200') }}">
                    <p class="text-[10px] uppercase font-bold tracking-wider">Step {{ $idx + 1 }}</p>
                    <p class="mt-0.5">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main 3-Column Work Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Column 1: Details & Status Update -->
        <div class="space-y-6">
            <!-- Prospect Meta Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-500"></i> Lead Overview
                </h3>

                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Status:</span>
                    <span class="font-bold text-slate-800 uppercase">{{ $lead->status }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Priority:</span>
                    <span class="font-bold uppercase text-rose-600">{{ $lead->priority }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Assigned To:</span>
                    <span class="font-bold text-slate-800">{{ $lead->assignedEmployee?->name ?? 'Unassigned' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Passengers:</span>
                    <span class="font-bold text-slate-800">{{ $lead->adults }} Adults {{ $lead->children > 0 ? '+ ' . $lead->children . ' Ch' : '' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Budget:</span>
                    <span class="font-bold text-emerald-700">{{ $lead->budget ? '₹' . number_format($lead->budget) : 'Open Budget' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Travel Start:</span>
                    <span class="font-bold text-slate-800">{{ $lead->travel_start_date ? \Carbon\Carbon::parse($lead->travel_start_date)->format('M d, Y') : 'Not Set' }}</span>
                </div>
                <div class="flex justify-between py-1.5">
                    <span class="text-slate-500">Source:</span>
                    <span class="font-bold text-slate-800">{{ $lead->source?->name ?? 'Website Enquiry' }}</span>
                </div>

                @if($lead->notes)
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-slate-400 font-semibold mb-1">Requirement Notes:</p>
                        <p class="p-2.5 bg-slate-50 rounded-xl text-slate-700 leading-relaxed">{{ $lead->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Status Changer Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4 text-amber-500"></i> Update Lead Status
                </h3>
                <form action="{{ route('admin.leads.status.update', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">New Pipeline Stage</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                            <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>New Enquiry</option>
                            <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="proposal_sent" {{ $lead->status == 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                            <option value="negotiation" {{ $lead->status == 'negotiation' ? 'selected' : '' }}>In Negotiation</option>
                            <option value="converted" {{ $lead->status == 'converted' ? 'selected' : '' }}>Converted / Won</option>
                            <option value="lost" {{ $lead->status == 'lost' ? 'selected' : '' }}>Lost Lead</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Status Notes</label>
                        <input type="text" name="remarks" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs" placeholder="e.g. Sent PDF itinerary over WhatsApp">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                        Update Pipeline
                    </button>
                </form>
            </div>
        </div>

        <!-- Column 2: Follow-up Scheduling & Quotations -->
        <div class="space-y-6">
            <!-- Schedule Follow-up Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 text-amber-500"></i> Schedule Next Follow-up
                </h3>
                <form action="{{ route('admin.leads.followup.store', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Follow-up Date & Time *</label>
                        <input type="datetime-local" name="followup_date" required min="{{ date('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Follow-up Agenda *</label>
                        <textarea name="remarks" rows="2" required
                                  class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs"
                                  placeholder="e.g. Call client to discuss hotel upgrade and flight options"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">
                        Schedule Follow-up
                    </button>
                </form>

                <!-- Existing Followups List -->
                @if($lead->followups->count() > 0)
                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                        <p class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Scheduled Tasks ({{ $lead->followups->count() }})</p>
                        @foreach($lead->followups as $fl)
                            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex items-start justify-between">
                                <div>
                                    <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($fl->followup_date)->format('M d, Y h:i A') }}</p>
                                    <p class="text-slate-600 text-[11px] mt-0.5">{{ $fl->remarks }}</p>
                                </div>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800 uppercase">{{ $fl->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quotations on this Lead -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4 text-teal-600"></i> Quotations ({{ $lead->quotations->count() }})
                    </h3>
                    <a href="{{ route('admin.quotations.create', ['lead_id' => $lead->id]) }}" class="text-teal-600 hover:text-teal-800 font-bold text-[11px]">+ New Quote</a>
                </div>
                <div class="space-y-2">
                    @forelse($lead->quotations as $quot)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-900">#{{ $quot->quotation_number }}</p>
                                <p class="text-[11px] text-slate-500">₹{{ number_format($quot->total_amount, 2) }} &bull; {{ $quot->status }}</p>
                            </div>
                            <a href="{{ route('admin.quotations.show', $quot->id) }}" class="text-teal-600 hover:text-teal-800 font-bold">
                                View &rarr;
                            </a>
                        </div>
                    @empty
                        <p class="text-slate-400 py-2">No quotations generated yet for this lead.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Column 3: Activity Feed & Note Logger -->
        <div class="space-y-6">
            <!-- Add Activity Note -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4 text-amber-500"></i> Log Client Touchpoint
                </h3>
                <form action="{{ route('admin.leads.activity.store', $lead->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Interaction Type *</label>
                            <select name="type" required class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                                <option value="call">Phone Call</option>
                                <option value="whatsapp">WhatsApp Chat</option>
                                <option value="email">Email</option>
                                <option value="note">Internal Note</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Summary *</label>
                            <input type="text" name="summary" required placeholder="e.g. Discussed package price" class="w-full px-2 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Details</label>
                        <textarea name="details" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 text-xs" placeholder="Client requested 4-star Dal Lake houseboat..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-black text-white font-bold rounded-xl text-xs transition">
                        Log Touchpoint
                    </button>
                </form>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs text-xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-slate-500"></i> Activity Timeline ({{ $lead->activities->count() }})
                </h3>

                <div class="space-y-4">
                    @forelse($lead->activities->sortByDesc('created_at') as $act)
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-slate-900">{{ $act->summary }}</p>
                                @if($act->details)
                                    <p class="text-slate-600 text-[11px] mt-0.5">{{ $act->details }}</p>
                                @endif
                                <p class="text-[10px] text-slate-400 mt-1">
                                    {{ $act->user?->name ?? 'System' }} &bull; {{ $act->created_at->format('M d, h:i A') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400">No activity logged yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

