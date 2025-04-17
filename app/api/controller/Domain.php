<?php
namespace app\api\controller;

use app\BaseController;

use AlibabaCloud\SDK\Alidns\V20150109\Alidns;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeDomainRecordsRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\UpdateDomainRecordRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

class Domain extends BaseController
{
    /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Alidns Client
     */
    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            // 您的AccessKey ID
            "accessKeyId" => $accessKeyId,
            // 您的AccessKey Secret
            "accessKeySecret" => $accessKeySecret
        ]);
        // 访问的域名
        $config->endpoint = "alidns.cn-hangzhou.aliyuncs.com";
        return new Alidns($config);
    }
    /**
     * @param string[] $args
     * @return void
     */
    public static function main($domain,$record){
        $client = self::createClient("LTAI5tKkxQ1GgrHFLCs4CDLf", "IBxm2Qb6qZpWsRhR0buLlPPbJonmq3");
        $describeDomainRecordsRequest = new DescribeDomainRecordsRequest([
            "domainName" => $domain
        ]);
        $runtime = new RuntimeOptions([]);
        try {
            // 复制代码运行请自行打印 API 的返回值
            $result = $client->describeDomainRecordsWithOptions($describeDomainRecordsRequest, $runtime);
            $recode = $result->body->domainRecords->record;
            for($i=0;$i<count($recode);$i++)
            {
                if($recode[$i]->RR == '*' && $recode[$i]->value == $record)
                {
                    $recordId = $recode[$i]->recordId;
                    /***====自动化更新DNS记录开始===**/
                    $updateDomainRecordRequest = new UpdateDomainRecordRequest([
                        "recordId" => $recordId,
                        "RR" => "*",
                        "type" => "CNAME",
                        "value" => "986e7835.ztcdndns.com"
                    ]);
                    $runtime = new RuntimeOptions([]);
                    try {
                        // 复制代码运行请自行打印 API 的返回值
                        $result1=$client->updateDomainRecordWithOptions($updateDomainRecordRequest, $runtime);
                        if($result1->body->recordId == $recordId)
                        {
                            $url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=域名DNS已自动切换至自建CDN,请检查处理(测试版)!' . date('Y-m-d H:i:s');
                            curl_file_get_contents($url);
                        }else{
                            $url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=域名DNS自动切换失败,请立即检查处理!' . date('Y-m-d H:i:s');
                            curl_file_get_contents($url);
                        }
                    }catch (Exception $error) {
                        if (!($error instanceof TeaError)) {
                            $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
                        }
                        $url = 'https://api.telegram.org/bot5433247843:AAFAcytBlaUl6Frr5JkLVhABHqXR7OtgzaI/sendMessage?chat_id=-709488839&text=域名DNS自动切换失败,请立即检查处理!' . date('Y-m-d H:i:s');
                            curl_file_get_contents($url);
                        // 如有需要，请打印 error
                        Utils::assertAsString($error->message);
                    }
                    /***====自动化更新DNS记录结束===**/
                }
            }
        }
        catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            // 如有需要，请打印 error
            Utils::assertAsString($error->message);
        }
    }
    public function index()
    {
        $domain = input('param.domain');
        $record = input('param.record');
        self::main($domain,$record);
    }
}