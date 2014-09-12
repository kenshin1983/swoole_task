<?php 
/**
 * 服务端
 */
namespace Sts;
class Server
{
	const SW_MODEL = SWOOLE_PROCESS;

	protected $server;
	protected $setting;
	protected $sw;
	protected $app;

	public function __construct($config = null)
    {
    	if ($config) $this->loadConfig($config);
    }

    public function loadConfig($config)
    {
    	$this->server = $config['server'];
    	$this->setting = $config['setting'];
    }

    public function getConfig($key = 'server') 
    {
    	return $this->$key;
    }

    public function getSwoole() 
    {
    	return $this->sw;
    }

    public function run()
    {
    	$flag = $this->server['ssl'] ? (SWOOLE_SOCK_TCP | SWOOLE_SSL) : SWOOLE_SOCK_TCP;
    	$this->sw = new \swoole_server($this->server['host'], $this->server['port'], self::SW_MODEL, $flag);
    	$this->sw->set($this->setting);

    	$this->sw->on('ManagerStart', array($this, 'onManagerStart'));
        $this->sw->on('Start', array($this, 'onStart'));
        $this->sw->on('WorkerStart', array($this->app, 'onStart'));
        $this->sw->on('Connect', array($this->app, 'onConnect'));
        $this->sw->on('Receive', array($this->app, 'onReceive'));
        $this->sw->on('Close', array($this->app, 'onClose'));
        $this->sw->on('WorkerStop', array($this->app, 'onShutdown'));
        if (is_callable(array($this->app, 'onTimer')))
        {
            $this->sw->on('Timer', array($this->app, 'onTimer'));
        }
        if (is_callable(array($this->app, 'onTask')))
        {
            $this->sw->on('Task', array($this->app, 'onTask'));
            $this->sw->on('Finish', array($this->app, 'onFinish'));
        }
        $this->sw->start();

    }

    /**
     * 注入应用
     */
	public function setApp($app)
	{
		$this->app = $app;
        $app->server = $this;
	}

	/*===================通用事件==================*/
	function onStart($serv)
    {
    	Console::setProcessName('php '.$this->server['name'].': manager');
    }

	function onManagerStart($serv)
    {
    	Console::setProcessName('php ' . $this->server['name'] . ': master -host=' . $this->server['host'] . ' -port=' . $this->server['port']);
    }
}