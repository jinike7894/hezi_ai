<?php
          
            $filename = 'itms-services.mobileconfig';
            $channel = $_GET['channelCode'];

            
            if(empty($channel)){
                $channel = "1";

            }
            //  echo "ok11111111";die;
            // 确保文件存在
            if (file_exists($filename)) {
                // 读取文件内容
                $fileContent = file_get_contents($filename);

                // 替换文件内容
                // 例如，替换 "PLACEHOLDER" 为实际值
                $url ="https://".$_SERVER['HTTP_HOST']."/#/?channelCode=".$channel; 
                if(isset($_GET['pid'])){
                    $url.="%26pid=".$_GET['pid'];
                }
                $fileContent = str_replace('@h5@', $url, $fileContent);

                // 设置头部信息，指示浏览器下载文件
                header('Content-Type: application/x-apple-aspen-config');
                header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
                header('Content-Length: ' . strlen($fileContent));
            
                // 清空输出缓冲区
                // ob_clean();
                // flush();
            
                // 输出修改后的内容
                echo $fileContent;
                exit;
            } else {
                echo '文件不存在。';
            }
        	

?>