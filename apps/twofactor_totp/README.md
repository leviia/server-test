# Two Factor Totp
![Downloads](https://img.shields.io/github/downloads/nextcloud/twofactor_totp/total.svg)
[![Build Status](https://travis-ci.org/nextcloud/twofactor_totp.svg?branch=master)](https://travis-ci.org/nextcloud/twofactor_totp)

[![Sauce Test Status](https://saucelabs.com/browser-matrix/nextcloud-totp.svg)](https://saucelabs.com/u/nextcloud-totp)

Tested with the following apps:
* [andOTP](https://github.com/andOTP/andOTP) (open source) Availabe via [F-Droid](https://f-droid.org/packages/org.shadowice.flocke.andotp/) and [Google Play](https://play.google.com/store/apps/details?id=org.shadowice.flocke.andotp). It features a built-in QR-code reader.
* [FreeOTPPlus](https://github.com/helloworld1/FreeOTPPlus/) (open source) Availabe via [F-droid](https://f-droid.org/packages/org.liberty.android.freeotpplus/) and [Google Play](https://play.google.com/store/apps/details?id=org.liberty.android.freeotpplus).
* [OTP Authenticator](https://github.com/0xbb/otp-authenticator) (open source) Availabe via [F-Droid](https://f-droid.org/en/packages/net.bierbaumer.otp_authenticator/) and [Google Play](https://play.google.com/store/apps/details?id=net.bierbaumer.otp_authenticator). It features a built-in QR-code reader.
* [Google Authenticator](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2) (proprietary)
* [KeePassXC (Linux, Windows, macOS)](https://keepassxc.org/) (open-source) Available via [download](https://keepassxc.org/download/), package repositories or [GitHub](http://www.github.com/keepassxreboot/keepassxc/) (Keepass also provides a plugin and Keepass2Android allows to use TOTP token)
* [SailOTP (SailfishOS)](https://github.com/seiichiro0185/sailotp) (open source) Available via JollaStore or [Openrepos.net](https://openrepos.net/content/seiichiro0185/sailotp)
* [OTP Auth](https://cooperrs.de/otpauth.html) (proprietary) Availabe via [Apple's App Store](https://itunes.apple.com/us/app/otp-auth/id659877384)

Tested with the following hardware devices:
* [Nitrokey Pro](https://shop.nitrokey.com/shop/product/nitrokey-pro-2-3)
* [Nitrokey Storage](https://shop.nitrokey.com/shop)

## Enabling TOTP 2FA for your account
![](screenshots/enter_challenge.png)
![](screenshots/settings.png)

## Login with external apps
Once you enable OTP with Two Factor Totp, your aplications (for example your Android app or your GNOME app) will need to login using device passwords. To manage it, [know more here](https://docs.nextcloud.com/server/stable/user_manual/en/session_management.html#managing-devices)
