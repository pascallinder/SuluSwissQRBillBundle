<?php
namespace Linderp\SuluSwissQRBillBundle\Service;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class QRBillConfig{
    public function __construct(
        #[Autowire('%sulu_swiss_qr_bill.iban%')]
        public string $iban,

        #[Autowire('%sulu_swiss_qr_bill.name%')]
        public string $name,

        #[Autowire('%sulu_swiss_qr_bill.street%')]
        public string $street,

        #[Autowire('%sulu_swiss_qr_bill.buildingNumber%')]
        public ?string $buildingNumber,

        #[Autowire('%sulu_swiss_qr_bill.postalCode%')]
        public int $postalCode,

        #[Autowire('%sulu_swiss_qr_bill.city%')]
        public string $city,

        #[Autowire('%sulu_swiss_qr_bill.country%')]
        public string $country,
    ) {}
}