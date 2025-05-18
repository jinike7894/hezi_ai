<?php
namespace app\gladmin\controller\data;

use app\common\model\Refresh;
use app\gladmin\traits\Curd;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Session;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Cdn\V20180606\CdnClient;
use TencentCloud\Cdn\V20180606\Models\DescribePushQuotaRequest;
//刷新CDN
use TencentCloud\Cdn\V20180606\Models\PurgeUrlsCacheRequest;

/**
 * Class Url
 * @package app\gladmin\controller\url
 * @ControllerAnnotation(title="Url刷新")
 */
class Url extends AdminController
{

    use Curd;

    protected $relationSearch = true;
    protected $sort = [
        'id'   => 'asc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Refresh();
        $this->SecretId = '';
        $this->SecretKey = '';
        $this->urls = '';
    }
	public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }else if(input('post.')){
            $domain=input('post.domain');
            if($domain)
            {
                //Session::set('domain',$domain);
               //$this->domain = $domain;
               $rule = '/^([a-z]|\d|[\x7f-\xff])+\.([a-z]|[\x7f-\xff])+$/';
               if(preg_match($rule,$domain)){
                   $result = $this->model->createdata($domain);
               }else{
                   $this->error('你输入的域名不符合规则!','',null,3,[],'html');
               }
            }else{
                $this->error('请不要提交非法内容','',null,3,[],'html');
            }
        }
        return $this->fetch();
    }
    public function add()
    {
        return $this->fetch();
    }
    public function refresh($id)
    {
        $this->checkPostRequest();
        $urls = $this->model->getredata();
        try {
            // 实例化一个认证对象，入参需要传入腾讯云账户secretId，secretKey,此处还需注意密钥对的保密
            // 密钥可前往https://console.cloud.tencent.com/cam/capi网站进行获取
            $cred = new Credential($this->SecretId, $this->SecretKey);
            // 实例化一个http选项，可选的，没有特殊需求可以跳过
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("cdn.tencentcloudapi.com");
            
            // 实例化一个client选项，可选的，没有特殊需求可以跳过
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            // 实例化要请求产品的client对象,clientProfile是可选的
            $client = new CdnClient($cred, "", $clientProfile);
            // 实例化一个请求对象,每个接口都会对应一个request对象
            $req = new PurgeUrlsCacheRequest();
            if(is_array($id))
            {
                for($i=0;$i<count($id);$i++)
                {
                    $reurls[$i] = $urls[$id[$i]-1]['url']; 
                }
            }else{
                $reurls[0] = $urls[$id]['url'];
            }
            $params = array(
                "Urls" => $reurls
            );
            $req->fromJsonString(json_encode($params));
            
            // 返回的resp是一个PurgeUrlsCacheResponse的实例，与请求对象对应
            $resp = $client->PurgeUrlsCache($req);
            
            // 输出json格式的字符串回包
            $this->success('链接刷新成功'.$resp->toJsonString());
        }catch(TencentCloudSDKException $e) {
            $this->error("链接刷新失败:{$e}");
        }
    }

}