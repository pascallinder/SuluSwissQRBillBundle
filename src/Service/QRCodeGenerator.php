<?php

namespace Linderp\SuluSwissQRBillBundle\Service;

use Sprain\SwissQrBill\DataGroup\Element\CreditorInformation;
use Sprain\SwissQrBill\DataGroup\Element\PaymentAmountInformation;
use Sprain\SwissQrBill\DataGroup\Element\PaymentReference;
use Sprain\SwissQrBill\DataGroup\Element\StructuredAddress;
use Sprain\SwissQrBill\Exception\InvalidQrBillDataException;
use Sprain\SwissQrBill\QrBill;
use Sprain\SwissQrBill\QrCode\QrCode;
use Sprain\SwissQrBill\Reference\RfCreditorReferenceGenerator;
use Sulu\Bundle\ContactBundle\Entity\Contact;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use function Symfony\Component\Clock\now;

readonly class QRCodeGenerator
{
    public function __construct(
        private QRBillConfig $qrBillConfig
    ){}

    public function generate(ContactInterface $contact, ?float $amount = null): QrBill{
        $qrBill = QrBill::create();
        $qrBill->setCreditor(
            StructuredAddress::createWithStreet(
                $this->qrBillConfig->name,
                $this->qrBillConfig->street,
                $this->qrBillConfig->buildingNumber,
                $this->qrBillConfig->postalCode,
                $this->qrBillConfig->city,
                $this->qrBillConfig->country,
            )
        );

        $qrBill->setCreditorInformation(
            CreditorInformation::create(
                $this->qrBillConfig->iban
            )
        );
        if(sizeof($contact->getAddresses())){
            $address = $contact->getAddresses()[0];
            if($address->getStreet() && $address->getZip() && $address->getCity()){
                $qrBill->setUltimateDebtor(StructuredAddress::createWithStreet(
                    $contact->getFirstName() . ' ' . $contact->getLastName(),
                    $address->getStreet(),
                    null,
                    $address->getZip(),
                    $address->getCity(),
                    'CH'
                ));
            }
        }


        $qrBill->setPaymentAmountInformation(
            PaymentAmountInformation::create(
                'CHF',
                $amount
            )
        );
        $qrBill->setPaymentReference(
            PaymentReference::create(
                PaymentReference::TYPE_SCOR,
                RfCreditorReferenceGenerator::generate($this->generateSCORReference($contact->getId()))
            )
        );
        return $qrBill;
    }

    private function generateSCORReference(int $id, ): string
    {
        return $id . (new \DateTimeImmutable())->format('Ymd');
    }
}