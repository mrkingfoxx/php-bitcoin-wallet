## PHP Bitcoin Wallet

A simple PHP library for reading Bitcoin wallet.dat file.

### Installation

```bash
composer require andkom/php-bitcoin-wallet
```

### Usage

```PHP
<?php

use AndKom\Bitcoin\Wallet\Wallet;
 
// create wallet instance
$wallet = new Wallet();
$wallet->read("/path/to/wallet.dat");
 
// check if wallet is encrypted
if ($wallet->isEncrypted()) {
 
    // print wallet master key
    echo $wallet->getMasterKey()->getEncryptedKey() . "\n";
     
    // decrypt wallet
    $wallet->decrypt("password");
}
 
// print stored keys
foreach ($wallet->getKeys() as $key) {
    echo $key->getPrivateKey()->toWif() . " => " . $key->getPublicKey()->getAddress()->getAddress() . "\n";
}
```