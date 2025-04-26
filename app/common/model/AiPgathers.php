<?php
namespace app\common\model;

use app\common\model\Pcategory;
use Think\Model;
use Think\Page;
use Think\Db;

class AiPgathers extends \think\Model
{
	// 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'          => 'string',
        'clicks'          => 'int',
		'income'          => 'decimal',
        'create_time' => 'int',
        'update_time' => 'int',
        'delete_time' => 'int',
    ];
	function getlist($where,$cxdate,$channelCode)
	{
	    $products = new Products();
        $aiProductTongji = new AiProductClickRecord();
        $aiActRecord = new AiActivityRecord();
	    $list = $products::field('id,name,cid,pid,k_name,androidurl')->where($where)->select()->toArray();/*->group('name')*/
	    $results = [];
	    $pcate = new Pcategory();
	    $ptype = new \app\common\model\Ptype();
	    for($i=0;$i<count($list);$i++){
	        if($cxdate) {
                $map1[] = ['create_time','>=',strtotime($cxdate[0][2].' 00:00:00')];
	            $map1[] = ['create_time','<=',strtotime($cxdate[0][2].' 23:59:59')];
	        }
	        if(!empty($channelCode)) {
	            $map1[] = ['channelCode','=',$channelCode];
	        }
	        $map1[] =['pid','=',$list[$i]['id']];
	        $data['clicks'] = $aiProductTongji::where($map1)->count();
            $data['points'] = $aiActRecord::where($map1)->sum('points');
            $data['channelCode'] = $channelCode;
            $data['cate_title'] = $pcate->where(array('id'=>$list[$i]['cid']))->value('title') ?: '';
            $data['type_title'] = $ptype->where(array('id'=>$list[$i]['pid']))->value('title') ?: '';
            $data['id'] = $list[$i]['id'];
            $data['name'] = $list[$i]['name'];
            $data['k_name'] = $list[$i]['k_name'];
            $data['androidurl'] = $list[$i]['androidurl'];
            if($cxdate) {
                $data['date'] = $cxdate[0][2];
            }

            $results[] = $data;
	        $map1 = null;
	    }
	    return $results;
	}
	public function getlistshow($id,$cxdate)
	{
	    $products = new Products();
	    $tongji = new Tongji();
	    //$name = $products::where(array('id'=>$id))->value('name');
	   // $list1 = $products::field('id')->where(array('id'=>$id))->find()->toArray();
	   // $ids = array_column($list1,'id');
	   // $ids = implode(',',$ids);
	    $product = $products::field('id,name,k_name,androidurl')->where(['id' => $id])->find();
	    $channelCode = $tongji->field('channelCode')->group('channelCode')->select();
	    $result = [];
	    for($i=0;$i<count($channelCode);$i++)
	    {
	        $map1[] =['pid','=',$id];
	        $map1[] = ['channelCode','=',$channelCode[$i]['channelCode']];
	        $map1[] = $cxdate;
	        $data['shows'] = $tongji::where($map1)->sum('shows');
	        $data['clicks'] = $tongji::where($map1)->sum('clicks');
	        $data['downfinish'] = $tongji::where($map1)->sum('downfinish');
	        if ($data['shows'] == 0 && $data['clicks'] == 0 && $data['downfinish'] == 0) {
	            //continue;
	        } else{
	            $data['name'] = $product['name'];
	            $data['k_name'] = $product['k_name'];
	            $data['ratio'] = $data['clicks'] > 0 ? @round($data['downfinish']/$data['clicks']*100,2) : '0';
	            $data['ratio1'] = $data['shows'] > 0 ? @round($data['clicks']/$data['shows']*100,2) : '0';
	            $data['channelCode'] = $i;
	            $data['cost'] = 0;
	            $data['income'] = 0;
	            $results[] = $data;
	        }
	        $map1 = null;
	    }
	    return $results;
	}
}

?>