<?php
?><html>
<head>
<title>api.for-bitcoin.com | A custodial API for Bitcoin & Lightning Network</title>
<style>
.placeholder {
color:gray;
  font-style:italic;
}
h2 {
  font-family: monospace;
  background-color: lightblue;
  padding: 5px;
}
</style>
</head>
<body>
<h1>api.for-bitcoin.com</h1>
A very basic custodial API for Bitcoin and Lightning Network. API calls are rate-limited to 30 calls per minute from the same IP address. For questions/support, send a message on telegram to @lafaire.


<?php
$rand = rand(0, 9999999);
$content = [
  [
    'title' => 'GET /register',
    'ref' => 'register',
    'shortdesc' => 'Register a new client/wallet.',
    'desc' => <<<EOT
Register a client. Specify a `client_id` string to anything you want to name this client or wallet. You will be given an access token for API calls. Be sure to keep this token a secret, and <span style="font-weight:bold;color:red">do not lose it</span>, as it protects all your funds. This token will be set as a cookie as well. The access token does not expire.
EOT
,
    'examples' => [
      [
        'url' => 'GET /register?client_id=foobar123',
        'response' => <<<EOT
{
  "client_id": "foobar123",
  "access_token": "50649a26c9259dabeadf68666769c13aa82329afaa2ec64d6b896ded240bcbbf"
}
EOT
      ]
    ],
    'tryit' => [
      'action' => 'register',
      'fields' => <<<EOT
client_id: <input type='text' name='client_id' value='foobar$rand' /> 
EOT
    ],
  ],
  [
    'title' => 'GET /balance',
    'ref' => 'balance',
    'shortdesc' => 'Get current balance.',
    'desc' => <<<EOT
Get your current balance. Be sure to specify the `access_token` as a cookie.
EOT
,
    'examples' => [
      [
        'url' => 'GET /balance',
        'response' => <<<EOT
{
  "client_id": "foobar123",
  "balance": "0.00000001",
  "balance_as_sats": 1,
  "transactions": [
    {
      "date": "...",
      "amount": "...",
      "type": "bitcoin",
      "txid": "..."
    }
  ],
  "logs": [
    "2020-02-20 08:33:22 A new address was generated.",
    "2020-02-20 08:33:22 A new address was generated.",
    "2020-02-20 08:33:22 A new address was generated.",
    "2020-02-20 08:33:22 A new address was generated."
  ]
}
EOT
      ]
    ],
    'tryit' => [
      'action' => 'balance',
      'fields' => <<<EOT
EOT
    ],
  ],
  [
    'title' => 'GET /address',
    'ref' => 'address',
    'shortdesc' => 'Generate a new Bitcoin address.',
    'desc' => <<<EOT
Get a Bitcoin address to receive payment. 
Specify either `client_id`, or use the `access_token` as a cookie. 
EOT
,
    'examples' => [
      [
        'url' => 'GET /address',
        'response' => <<<EOT
{
  "client_id": "foobar123",
  "address": "1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge",
  "qr": "https://chart.googleapis.com/?..."
}
EOT
      ]
    ],
    'tryit' => [
      'action' => 'address',
      'fields' => <<<EOT
EOT
    ],
  ],
  [
    'title' => 'GET /lightning-invoice',
    'ref' => 'lightning-invoice',
    'shortdesc' => 'Generate a new Lightning Network invoice.',
    'desc' => <<<EOT
Get a Lightning Network invoice to receive payment. 
Specify either `client_id`, or use the `access_token` as a cookie.
Use the optional `amount` parameter to specify the amount to receive. 
The amount may have a `sats` prefix to specify number of satoshi, otherwise it will assume the amount is in BTC.
EOT
,
    'examples' => [
      [
        'url' => 'GET /lightning-invoice&amount=100sats',
        'response' => <<<EOT
{
  "client_id": "foobar123",
  "invoice" : "lnbc10n1p0yatsy...",
  "amount": 0.000001,
  "amount_as_sats": 100
  "qr": "https://chart.googleapis.com/?..."
}
EOT
      ]
    ],
    'tryit' => [
      'action' => 'lightning-invoice',
      'fields' => <<<EOT
amount: <input type='text' name='amount' value='100sats' /> 
EOT
    ],
  ],
  [
    'title' => 'GET /transfer',
    'ref' => 'transfer',
    'shortdesc' => 'Transfer funds to a Bitcoin address or Lightning Network invoice.',
    'desc' => <<<EOT
Transfer some amount in your balance to a destination Bitcoin address or Lightning Network invoice.
This is a 2-step process for security reasons.
This will return a unique `transfer_key` value, which must then be sent back to the end-point. The purpose of the transfer_key step is to protect against basic CSRF attacks.
The `access_token` must be provided as a cookie.
The `amount` parameter is required, to specify the amount to transfer.
The amount may have a `sats` prefix to specify number of satoshi, otherwise it will assume the amount is in BTC. The amount may be set to the string "all" in which case it will transfer the entire balance.
EOT
,
    'examples' => [
      [
        'url' => 'GET /transfer?address=1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge&amp;amount=1sats',
        'response' => <<<EOT
{
  "client_id": "foobar123",
  "txid": "38be3b046f493b1697120dc4e4417aee5c67f8e448565cfdfd110bc903bbedad",
  "dest_address": "1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge",
  "amount": "0.00000001",
  "amount_as_sats": 1,
  "transfer_key": "1eb42705f0fc628264787639004d85ff73391bed8ebe8a44d3ef382392d1d13e",
  "status": "needs_step_2"
}
EOT
      ],
      [
        'url' => 'GET /transfer?transfer_key=1eb42705f0fc628264787639004d85ff73391bed8ebe8a44d3ef382392d1d13e',
        'response' => <<<EOT
{
  "client_id": "foobar123",
  "txid": "38be3b046f493b1697120dc4e4417aee5c67f8e448565cfdfd110bc903bbedad",
  "dest_address": "1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge",
  "amount": "0.00000001",
  "amount_as_sats": 1,
  "status": "success"
}
EOT
      ]
    ],
    'tryit' => [
      'action' => 'transfer',
      'fields' => <<<EOT
address: <input type="text" name="address" /> 
invoice: <input type='text' name='invoice' /> <br />
amount: <input type='text' name='amount' /> 
transfer_key: <input type="text" name="transfer_key" /> 
EOT
    ],
  ],
  [
    'title' => 'GET /notify',
    'ref' => 'notify',
    'shortdesc' => 'Be notified of incoming transactions.',
    'desc' => <<<EOT
Get a list of notification targets. A notification target is a way to be notified when a transaction is detected. This can be an email notification, or it can be a web callback URL. Optional parameters are `add_email`, `add_url`, and `delete_id`.
EOT
,
    'examples' => [
      [
        'url' => 'GET /notify?add_email=foo@bar.com',
        'response' => '',
      ],
      [
        'url' => 'GET /notify?add_url=https://your-website.com/transaction-callback.php',
        'response' => '',
      ],
      [
        'url' => 'GET /notify',
        'response' => <<<EOT
[
  {
    "id": 1,
    "type": "email",
    "target": "foo@bar.com"
  },
  {
    "id": 2,
    "type": "url",
    "target": "https://your-website.com/transaction-callback.php",
  }
]
EOT
      ]
    ],
    'tryit' => [
      'action' => 'notify',
      'fields' => <<<EOT
add_email: <input type="text" name="add_email" /> 
add_url: <input type='text' name='add_url' /> <br />
delete_id: <input type='text' name='delete_id' /> 
EOT
    ],
  ],
];
?>

<ul>
<?php

// Generate Table of Contents
foreach ($content as $item) {
  echo "<li><a href='#{$item['ref']}'>{$item['title']}</a> - {$item['shortdesc']}</li>";
}
?>
</ul>

<?php
// Generate Contents
foreach ($content as $item) {
  $examples = '';
  foreach ($item['examples'] as $example) {
    $examples .= <<<EOT
      <p>Example: <tt>{$example['url']}</tt></p>
      <pre>{$example['response']}</pre>
EOT;
  }
  echo <<<EOT
    <a name='{$item['ref']}' /><h2>{$item['title']}</h2>
    <p>{$item['desc']}</p>
    $examples
    <p>Try it:</p>
    <form style='border:dashed 1px gray;padding:20px' method='get' action="{$item['tryit']['action']}">
    {$item['tryit']['fields']}
    <button type='submit'>{$item['title']}</button>
    </form>
EOT;
}

?>

</body>
</html>
