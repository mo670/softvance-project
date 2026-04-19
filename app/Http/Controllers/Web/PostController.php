<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    private function ensureApiPermission(Request $request, string $permission): void
    {
        $user = $request->user();

        abort_unless($user && $user->hasPermissionTo($permission, 'api'), 403, 'Unauthorized');
    }

    private function ensureApiAdmin(Request $request): void
    {
        $user = $request->user();

        abort_unless($user && $user->hasRole('admin', 'api'), 403, 'Unauthorized');
    }

    public function list(): View
    {
        return view('posts.list');
    }

    public function create(Request $request): View
    {
        $this->ensureApiPermission($request, 'post.create');

        return view('posts.create');
    }

    public function datatable(Request $request): JsonResponse
    {
        $query = Post::query()->with('user')->latest();

        return DataTables::eloquent($query)
            ->addColumn('author', fn (Post $post) => $post->user?->name ?? 'N/A')
            ->addColumn('actions', function (Post $post): string {
                return sprintf(
                    '<button class="btn btn-sm btn-warning edit-btn" data-id="%d" data-title="%s" data-body="%s">Edit</button>
                     <button class="btn btn-sm btn-danger delete-btn" data-id="%d">Delete</button>',
                    $post->id,
                    e($post->title),
                    e($post->body ?? ''),
                    $post->id
                );
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function store(Request $request): JsonResponse
    {
        $this->ensureApiPermission($request, 'post.create');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = $request->user()->id;
        Post::create($validated);

        return response()->json(['message' => 'Post created successfully']);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $this->ensureApiAdmin($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
        ]);

        $post->update($validated);

        return response()->json(['message' => 'Post updated successfully']);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $this->ensureApiPermission($request, 'post.delete');

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
