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

    public function generate(ContactInterface $contact): QrBill{
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
        if($contact->getMainAddress() && $contact->getMainAddress()?->getStreet()
        && $contact->getMainAddress()->getZip() && $contact->getMainAddress()->getCity()
        && $contact->getMainAddress()->getCountry()){
            $qrBill->setUltimateDebtor(StructuredAddress::createWithoutStreet(
                $contact->getFirstName() . ' ' . $contact->getLastName(),
                $contact->getMainAddress()->getStreet(),
                null,
                $contact->getMainAddress()->getZip(),
            ));
        }

        $qrBill->setPaymentAmountInformation(
            PaymentAmountInformation::create(
                'CHF'
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