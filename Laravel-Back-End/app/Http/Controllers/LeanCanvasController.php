<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\CostStructure;
use App\Models\CustomerSegment;
use App\Models\KeyMetric;
use App\Models\LeanCanvas;
use App\Models\Problem;
use App\Models\Project;
use App\Models\Revenue;
use App\Models\Solution;
use App\Models\UnfairAdvantage;
use App\Models\UniqueValueProposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeanCanvasController extends Controller
{
    public function index($id) {
        // $customerSegment = LeanCanvas::with('customerSegment')->get();
        $canvas = LeanCanvas::find($id);
        $customerSegment = $canvas->customerSegment()->get();
        $problem = $canvas->problem()->get();
        $revenue = $canvas->revenue()->get();
        $solution = $canvas->solution()->get();
        $uva = $canvas->uniqueValueProposition()->get();
        $channel = $canvas->channel()->get();
        $keyMetric = $canvas->keyMetric()->get();
        $cost = $canvas->costStructure()->get();
        $unfairAdvantage = $canvas->unfairAdvantage()->get();
        // return response()->json([
        //     'content' => [
        //         'customerSegment' => $customerSegment,
        //         'problem' => $problem,
        //         'revenue' => $revenue,
        //         'solution' => $solution,
        //         'uniqueValueProposition' => $uva,
        //         'channel' => $channel,
        //         'keyMetric' => $keyMetric,
        //         'costStructure' => $cost,
        //         'unfairAdvantage' => $unfairAdvantage
        //     ],
        //     'success' => true,
        //     'errors' => null
            
        // ]);

        return response()->json([
            'content' => [
                $customerSegment,
                $problem,
                $revenue,
                $solution,
                $uva,
                $channel,
                $keyMetric,
                $cost,
                $unfairAdvantage
            ],
            'success' => true
        ]);
        
    }

    public function addContent(Request $request, $projectId) {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'description' => 'string|nullable',
            'publisher_id' => 'integer|required',
            'contentType' => 'integer|required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $project = Project::find($projectId);
        $canvas = $project->leanCanvases()->get();
        $data = $validator->validated();
        $content = $this->initializeContent($data['contentType']);

        $content->topic = $data['topic'];
        $content->description = $data['description'];
        $content->publisher_id = $data['publisher_id'];
        $content->canvas()->associate($canvas[0]->id)->save();

        return response()->json([
            'content' => $content,
            'success' => true,
            'errors' => null
        ]);
               
    }

    public function initializeContent($type) {
        $contentArr = [
            'CustomerSegment',
            'Problem',
            'Revenue',
            'Solution',
            'UniqueValueProposition',
            'Channel',
            'KeyMetric',
            'CostStructure',
            'UnfairAdvantage'
        ];

        $currentContent = $contentArr[$type-1];
        $namespace = 'App\\Models';
        $content = $namespace . '\\' . $currentContent;

        return new $content;
    }


    public function deleteContent($type, $contentId) {


        $content = null;
        // return response()->json([
        //     'success' => true,
        //     'type' => $type,
        //     'id' => $contentId
        // ]);
        if($type == 1) {
            $content = CustomerSegment::find($contentId);
        } else if ($type == 2) {
            $content = Problem::find($contentId);
        } else if ($type == 3) {
            $content = Revenue::find($contentId);
        } else if ($type == 4) {
            $content = Solution::find($contentId);
        } else if ($type == 5) {
            $content = UniqueValueProposition::find($contentId);
        } else if ($type == 6) {
            $content = Channel::find($contentId);
        } else if ($type == 7) {
            $content = KeyMetric::find($contentId);
        } else if ($type == 8) {
            $content = CostStructure::find($contentId);
        } else if ($type == 9) {
            $content = UnfairAdvantage::find($contentId);
        }

        $content->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function updateContent(Request $request, $type, $contentId) {

        $validator = Validator::make($request->all(), [
            'topic' => 'required',
            'description' => 'nullable|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false
            ]);
        }

        $data = $validator->validated();

        $content = null;
        if($type == 1) {
            $content = CustomerSegment::find($contentId);
        } else if ($type == 2) {
            $content = Problem::find($contentId);
        } else if ($type == 3) {
            $content = Revenue::find($contentId);
        } else if ($type == 4) {
            $content = Solution::find($contentId);
        } else if ($type == 5) {
            $content = UniqueValueProposition::find($contentId);
        } else if ($type == 6) {
            $content = Channel::find($contentId);
        } else if ($type == 7) {
            $content = KeyMetric::find($contentId);
        } else if ($type == 8) {
            $content = CostStructure::find($contentId);
        } else if ($type == 9) {
            $content = UnfairAdvantage::find($contentId);
        }

        $content->topic = $data['topic'];
        $content->description = $data['description'];
        $content->save();

        return response()->json([
            'success' => true
        ]);

        
    }
    
    
    
}