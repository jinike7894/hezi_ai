<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\exception\HttpResponseException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
	/**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的 URL 地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function success($msg = '', $data = '', $url = null, $wait = 3, array $header = [])
    {
        if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : app('route')->buildUrl($url)->__toString();
        }

        $result = [
            'code' => 1,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ];

        $type = $this->getResponseType();
        if ($type == 'html') {
            $response = view(app('config')->get('app.dispatch_success_tmpl'), $result);
        } elseif ($type == 'json') {
            $response = json($result);
        }
        throw new HttpResponseException($response);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的 URL 地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function error($msg = '', $data = '', $url = null, $wait = 3, array $header = [])
    {
        if (is_null($url)) {
            $url = request()->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : app('route')->buildUrl($url)->__toString();
        }

        $type   = $this->getResponseType();
        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ];
        if ($type == 'html') {
            $response = view(app('config')->get('app.dispatch_error_tmpl'), $result);
        } elseif ($type == 'json') {
            $response = json($result);
        }
        throw new HttpResponseException($response);
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param int $code 返回的 code
     * @param mixed $msg 提示信息
     * @param string $type 返回数据格式
     * @param array $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function result($data, $code = 0, $msg = '', $type = '', array $header = [])
    {
        $result   = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];
        $type     = $type ?: $this->getResponseType();
        $response = Response::create($result, $type)->header($header);

        throw new HttpResponseException($response);
    }

    /**
     * URL 重定向
     * @access protected
     * @param string $url 跳转的 URL 表达式
     * @param array|int $params 其它 URL 参数
     * @param int $code http code
     * @param array $with 隐式传参
     * @return void
     * @throws HttpResponseException
     */
    protected function redirect($url = [], $params = [], $code = 302)
    {
        if (is_integer($params)) {
            $code   = $params;
            $params = [];
        }

        $response = Response::create($url, 'redirect', $code);
        throw new HttpResponseException($response);
    }

    /**
     * 获取当前的 response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return (request()->isJson() || request()->isAjax() || request()->isPost()) ? 'json' : 'html';
    }

}
