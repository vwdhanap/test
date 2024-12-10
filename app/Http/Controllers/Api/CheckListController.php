<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCheckListRequest;
use App\Http\Resources\CheckListItemResource;
use App\Http\Resources\CheckListResource;
use App\Models\CheckList;
use Illuminate\Http\Request;

class CheckListController extends Controller
{
    /**
     * Get checklists
     * 
     * @response CheckListResource
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $checkList = $user->checkList()->whereNull('parent_id')->get();

        return CheckListResource::collection($checkList);
    }

    /**
     * Store checklist
     * 
     * @response CheckListResource
     */
    public function store(StoreCheckListRequest $request)
    {
        $user = $request->user();
        $checkList = $user->checkList()->create(['name' => $request->input('name')]);

        return new CheckListResource($checkList);
    }

    /**
     * Delete checklist
     * 
     * @response CheckListResource
     */
    public function destroy($checklistId)
    {
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $checkList->delete();

        return response()->json([
            'message' => 'Checklist deleted successfully'
        ], 200);
    }

    /**
     * Get check List items
     * 
     * @response CheckListItemResource
     */
    public function getCheckListItems($checklistId)
    {
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $items = $checkList->child()->get();

        return CheckListItemResource::collection($items);
    }

    /**
     * Store check List item
     * 
     * @response CheckListItemResource
     */
    public function storeCheckListItem($checklistId, StoreCheckListRequest $request)
    {
        $user = $request->user();
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $item = $checkList->child()->create([
            'user_id' => $user->id,
            'name' => $request->input('name'),
            'status' => false
        ]);

        return new CheckListItemResource($item);
    }

    /**
     * Show check List item
     * 
     * @response CheckListItemResource
     */
    public function showCheckListItem($checklistId, $checklistItemId)
    {
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $item = $checkList->child()->firstWhere('id', $checklistItemId);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        return new CheckListItemResource($item);
    }

    /**
     * Update status check List item
     * 
     * @response CheckListItemResource
     */
    public function updateStatusCheckListItem($checklistId, $checklistItemId)
    {
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $item = $checkList->child()->firstWhere('id', $checklistItemId);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        $item->status = !$item->status;
        $item->save();

        return new CheckListItemResource($item);
    }

    /**
     * Delete check List item
     * 
     * @response CheckListItemResource
     */
    public function deleteCheckListItem($checklistId, $checklistItemId)
    {
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $item = $checkList->child()->firstWhere('id', $checklistItemId);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Checklist item deleted successfully'
        ], 200);
    }

    /**
     * Rename check List item
     * 
     * @response CheckListItemResource
     */
    public function renameCheckListItem($checklistId, $checklistItemId, StoreCheckListRequest $request)
    {
        $checkList = CheckList::find($checklistId);

        if (!$checkList) {
            return response()->json([
                'message' => 'Check list not found'
            ], 404);
        }

        $item = $checkList->child()->firstWhere('id', $checklistItemId);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found'
            ], 404);
        }

        $item->name = $request->input('name');
        $item->save();

        return new CheckListItemResource($item);
    }
}
