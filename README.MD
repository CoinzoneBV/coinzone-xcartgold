# coinzone-xcart

#  About

  * Bitcoin payment via coinzone.com for X-Cart Gold.

#  System Requirements

  * [Merchant Account](https://merchant.coinzone.com/signup?source=xcartgold)
  * [X-Cart Gold](http://kb.x-cart.com/display/XDD/Installation+Guide#InstallationGuide-Serverrequirements)
  * PHP 5.3+
  * Curl PHP Extension
  * JSON PHP Extension

#  Configuration

The current version of the x-cart plugin is version 1.0. It is available either by
cloning this repository or downloading the .zip file from the
[download page](https://github.com/CoinzoneBV/coinzone-xcartgold/archive/master.zip).

1. Upload files to your X-Cart installation.

2. Run the `install.sql` provided on your X-Cart installation Mysql server.

3. Go to your X-Cart administration. Settings -&gt; Payment Methods -&gt; Payment Gateways. Select `All Countries`, select `Coinzone` for payment gateway & click [Add]

4. Go to Payment methods, find `Coinzone` & click [Configure].

5. Fill in your Client Code and API Key. They can be found on the API tab of the Settings page in the [Coinzone Control Panel](https://merchant.coinzone.com/settings#apiTab). Also set the currency your store uses.

6. Go to  Settings -&gt; Payment Methods, find `Coinzone` and check to enable the payment followed by [Apply Changes].

7. Don't have a Coinzone account? [Sign up for free](https://merchant.coinzone.com/signup?source=xcartgold)

#  Support

##  Coinzone Support

  * Have questions? Please visit our [Customer Support Site](http://support.coinzone.com/).

##  X-Cart Support

  * [Homepage](http://www.x-cart.com/)
  * [Documentation](http://kb.x-cart.com/display/XDD/Developer+docs)
  * [Support](http://www.x-cart.com/help.html)
