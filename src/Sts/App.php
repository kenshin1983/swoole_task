<?php 
/**
 * 
 */
namespace Sts;
class App
{
    public $server;

	function onStart($serv, $worker_id = 0)
    {
        //用户修改
        $server = $this->server->getConfig();
        if (!empty($server['user']))
            Console::changeUser($server['user']);

        if (!empty($server['name'])) 
        {
            if ($worker_id >= $serv->setting['worker_num'])
            {
                Console::setProcessName('php '.$server['name'].': task');
            }
            else
            {
                Console::setProcessName('php '.$server['name'].': worker');
            }
        }

        Console::output($server['name'] . "[#{$worker_id}]. running. on {$server['host']}:{$server['port']}");
        register_shutdown_function(array($this, 'onError'));
    }

    function onReceive($serv, $client_id, $from_id, $data)
    {
        $response = new Response();
        $response->body = json_encode(array(1,2,3));
        $this->server->getSwoole()->send($client_id, $response->getHeader() . $response->body);
        sleep(1);
        $this->server->getSwoole()->close($client_id);
        return;
        // //检测request data完整性
        // $ret = $this->checkData($client_id, $data);
        // switch($ret)
        // {
        //     //错误的请求
        //     case self::ST_ERROR;
        //         $this->server->close($client_id);
        //         return;
        //     //请求不完整，继续等待
        //     case self::ST_WAIT:
        //         return;
        //     default:
        //         break;
        // }
        // //完整的请求
        // //开始处理
        // $request = $this->requests[$client_id];
        // $info = $serv->connection_info($client_id);
        // $request->remote_ip = $info['remote_ip'];
        // $_SERVER['SWOOLE_CONNECTION_INFO'] = $info;

        // $this->parseRequest($request);
        // $request->fd = $client_id;
        // $this->currentRequest = $request;
        // if ($this->async)
        // {
        //     $this->onAsyncRequest($request);
        // }
        // else
        // {
        //     //处理请求，产生response对象
        //     $response = $this->onRequest($request);
        //     //发送response
        //     $this->response($request, $response);
        // }
        Console::output("onReceive");
    }

    function onConnect($serv, $client_id, $from_id)
    {
        Console::output("onConnect");
    }

    function onClose($serv, $client_id, $from_id)
    {
        Console::output("onClose");
    }

    function onShutdown($serv)
    {
        Console::output("onShutdown");
    }

    function onTimer($serv, $interval)
    {
        Console::output("onTimer");
    }

    function onTask($serv, $task_id, $from_id, $data)
    {
        Console::output("onTask");
    }

    function onFinish($serv, $task_id, $data)
    {
        Console::output("onFinish");
    }

    /**
     * 捕获错误
     */
    function onError()
    {
        $error = error_get_last();
        if (!isset($error['type'])) return;
        switch ($error['type'])
        {
            case E_ERROR :
            case E_PARSE :
            case E_DEPRECATED:
            case E_CORE_ERROR :
            case E_COMPILE_ERROR :
                break;
            default:
                return;
        }
        $errorMsg = "{$error['message']} ({$error['file']}:{$error['line']})";
        $message = Console::output("Application Error", $errorMsg);
    }
}