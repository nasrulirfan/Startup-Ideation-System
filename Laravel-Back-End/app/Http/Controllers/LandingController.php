<?php

namespace App\Http\Controllers;
// namespace App\Http\Controllers\stdClass;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $landing = DB::table('landing_pages')->get();
        return response()->json([
            'success' => true,
            'data' => $landing,
        ]);
    }

    public function checkExistLandingProject($projectID){
        $searchProjectID = DB::table('landing_pages')->where('projectID','=',$projectID)->exists();
        return $searchProjectID;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$projectID)
    {
        $validator = Validator::make($request->all(), [
            'html' => 'string|nullable',
            'css' => 'string|nullable',
            'landingName' => 'string|required',
            'landingRevGoal' => 'numeric|required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();
        $initializeJSON = json_encode (json_decode ("[]"));
        $insertLandingPage = DB::table('landing_pages')->insert([
            'projectID' => $projectID,
            'landingHTML' => $data['html'],
            'landingCSS' => $data['css'],
            'landingName' => $data['landingName'],
            'target_revenue' => $data['landingRevGoal'],
            'sign_ups' => 0,
            'unique_view' => 0,
            'expected_revenue' => 0,
            'today_pageview' => 0,
            'remainder_pageview' => 0,
            'currentdate' => now(),
            'series' => $initializeJSON,

        ]);
        return  response()->json([
            'success' => true,
            'errors' => null
        ]);

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $projectid)
    {
        $validator = Validator::make($request->all(), [
            'html' => 'string|nullable',
            'css' => 'string|nullable',
            'landingName' => 'string|required',
            'landingRevGoal' => 'numeric|required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();
        $updateDetails = [
            'landingHTML' => $data['html'],
            'landingCSS' => $data['css'],
        ];
        DB::table('landing_pages')->where('projectID',$projectid)->update($updateDetails);
    }

    public function updateSeries(Request $request, $projectid){
        $validator = Validator::make($request->all(), [
            'updateSeries' => 'json',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $data = $validator->validated();
        DB::table('landing_pages')->where('projectID',$projectid)->update(['series' => $data['updateSeries']]);
    }

    public function updateCurrentDate(Request $request, $projectid){
        $validator = Validator::make($request->all(), [
            'newCurrentDate' => 'date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $data = $validator->validated();
        DB::table('landing_pages')->where('projectID',$projectid)->update(['currentdate' => $data['newCurrentDate']]);
    }

    public function updateTodayPageView(Request $request, $projectid){
        $validator = Validator::make($request->all(), [
            'newTodayPageView' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $data = $validator->validated();
        DB::table('landing_pages')->where('projectID',$projectid)->update(['today_pageview' => $data['newTodayPageView']]);
    }

    public function handleRevenueSignup(Request $request, $projectid){
        $validator = Validator::make($request->all(), [
            'revenue' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $data = $validator->validated();
        DB::table('landing_pages')->where('projectID',$projectid)->increment('sign_ups');
        $dbRevenue = DB::table('landing_pages')->where('projectID',$projectid)->pluck('expected_revenue')[0];
        $newRevenue = $data['revenue'] + $dbRevenue;
        DB::table('landing_pages')->where('projectID',$projectid)->update(['expected_revenue' => $newRevenue]);
        return  response()->json([
            'success' => true,
            'errors' => null
        ]);
    }

    public function incrementTodayPageView($projectid){
        DB::table('landing_pages')->where('projectID',$projectid)->increment('today_pageview');
        return  response()->json([
            'success' => true,
            'errors' => null
        ]);
    }

    public function incrementTotalPageView($projectid){
        DB::table('landing_pages')->where('projectID',$projectid)->increment('unique_view');
        return  response()->json([
            'success' => true,
            'errors' => null
        ]);
    }

    public function incrementRemainderPageView($projectid){
        DB::table('landing_pages')->where('projectID',$projectid)->increment('remainder_pageview');
        return  response()->json([
            'success' => true,
            'errors' => null
        ]);
    }

    public function resetRemainder($projectid){
        DB::table('landing_pages')->where('projectID',$projectid)->update(['remainder_pageview' => 0]);
        return  response()->json([
            'success' => true,
            'errors' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($projectid){
        DB::table('landing_pages')->where('projectID',$projectid)->delete();
        return response()->json(['success' => true, 'message' => 'successfully deleted']);
    }

}
