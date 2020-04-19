<?php

namespace App\Http\Utilities;

use App\Http\Models\PaidServiceModel;
use App\Http\Models\ProfileModel;
use App\Http\Models\UserModel;
use Illuminate\Support\Facades\Log;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\ThreedsInitialize;
use Iyzipay\Model\ThreedsPayment;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\CreateThreedsPaymentRequest;

class Iyzico {

    private $options;

    public function __construct() {
        $this->setOptions();
    }

    public function getOptions() { return $this->options; }

    public function setOptions(): void {
        $this->options = new Options();
        $this->options->setApiKey(env('IYZICO_API_KEY'));
        $this->options->setSecretKey(env('IYZICO_SECRET_KEY'));
        $this->options->setBaseUrl(env('IYZICO_BASE_URL'));
    }

    public function confirmPayment($response) {
        Log::info('User: ' . $response['conversationId'] . ' starts confirm payment');
        $request = new CreateThreedsPaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($response['conversationId']);
        $request->setPaymentId($response['paymentId']);
        $request->setConversationData($response['conversationData']);
        $message = $this->mdStatusMessage($response['mdStatus']);

        $threeDSPayment = ThreedsPayment::create($request, $this->getOptions());
        if ($threeDSPayment->getErrorCode()) {
            Log::error('IYZICO says: user ' . $response['conversationId'] . ' => ' . $threeDSPayment->getErrorMessage());
            return '<div align="center" style="width: 100%; height: calc(100% - 64px); background: #fbfbfb; padding: 32px 0; font-family: Ubuntu, sans-serif;">
                <h3 style="color: #f44336;">Hatalı</h3>
                <h2 style="color: #303030; font-weight: 100;">' . $message .'</h2>
                </div>';
        } else {
            $paidService = new PaidServiceModel();
            $paidService->upgradePaidService($response['conversationId'], $threeDSPayment->getPaymentItems());
            return '<div align="center" style="width: 100%; height: calc(100% - 64px); background: #fbfbfb; padding: 32px 0; font-family: Ubuntu, sans-serif;">
                <h3 style="color: #5FDC96;">Başarılı</h3>
                <h2 style="color: #303030; font-weight: 100;">Paket satın alma işlemi başarıyla sonuçlandı<br/>Devam etmek için pencereyi kapatabilirsiniz.</h2>
                </div>';
        }
    }

    public function startThreeDSInitialize(UserModel $user, ProfileModel $profile, $paymentDetail, $ipAddress) {
        Log::info('User: ' . $user->getIdentifier() . ' starts 3DS initialize');
        $request = $this->payment($paymentDetail[PRICE], $user->getIdentifier());

        $paymentCard = $this->paymentCard($paymentDetail);
        $request->setPaymentCard($paymentCard);

        $buyer = $this->buyer($user, $profile, $ipAddress);
        $request->setBuyer($buyer);

        $billingAddress = $this->billingAddress($user, $profile);
        $request->setBillingAddress($billingAddress);

        $basketItems = $this->basketItems($paymentDetail);
        $request->setBasketItems($basketItems);

        $threeDSInitialize = ThreedsInitialize::create($request, $this->getOptions());
        if ($threeDSInitialize->getErrorCode()) {
            Log::error('IYZICO says: user ' . $user->getIdentifier() . ' => ' . $threeDSInitialize->getErrorMessage());
        } else {
            Log::info('IYZICO says: user ' . $user->getIdentifier() . ' => successfully initialized payment');
            return $threeDSInitialize->getHtmlContent();
        }
    }

    /**
     * @param $paymentDetail
     * @return array
     */
    private function basketItems($paymentDetail): array {
        $basketItems = array();
        foreach ($paymentDetail[PACKAGES] as $packageKey) {
            $package = Packages::getPackage($packageKey);
            $basketItem = new BasketItem();
            $basketItem->setId($package[KEY]);
            $basketItem->setName($package[KEY]);
            $basketItem->setCategory1("Paid Services");
            $basketItem->setItemType(BasketItemType::VIRTUAL);
            $basketItem->setPrice($package[PRICE]);
            array_push($basketItems, $basketItem);
        }
        return $basketItems;
    }

    /**
     * @param UserModel $user - holds the user detail.
     * @param ProfileModel $profile - holds the profile detail of user.
     * @return Address
     */
    private function billingAddress(UserModel $user, ProfileModel $profile): Address {
        $billingAddress = new Address();
        $billingAddress->setContactName($user->getName() . ' ' . $user->getSurname());
        $billingAddress->setCity($profile->getCity());
        $billingAddress->setCountry($profile->getCountry());
        $billingAddress->setAddress($profile->getAddress());
        return $billingAddress;
    }

    /**
     * @param UserModel $user - holds the user detail.
     * @param ProfileModel $profile - holds the user`s profile detail.
     * @param $ipAddress - holds the current user`s ip.
     * @return Buyer
     */
    private function buyer(UserModel $user, ProfileModel $profile, $ipAddress): Buyer {
        $buyer = new Buyer();
        $buyer->setId($user->getIdentifier());
        $buyer->setName($user->getName());
        $buyer->setSurname($user->getSurname());
        $buyer->setEmail($user->getEmail());
        $buyer->setIdentityNumber($user->getIdentityNumber());
        $buyer->setGsmNumber($profile->getPhone());
        $buyer->setRegistrationAddress($profile->getAddress());
        $buyer->setIp($ipAddress);
        $buyer->setCity($profile->getCity());
        $buyer->setCountry($profile->getCountry());
        return $buyer;
    }

    /**
     * @param string $price - holds the payed price.
     * @param $key - holds the key for iyzico.
     * @return CreatePaymentRequest
     */
    private function payment(string $price, $key): CreatePaymentRequest {
        $request = new CreatePaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($key);
        $request->setPrice($price);
        $request->setPaidPrice($price);
        $request->setCurrency(Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId("NO_BASKET_ID");
        $request->setPaymentChannel(PaymentChannel::WEB);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl(env('HOST_NAME') . '/api/v1/deposit_confirmation');
        return $request;
    }

    /**
     * @param $card - holds the card detail.
     * @return PaymentCard
     */
    private function paymentCard($card): PaymentCard {
        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($card[CARD_HOLDER_NAME]);
        $paymentCard->setCardNumber($card[CARD_NUMBER]);
        $paymentCard->setExpireMonth($card[EXPIRE_MONTH]);
        $paymentCard->setExpireYear($card[EXPIRE_YEAR]);
        $paymentCard->setCvc($card[CVC]);
        $paymentCard->setRegisterCard(0);
        return $paymentCard;
    }

    private function mdStatusMessage($mdStatus) {
        $message = '';
        if (number_format($mdStatus) == 0) {
            $message = '3-D Secure imzası geçersiz veya doğrulama';
        } else if (number_format($mdStatus) == 2) {
            $message = 'Kart sahibi veya bankası sisteme kayıtlı değil';
        } else if (number_format($mdStatus) == 3) {
            $message = 'Kartın bankası sisteme kayıtlı değil';
        } else if (number_format($mdStatus) == 4) {
            $message = 'Doğrulama denemesi, kart sahibi sisteme daha sonra kayıt olmayı seçmiş';
        } else if (number_format($mdStatus) == 5) {
            $message = 'Doğrulama yapılamıyor';
        } else if (number_format($mdStatus) == 6) {
            $message = '3-D Secure hatası';
        } else if (number_format($mdStatus) == 7) {
            $message = 'Sistem hatası';
        } else if (number_format($mdStatus) == 8) {
            $message = 'Bilinmeyen kart no';
        }
        return $message;
    }

}
