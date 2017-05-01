<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Application.php.
 *
 * Part of Overtrue\WeChat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015
 *
 * @see      https://github.com/overtrue
 * @see      http://overtrue.me
 */

namespace app\EasyWechat\Foundation;

// include ""

use app\EasyWechat\Pimple\Container;
use Doctrine\Common\Cache\Cache as CacheInterface;
use Doctrine\Common\Cache\FilesystemCache;
use EasyWechat\Core\AccessToken;
use EasyWechat\Core\Http;
use EasyWechat\Support\Log;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Application.
 *
 * @property \EasyWechat\Core\AccessToken                    $access_token
 * @property \EasyWechat\Server\Guard                        $server
 * @property \EasyWechat\User\User                           $user
 * @property \EasyWechat\User\Tag                            $user_tag
 * @property \EasyWechat\User\Group                          $user_group
 * @property \EasyWechat\Js\Js                               $js
 * @property \Overtrue\Socialite\Providers\WeChatProvider    $oauth
 * @property \EasyWechat\Menu\Menu                           $menu
 * @property \EasyWechat\Notice\Notice                       $notice
 * @property \EasyWechat\Material\Material                   $material
 * @property \EasyWechat\Material\Temporary                  $material_temporary
 * @property \EasyWechat\Staff\Staff                         $staff
 * @property \EasyWechat\Url\Url                             $url
 * @property \EasyWechat\QRCode\QRCode                       $qrcode
 * @property \EasyWechat\Semantic\Semantic                   $semantic
 * @property \EasyWechat\Stats\Stats                         $stats
 * @property \EasyWechat\Payment\Merchant                    $merchant
 * @property \EasyWechat\Payment\Payment                     $payment
 * @property \EasyWechat\Payment\LuckyMoney\LuckyMoney       $lucky_money
 * @property \EasyWechat\Payment\MerchantPay\MerchantPay     $merchant_pay
 * @property \EasyWechat\Payment\CashCoupon\CashCoupon       $cash_coupon
 * @property \EasyWechat\Reply\Reply                         $reply
 * @property \EasyWechat\Broadcast\Broadcast                 $broadcast
 * @property \EasyWechat\Card\Card                           $card
 * @property \EasyWechat\Device\Device                       $device
 * @property \EasyWechat\ShakeAround\ShakeAround             $shakearound
 * @property \EasyWechat\OpenPlatform\OpenPlatform           $open_platform
 * @property \EasyWechat\MiniProgram\MiniProgram             $mini_program
 */
class Application extends Container
{
    /**
     * Service Providers.
     *
     * @var array
     */
    protected $providers = [
        ServiceProviders\ServerServiceProvider::class,
        ServiceProviders\UserServiceProvider::class,
        ServiceProviders\JsServiceProvider::class,
        ServiceProviders\OAuthServiceProvider::class,
        ServiceProviders\MenuServiceProvider::class,
        ServiceProviders\NoticeServiceProvider::class,
        ServiceProviders\MaterialServiceProvider::class,
        ServiceProviders\StaffServiceProvider::class,
        ServiceProviders\UrlServiceProvider::class,
        ServiceProviders\QRCodeServiceProvider::class,
        ServiceProviders\SemanticServiceProvider::class,
        ServiceProviders\StatsServiceProvider::class,
        ServiceProviders\PaymentServiceProvider::class,
        ServiceProviders\POIServiceProvider::class,
        ServiceProviders\ReplyServiceProvider::class,
        ServiceProviders\BroadcastServiceProvider::class,
        ServiceProviders\CardServiceProvider::class,
        ServiceProviders\DeviceServiceProvider::class,
        ServiceProviders\ShakeAroundServiceProvider::class,
        ServiceProviders\OpenPlatformServiceProvider::class,
        ServiceProviders\MiniProgramServiceProvider::class,
    ];

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        parent::__construct();

        $this['config'] = function () use ($config) {
            return new Config($config);
        };

        if ($this['config']['debug']) {
            error_reporting(E_ALL);
        }

        $this->registerProviders();
        $this->registerBase();
        $this->initializeLogger();

        Http::setDefaultOptions($this['config']->get('guzzle', ['timeout' => 5.0]));

        $this->logConfiguration($config);
    }

    /**
     * Log configuration.
     *
     * @param array $config
     */
    public function logConfiguration($config)
    {
        $config = new Config($config);

        $keys = ['app_id', 'secret', 'open_platform.app_id', 'open_platform.secret', 'mini_program.app_id', 'mini_program.secret'];
        foreach ($keys as $key) {
            !$config->has($key) || $config[$key] = '***'.substr($config[$key], -5);
        }

        Log::debug('Current config:', $config->toArray());
    }

    /**
     * Add a provider.
     *
     * @param string $provider
     *
     * @return Application
     */
    public function addProvider($provider)
    {
        array_push($this->providers, $provider);

        return $this;
    }

    /**
     * Set providers.
     *
     * @param array $providers
     */
    public function setProviders(array $providers)
    {
        $this->providers = [];

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Register providers.
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * Register basic providers.
     */
    private function registerBase()
    {
        $this['request'] = function () {
            return Request::createFromGlobals();
        };

        if (!empty($this['config']['cache']) && $this['config']['cache'] instanceof CacheInterface) {
            $this['cache'] = $this['config']['cache'];
        } else {
            $this['cache'] = function () {
                return new FilesystemCache(sys_get_temp_dir());
            };
        }

        $this['access_token'] = function () {
            return new AccessToken(
                $this['config']['app_id'],
                $this['config']['secret'],
                $this['cache']
            );
        };
    }

    /**
     * Initialize logger.
     */
    private function initializeLogger()
    {
        if (Log::hasLogger()) {
            return;
        }

        $logger = new Logger('EasyWechat');

        if (!$this['config']['debug'] || defined('PHPUNIT_RUNNING')) {
            $logger->pushHandler(new NullHandler());
        } elseif ($this['config']['log.handler'] instanceof HandlerInterface) {
            $logger->pushHandler($this['config']['log.handler']);
        } elseif ($logFile = $this['config']['log.file']) {
            $logger->pushHandler(new StreamHandler(
                $logFile,
                $this['config']->get('log.level', Logger::WARNING),
                true,
                $this['config']->get('log.permission', null))
            );
        }

        Log::setLogger($logger);
    }
}
