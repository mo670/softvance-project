<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function inbox(): View
    {
        return view('contacts.inbox');
    }

    public function datatable(): JsonResponse
    {
        $query = Contact::query()->latest();

        return DataTables::eloquent($query)
            ->editColumn('message', fn (Contact $row) => e(Str::limit($row->message, 120)))
            ->editColumn('company', fn (Contact $row) => e($row->company ?? '—'))
            ->editColumn('created_at', fn (Contact $row) => $row->created_at?->format('Y-m-d H:i'))
            ->addColumn('actions', function (Contact $row) {
                $url = route('contacts.destroy', $row);

                return '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-contact" data-url="'.e($url).'">Delete</button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json(['message' => 'Deleted.']);
    }
}
