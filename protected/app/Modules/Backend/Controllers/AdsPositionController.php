<?php   namespace App\Modules\Backend\Controllers;



use App\Libs\Utils\Vii;
use App\Models\AdsPosition;
use App\Models\Category;
use App\Models\Config;
use Illuminate\Http\Request;

class AdsPositionController extends BaseController{

    private static $langName = 'ads_position';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }
    

    public function getPositionForm(Request $request){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $total_cat_parent = Category::where('parent_id', 0)
            ->where('published', 1)
            ->where('is_trashed', 0)
            ->count();

        $ads_position = AdsPosition::where('status', '>=', 0)
            ->select(['id', 'page_id', 'pos_name', 'status', 'is_fix'])
            ->get();

        if(count($ads_position) > 0){

            $position_data = [];
            foreach($ads_position as $k => $v){
                $position_data[$v->pos_name] = $v;
            }

            //dd($position_data);

            $config = Config::where('tag_name', 'ads')->first();

            $config_data = json_decode($config->params, true);

            return view(
                'Backend::ads-position.edit-position',
                [
                    'total_cat' => $total_cat_parent,
                    'positions' => $position_data,
                    'cat_group' => $config_data['cat_group'],
                    'qs' => $qs
                ]
            );
        }


        return view(
            'Backend::ads-position.create-position',
            [
                'total_cat' => $total_cat_parent,
                'cat_group' => 2,
                'qs' => $qs
            ]
        );
    }

    public function postCreatePosition(Request $request){
        //dd($request->all());

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $config = Config::firstOrNew(['tag_name' => 'ads']);
        $config->params = json_encode(['cat_group'=> $request->input('cat_group')]);
        $config->save();

        $ads_position = $request->input('ads_position');


        if(!empty($ads_position)){

            AdsPosition::where('status', '>=', 0)->update(['status' => 0]);

            foreach($ads_position as $item){

                $a = explode('|', $item);
                $b = explode('.', $a[0]);

                $page_name = strtoupper($b[0]);

                $pos = AdsPosition::firstOrNew(['pos_name' => $a[0]]);
                $pos->page_id = config('constant.PAGE_POSITION.' . $page_name);
                $pos->status = $a[1];
                $pos->is_fix = $a[2];

                $pos->save();

            }
        }

        return redirect($this->prefixUrl . '/position/create' . $qs);


    }
}