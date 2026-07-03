<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentChatSession;
use App\Models\AiAgent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class AgentChatSessionController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $request->validate([
            'agent' => ['nullable', 'integer'],
            'status' => ['nullable', Rule::in(['active', 'completed'])],
        ]);

        $query = AgentChatSession::query()
            ->with('agent:id,name')
            ->withCount('messages')
            ->latest();

        if ($agentId = $request->integer('agent')) {
            $query->where('ai_agent_id', $agentId);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        return Inertia::render('admin/AgentChatSessions/Index', [
            'sessions' => $query->paginate(15)->withQueryString(),
            'agents' => AiAgent::query()->orderBy('name')->get(['id', 'name']),
            'filters' => [
                'agent' => $request->input('agent'),
                'status' => $request->input('status'),
            ],
            'totals' => [
                'sessions' => AgentChatSession::query()->count(),
                'active' => AgentChatSession::query()->where('status', 'active')->count(),
                'prompt_tokens' => (int) AgentChatSession::query()->sum('prompt_tokens'),
                'completion_tokens' => (int) AgentChatSession::query()->sum('completion_tokens'),
            ],
        ]);
    }

    public function show(AgentChatSession $chatSession): InertiaResponse
    {
        $chatSession->load([
            'agent:id,name,provider,model,max_interactions',
            'shareLink:id,token,label',
            'messages' => fn ($query) => $query->orderBy('id'),
        ]);

        return Inertia::render('admin/AgentChatSessions/Show', [
            'session' => $chatSession,
        ]);
    }
}
