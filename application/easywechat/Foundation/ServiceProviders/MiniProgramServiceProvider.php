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
 * MiniProgramServiceProvider.php.
 *
 * This file is part of the wechat.
 *
 * (c) mingyoung <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace app\easywechat\Foundation\ServiceProviders;

use app\easywechat\Encryption\Encryptor;
use app\easywechat\MiniProgram\AccessToken;
use app\easywechat\MiniProgram\Material\Temporary;
use app\easywechat\MiniProgram\MiniProgram;
use app\easywechat\MiniProgram\Notice\Notice;
use app\easywechat\MiniProgram\QRCode\QRCode;
use app\easywechat\MiniProgram\Server\Guard;
use app\easywechat\MiniProgram\Sns\Sns;
use app\easywechat\MiniProgram\Staff\Staff;
use app\easywechat\MiniProgram\Stats\Stats;
use app\easywechat\Pimple\Container;
use app\easywechat\Pimple\ServiceProviderInterface;

/**
 * Class MiniProgramServiceProvider.
 */
class MiniProgramServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['mini_program.access_token'] = function ($pimple) {
            return new AccessToken(
                $pimple['config']['mini_program']['app_id'],
                $pimple['config']['mini_program']['secret'],
                $pimple['cache']
            );
        };

        $pimple['mini_program.encryptor'] = function ($pimple) {
            return new Encryptor(
                $pimple['config']['mini_program']['app_id'],
                $pimple['config']['mini_program']['token'],
                $pimple['config']['mini_program']['aes_key']
            );
        };

        $pimple['mini_program.server'] = function ($pimple) {
            $server = new Guard($pimple['config']['mini_program']['token']);
            $server->debug($pimple['config']['debug']);
            $server->setEncryptor($pimple['mini_program.encryptor']);

            return $server;
        };

        $pimple['mini_program.staff'] = function ($pimple) {
            return new Staff($pimple['mini_program.access_token']);
        };

        $pimple['mini_program.notice'] = function ($pimple) {
            return new Notice($pimple['mini_program.access_token']);
        };

        $pimple['mini_program.material_temporary'] = function ($pimple) {
            return new Temporary($pimple['mini_program.access_token']);
        };

        $pimple['mini_program.stats'] = function ($pimple) {
            return new Stats(
                $pimple['mini_program.access_token'],
                $pimple['config']['mini_program']
            );
        };

        $pimple['mini_program.sns'] = function ($pimple) {
            return new Sns(
                $pimple['mini_program.access_token'],
                $pimple['config']['mini_program']
            );
        };

        $pimple['mini_program.qrcode'] = function ($pimple) {
            return new QRCode(
                $pimple['mini_program.access_token'],
                $pimple['config']['mini_program']
            );
        };

        $pimple['mini_program'] = function ($pimple) {
            return new MiniProgram($pimple);
        };
    }
}
