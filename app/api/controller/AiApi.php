<?php
namespace app\api\controller;

use app\api\controller\AiBase;
use app\common\model\Products;

use think\facade\Db;
use app\common\model\AiActivityRecord;
use app\common\model\AiUser;
use app\common\model\AiOrder;
use app\common\model\AiPointsBill;
use app\common\model\AiUseRecord;
use app\common\model\AiVideoTemplate;
use app\common\model\AiImgTemplate;
use app\gladmin\model\SystemConfig;

class AiApi
{
    public $token = "7e9b31fa6a2141d5a2981aa9f3e14110";
    public $taskHost = "https://test.aifacetools.com/openApi/submitTask"; //视频/图片/自动/手动 换脸
    public $taskStatusHost = "https://test.aifacetools.com/openApi/batchGetTaskDetail"; //批量获取任务
    public $delTaskHost = "https://test.aifacetools.com/openApi/removeTask"; //批量获取任务

    //发送任务至ai三方
    public function dataToAi($taskType, $imgPath, $templateId, $recordId, $maskUrl = "")
    {
        $aiParams = [];
        $templateParams = "";
        $system = new SystemConfig();
        $imgHost = $system
            ->where('name', "pic_url")
            ->value("value");
        switch ($taskType) {
            case 0:
                //视频脱衣
                $aiParams["taskType"] = "video_face_swap";
                $templateParams = AiVideoTemplate::where(["id" => $templateId])->value("video_src");
                $aiParams["taskParam"] = [
                    "videoUrl" => $templateParams,
                    "faceUrl" => $imgHost . $imgPath,
                ];
                break;
            case 1:
                //图片脱衣
                $aiParams["taskType"] = "image_face_swap";
                $templateParams = AiImgTemplate::where(["id" => $templateId])->value("img");
                $aiParams["taskParam"] = [
                    "imageUrl" => $templateParams,
                    "faceUrl" => $imgHost . $imgPath,
                ];
                break;
            case 2:
                //自动脱衣
                $aiParams["taskType"] = "auto_undress";
                $aiParams["taskParam"] = [
                    "imageUrl" => $imgHost . $imgPath,
                ];
                break;
            case 3:
                //手动脱衣
                $aiParams["taskType"] = "manual_undress";
                $aiParams["taskParam"] = [
                    "imageUrl" => $imgHost . $imgPath,
                    "maskUrl" => $imgHost . $imgPath,
                ];
                break;
        }
        $aiParams["taskImage"] = $imgHost . $imgPath;//用户上传的图片

        $apiResponse = $this->postParams($this->taskHost, $aiParams);
        if ($apiResponse) {
            $responseData = json_decode($apiResponse, true);
            $taskId = $responseData["data"]["taskId"];
            return AiUseRecord::where(["id" => $recordId])->update([
                "task_id" => $taskId,
            ]);
        }
        return false;
    }
    //批量获取任务
    public function getTaskStatus($dataList)
    {
        $aiParams = [];

        $aiParams["taskIds"] = array_values(array_column($dataList, "task_id"));
        $apiResponse = $this->postParams($this->taskStatusHost, $aiParams);
        $apiResponseData = json_decode($apiResponse, true);
        var_dump($apiResponseData);
        if ($apiResponseData["data"]["list"]) {
            $useRecordParams = [];
            foreach ($apiResponseData["data"]["list"] as $apik => $apiv) {
                $json=json_decode($apiv["taskResults"],true);
                if ($apiv["taskStatus"] != "1" &&$apiv["taskStatus"] != "2") {
                    continue;
                }
                
                switch ($apiv["taskStatus"]) {
                    case "1":
                        //更改任务状态
                        $useRecordParams[] = [
                            "status" => 1,
                            "task_id" => $apiv["taskId"],
                            "ai_generate_source"=>$json[0]["dataUrl"],
                        ];
                        break;
                    case "2":
                        //更改任务状态
                        $useRecordParams[] = [
                            "status" => 2,
                            "task_id" => $apiv["taskId"],
                            "ai_generate_source"=>"",
                        ];
                        break;
                }


            }
            if (count($useRecordParams) > 0) {
                //更新任务表状态
                $sql = "UPDATE dh_ai_use_record SET ";
                $columns = ['status', 'ai_generate_source']; // 替换 'other_field' 为实际字段名称
                foreach ($columns as $column) {
                    $sql .= "$column = CASE task_id ";
                    foreach ($useRecordParams as $update) {
                        $sql .= "WHEN {$update['task_id']} THEN '{$update[$column]}' ";
                    }
                    $sql .= "END, ";
                }
                // 移除最后的逗号
                $sql = rtrim($sql, ", ");
                $sql .= " WHERE task_id IN (" . implode(",", array_column($useRecordParams, "task_id")) . ")";
     
                Db::execute($sql);
                //更新资源
            }

        }
    }
    //删除任务
    public function delTask($taskId)
    {

        $aiParams = [];

        $aiParams["taskId"] = $taskId;
        $apiResponse = $this->postParams($this->delTaskHost, $aiParams);
        $apiResponseData = json_decode($apiResponse, true);
        if($apiResponseData["code"]==200){
            return true;
        }
        return false;
    }
    public function postParams($url, $parmas)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($parmas),
            CURLOPT_HTTPHEADER => [
                "apiToken:" . $this->token,
                "Content-Type: application/json",
            ],
        ]);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 关闭服务器 SSL 证书验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 关闭服务器主机名验证
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return $response;
        }
    }
}