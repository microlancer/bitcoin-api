<?php
?><html>
<head>
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
A very basic custodial API for Bitcoin and Lightning Network. API calls are rate-limited to 30 calls per minute from the same IP address.

<h2>GET /register?client_id=<span class='placeholder'>somename</span></h2>

<p>Register a client. You will be given an access token for API calls. Be sure to keep this token a secret, and do not lose it, as it protects all your funds. This token will be set as a cookie as well. The access token does not expire.</p>

<p>Example: <tt>GET /register?client_id=foobar123</tt></p>

<p>Response:</p>

<pre>
{
  "client_id": "foobar123",
  "access_token": "50649a26c9259dabeadf68666769c13aa82329afaa2ec64d6b896ded240bcbbf"
}
</pre>

<p>Try it:</p>

<form style='border:dashed 1px gray;padding:20px' method='get' action="register">client_id: <input type='text' name='client_id' value='foobar<?=rand(0,999999)?>' /> <button type='submit'>GET /register</button></form>

<h2>GET /balance</h2>

Get your current balance. Be sure to specify the `access_token` as a cookie.

<p>Example: <tt>GET /balance</tt></p>

<pre>
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
</pre>

<p>Try it:</p>

<form style='border:dashed 1px gray;padding:20px' method='get' action="balance"><button type='submit'>GET /balance</button></form>


<h2>GET /address?client_id=<span class="placeholder">somename</span></h2>

Get a Bitcoin address to receive payment. 
Specify either `client_id`, or use the `access_token` as a cookie. 

<p>Example: <tt>GET /address</tt></p>

<pre>
{
  "client_id": "foobar123",
  "address": "1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge",
  "qr": "https://chart.googleapis.com/?..."
}
</pre>

<p>Try it:</p>

<form style='border:dashed 1px gray;padding:20px' method='get' action="address"><button type='submit'>GET /address</button></form>

<h2>GET /lightning-invoice?client_id=<span class="placeholder">somename</span>&amount=<span class="placeholder">someamount</span></h2>

Get a Lightning Network invoice to receive payment. 
Specify either `client_id`, or use the `access_token` as a cookie.
Use the optional `amount` parameter to specify the amount to receive. 
The amount may have a `sats` prefix to specify number of satoshi, otherwise it will assume the amount is in BTC.

<p>Example: <tt>GET /lightning-invoice?amount=100sats</tt></p>

<pre>
{
  "client_id": "foobar123",
  "invoice" : "lnbc10n1p0yatsypp5c9ah29gx7cclqjq8g68h0mcwe6mqdeusgag009s8hl3r8upu8ttqdrctdxkjcmjdakxzmnrv4ezu6t0t5s9qcted4jkuapqdanzqvfqwdshgmmndp5jqctnypsjqcn0daehggr5dus8qmmnwsszxwpeyp38jgzdd93hymmvv9hxxetjxqzuycqp2rzjqvs9kpzmqynvp5lmsf4r3wd7l396hagk392yjzx8qnvw2877ca0lkzy5hsqqf2qqqyqqqq05qqqqqzsqrcts4mytvhu9ppk7ps0cutxh454mpnsxe2pqf307h4xc2u5dqrqyxqgen3rvh9dkk89me7uzq3tw3rqwwrafd6c3sp62asxtvpzach06sq5dxmjf",
  "amount": 0.000001,
  "amount_as_sats": 100
  "qr": "https://chart.googleapis.com/?..."
}
</pre>

<p>Try it:</p>

<form style='border:dashed 1px gray;padding:20px' method='get' action="lightning-invoice">amount: <input type='text' name='amount' value='100sats' /> <button type='submit'>GET /lightning-invoice</button></form>

<h2>GET /transfer?address=<span class="placeholder">someaddress</span>&amount=<span class="placeholder">someamount</span></h2>

Transfer some amount in your balance to a destination Bitcoin address or Lightning Network invoice.
This is a 2-step process for security reasons.
This will return a unique `transfer_key` value, which must then be sent back to the end-point. The purpose of the transfer_key step is to protect against basic CSRF attacks.
The `access_token` must be provided as a cookie.
The `amount` parameter is required, to specify the amount to transfer.
The amount may have a `sats` prefix to specify number of satoshi, otherwise it will assume the amount is in BTC. The amount may be set to the string "all" in which case it will transfer the entire balance.

<p>Example: <tt>GET /transfer?address=1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge&amp;amount=1sats</tt></p>

<pre>
{
  "client_id": "foobar123",
  "txid": "38be3b046f493b1697120dc4e4417aee5c67f8e448565cfdfd110bc903bbedad",
  "dest_address": "1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge",
  "amount": "0.00000001",
  "amount_as_sats": 1,
  "transfer_key": "1eb42705f0fc628264787639004d85ff73391bed8ebe8a44d3ef382392d1d13e",
  "status": "needs_step_2"
}
</pre>

<p>Example: <tt>GET /transfer?transfer_key=1eb42705f0fc628264787639004d85ff73391bed8ebe8a44d3ef382392d1d13e</tt></p>

<pre>
{
  "client_id": "foobar123",
  "txid": "38be3b046f493b1697120dc4e4417aee5c67f8e448565cfdfd110bc903bbedad",
  "dest_address": "1Hx4QLvKgVDmNnujoDanRrktsycY4aqWge",
  "amount": "0.00000001",
  "amount_as_sats": 1,
  "status": "complete"
}
</pre>

<p>Try it:</p>

<form style='border:dashed 1px gray;padding:20px' method='get' action="transfer">
address: <input type="text" name="address" /> 
invoice: <input type='text' name='invoice' /> <br />
amount: <input type='text' name='amount' /> 
transfer_key: <input type="text" name="transfer_key" /> 
<button type='submit'>GET /transfer</button></form>

<h2>GET /notify</h2>

Get a list of notification targets. A notification target is a way to be notified when a transaction is detected. This can be an email notification, or it can be a web callback URL. Optional parameters are `add_email`, `add_url`, and `delete_id`.

<p>Example: <tt>GET /notify?add_email=foo@bar.com</tt></p>

<p>Example: <tt>GET /notify?add_url=https://your-website.com/transaction-callback.php</tt></p>

<p>Example: <tt>GET /notify</tt></p>

<pre>
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
</pre>

<p>Try it:</p>

<form style='border:dashed 1px gray;padding:20px' method='get' action="notify">
add_email: <input type="text" name="add_email" /> 
add_url: <input type='text' name='add_url' /> <br />
delete_id: <input type='text' name='delete_id' /> 
<button type='submit'>GET /notify</button></form>

</body>
</html>
