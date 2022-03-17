<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkStampRequest;
use App\Http\Requests\UpdateWorkStampRequest;
use App\Models\WorkStamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkStampController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreWorkStampRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkStampRequest $request)
    {
        $new_stamp = collect($request->safe()->only("newStamp"))->first();
        WorkStamp::create([
            "work_id" => $new_stamp["work_id"],
            "stamp_at" => $new_stamp["stamp_at"],
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkStampRequest  $request
     * @param  WorkStamp  $work_stamp
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkStampRequest $request, WorkStamp $work_stamp)
    {
        $update_stamp = collect($request->safe()->only("updateStamp"))->first();
        $work_stamp->stamp_at = $update_stamp["stamp_at"];
        $work_stamp->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkStamp $work_stamp)
    {
        $work_stamp->delete();
        return back();
    }
}
