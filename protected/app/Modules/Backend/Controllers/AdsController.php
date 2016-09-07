<?php   namespace App\Modules\Backend\Controllers;



use App\Libs\Utils\Vii;
use App\Models\Ads;
use App\Models\AdsAutoPublish;
use App\Models\AdsPosition;
use App\Modules\Backend\Requests\AdsPostRequest;
use Illuminate\Http\Request;

class AdsController extends BaseController{

    private static $langName = 'ads';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }


    public function getAdsList(Request $request){

        $fields = [
            'id',
            'page_id',
            'pos_id',
            'ads_title',
            'is_auto_publish',
            'published_at',
            'start_time',
            'end_time',
            'status',
            'ordering',
            'created_at',
            'modified_at',
            'created_by',
            'modified_by'
        ];

        $paging = config('backend.row_per_page.20');


        $ads_sql =  Ads::where('is_trashed', 0)->where('status','>=', 0)->with('position');

        $ads_list = $ads_sql->select($fields)->orderBy('created_at', 'DESC')->paginate($paging);

        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        return view(
            'Backend::ads.list-ads',
            [
                'ads_list' => $ads_list,
                'now' => strtotime(date('Y-m-d H:i')),
                'prefix_url' => $this->prefixUrl,
                'form_qs' => $form_qs,
                'rows_per_page' => config('backend.rows_per_page'),
                'qs' => $qs
            ]
        );
    }

    public function getTrashedList(Request $request){

        $fields = [
            'id',
            'page_id',
            'pos_id',
            'ads_title',
            'is_auto_publish',
            'published_at',
            'start_time',
            'end_time',
            'status',
            'ordering',
            'created_at',
            'modified_at',
            'created_by',
            'modified_by'
        ];

        $paging = config('backend.row_per_page.20');


        $ads_sql =  Ads::where('is_trashed', 1)->with('position');

        $ads_list = $ads_sql->select($fields)->orderBy('created_at', 'DESC')->paginate($paging);

        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        return view(
            'Backend::ads.list-trashed-ads',
            [
                'ads_list' => $ads_list,
                'prefix_url' => $this->prefixUrl,
                'form_qs' => $form_qs,
                'rows_per_page' => config('backend.rows_per_page'),
                'qs' => $qs
            ]
        );
    }

    public function getAdsForm(Request $request, $id=null){

        $positions = AdsPosition::where('status', '>=', 0)
            ->select(['id', 'pos_name', 'width', 'height', 'status', 'page_id'])
            ->orderBy('page_id')
            ->get();

        $this->combinePositionFields($positions);



        //dd($positions->toArray());

        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($id != null){

            $ads = Ads::with('auto_publish')->findOrFail($id);

            $can_change_publish = true;
            if($ads->published_at != ''){
                if(strtotime($ads->published_at) < strtotime(date('Y-m-d H:i'))){
                    $can_change_publish = false;
                }

            }

            return view(
                'Backend::ads.edit-ads',
                [
                    'ads' => $ads,
                    'positions' => $positions,
                    'prefix_url' => $this->prefixUrl,
                    'can_change_publish' => $can_change_publish,
                    'qs' => $qs
                ]
            );
        }

        return view(
            'Backend::ads.create-ads',
            [
                'positions' => $positions,
                'prefix_url' => $this->prefixUrl,
                'qs' => $qs
            ]
        );
    }

    public function postCreateAds(AdsPostRequest $request){
        //dd($request->all());

        $data = $request->only(['pos_id', 'ads_title', 'ads_content', 'status', 'published_at']);
        $display_time = $request->input('display_time');

        $pos = AdsPosition::find($data['pos_id'])->first();
        if($pos != null)
            $data['page_id'] = $pos->page_id;

        $published_at = '';
        $data['status'] = intval($data['status']);

        $data['is_auto_publish'] = 0;
        if($data['published_at'] != ''){
            $published_at = Vii::formatDateTime($data['published_at'], 'Y-m-d H:i');
            $data['published_at'] = null;
            $data['is_auto_publish'] = 1;
            $data['status'] = 0;
        }
        else{
            $data['published_at'] = date('Y-m-d H:i');
            $data['status'] = 1;
        }

        if($display_time != ''){
            $atime = explode('-', $display_time);
            $data['start_time'] = Vii::formatDateTime($atime[0], 'Y-m-d H:i');
            $data['end_time'] = Vii::formatDateTime($atime[1], 'Y-m-d H:i');
        }
        else{
            $data['start_time'] = null;
            $data['end_time'] = null;
        }

        $ads = new Ads($data);
        $ads->created_at = date('Y-m-d H:i');
        $ads->created_by = $this->guard->user()->id;

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $rs = true;
        if($ads->save()){

            //Insert Auto Publish
            if($ads->is_auto_publish == 1){
                $auto = new AdsAutoPublish([
                    'ads_id' => $ads->id,
                    'published_at' => $published_at,
                    'is_done' => 0
                ]);

                $rs &= $auto->save();
            }

            if(!$rs){
                return redirect()->back()
                    ->with('message-error', trans($this->mod . '/' . self::$langName . '.ads_create_error'))
                    ->withInput($request->old());
            }

            return redirect($this->prefixUrl . $qs);
        }

        return redirect()->back()
            ->with('message-error', trans($this->mod . '/' . self::$langName . '.ads_create_error'))
            ->withInput($request->old());



    }

    public function putEditAds(AdsPostRequest $request, $id=null){

        //dd($request->all());

        $id = $request->input('id');

        $ads = Ads::find($id);


        $data = $request->only(['pos_id', 'ads_title', 'ads_content', 'status', 'published_at']);
        $display_time = $request->input('display_time');

        $pos = AdsPosition::find($data['pos_id'])->first();
        if($pos != null)
            $data['page_id'] = $pos->page_id;


        $data['status'] = intval($data['status']);

        $data['is_auto_publish'] = 0;
        if($data['published_at'] != ''){
            $data['published_at'] = Vii::formatDateTime($data['published_at'], 'Y-m-d H:i');
            $data['is_auto_publish'] = 1;
        }
        else{
            $data['published_at'] = date('Y-m-d H:i');
        }

        if($display_time != ''){
            $atime = explode('-', $display_time);
            $data['start_time'] = Vii::formatDateTime($atime[0], 'Y-m-d H:i');
            $data['end_time'] = Vii::formatDateTime($atime[1], 'Y-m-d H:i');
        }
        else{
            $data['start_time'] = null;
            $data['end_time'] = null;
        }

        $data['modified_at'] = date('Y-m-d H:i');
        $data['modified_by'] = $this->guard->user()->id;


        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($ads != null) {

            $rs = true;
            if($ads->update($data)){

                //Insert Auto Publish
                if($ads->is_auto_publish == 1){
                    $auto = new AdsAutoPublish([
                        'ads_id' => $ads->id,
                        'published_at' => $ads->published_at,
                        'is_done' => 0
                    ]);

                    $rs &= $auto->save();
                }
                else{
                    $rs &= AdsAutoPublish::where('ads_id', $id)->delete();
                }

                if(!$rs){
                    return redirect()->back()
                        ->with('message-error', trans($this->mod . '/' . self::$langName . '.ads_edit_error'))
                        ->withInput($request->old());
                }

                return redirect($this->prefixUrl . $qs);
            }
        }

        return redirect()->back()
            ->with('message-error', trans($this->mod . '/' . self::$langName . '.ads_edit_error'))
            ->withInput($request->old());
    }

    public function getPublishedAds(Request $request, $id=null){
        $qs = Vii::queryStringBuilder($request->getQueryString());

        $ads = Ads::find($id);

        if($ads != null){
            $p = 1 - $ads->status;
            $ads->update(['status' => $p]);
        }


        return redirect($this->prefixUrl . $qs);
    }

    public function getTrashAds(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        Ads::where('id', $id)->update(['is_trashed' => 1]);

        return redirect($this->prefixUrl . $qs);
    }

    public function getRestoreAds(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        Ads::where('id', $id)->update(['is_trashed' => 0, 'status' => 0]);

        return redirect($this->prefixUrl . '/trashed' . $qs);
    }

    public function getDeleteAds(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        Ads::destroy($id);//News::where('votes', '>', 100)->delete();

        AdsAutoPublish::where('ads_id', $id)->delete();

        return redirect($this->prefixUrl . '/trashed' . $qs);
    }

    /*-------------------------------------------------------------------------*/

    private function combinePositionFields(&$positions){

        if(count($positions) > 0){
            foreach($positions as $v){
                if($v->height > 0 || $v->width > 0){
                    $v->pos_name = $v->pos_name . "(" . $v->width . "x" . $v->height . ")";
                }
            }
        }

    }
    

}