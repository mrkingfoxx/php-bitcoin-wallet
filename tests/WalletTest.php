<?php

declare(strict_types=1);

namespace AndKom\Bitcoin\Wallet\Tests;

use AndKom\Bitcoin\Wallet\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    public function testParse()
    {
        $wallet = new Wallet();
        $wallet->read(__DIR__ . '/data/wallet.dat');

        $keys = $wallet->getKeys();

        $key = \reset($keys);

        $this->assertEquals($key->getPrivateKey()->toWif(), 'L1uaD1GSyvL78gRkBgMggLSUYkMrULFVGeS9wTxhLcBJN83HYRF3');
        $this->assertEquals($key->getPublicKey()->getPubKeyHash()->getHex(), '3f89ab613c2e51e0254513e1b3305dab0be0a8a4');
    }

    public function testMasterKey()
    {
        $wallet = new Wallet();
        $wallet->read(__DIR__ . '/data/wallet_encrypted.dat');

        $keys = $wallet->getKeys();
        $key = \end($keys);
        $mk = $wallet->getMasterKey();

        $this->assertEquals(bin2hex($mk->getEncryptedKey()), '80bb7a5985fd80e71c4b7f1601ce8fd7681a195c345695c6e87396eb7f8aefbf4e098ed009a42a173bd6db863c24d464');
        $this->assertEquals(bin2hex($mk->getSalt()), '98313fb978e6ef49');
        $this->assertEquals($mk->getDerivationMethod(), 0);
        $this->assertEquals($mk->getDerivationIterations(), 196349);
        $this->assertEquals($mk->getHash($key), '$bitcoin$64$681a195c345695c6e87396eb7f8aefbf4e098ed009a42a173bd6db863c24d464$16$98313fb978e6ef49$196349$96$efe4244d839af470418ee08b278ffd20510dcd105bd1aa3de016bf59c35f99b8537222a0e4a8ea1db2b6b795de697785$66$03f4c3e512b84d950cf7568966a89c9048526076a2654c907a43db8fb8f38db508');
    }

    public function testDecrypt()
    {
        $wallet = new Wallet();
        $wallet->read(__DIR__ . '/data/wallet_encrypted.dat');
        $wallet->decrypt('test');

        $keys = $wallet->getKeys();
        $key = \reset($keys);

        $this->assertEquals($key->getPrivateKey()->toWif(), 'Kz1MJgnRAmUoeWq6gVwEmeCy1ykKPjNbDK9bcDbCUMipSLMKnwrm');
        $this->assertEquals($key->getPublicKey()->getPubKeyHash()->getHex(), 'ac1e83e60984cbd690d3b439f37cff88c38413e7');
    }

    public function testVersion()
    {
        $wallet = new Wallet();
        $wallet->read(__DIR__ . '/data/wallet.dat');

        $this->assertEquals($wallet->getAttributes()['version'], 160100);
        $this->assertEquals($wallet->getAttributes()['minversion'], 159900);
    }
}