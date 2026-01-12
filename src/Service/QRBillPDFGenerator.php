<?php

namespace Linderp\SuluSwissQRBillBundle\Service;

use Sprain\SwissQrBill\PaymentPart\Output\DisplayOptions;
use Sprain\SwissQrBill\PaymentPart\Output\TcPdfOutput\TcPdfOutput;
use Sprain\SwissQrBill\QrBill;

class QRBillPDFGenerator
{
    public function generate(QrBill $qrBill): string
    {
        $tcPdf = new \TCPDF('P', 'mm', 'A4', true, 'ISO-8859-1');
        $tcPdf->setPrintHeader(false);
        $tcPdf->setPrintFooter(false);
        $tcPdf->AddPage();
        $output = new TcPdfOutput($qrBill, 'en', $tcPdf);
        $displayOptions = new DisplayOptions();
        $displayOptions
            ->setPrintable(false) // true to remove lines for printing on a perforated stationery
            ->setDisplayTextDownArrows(false) // true to show arrows next to separation text, if shown
            ->setDisplayScissors(false) // true to show scissors instead of separation text
            ->setPositionScissorsAtBottom(false);
        $output
            ->setDisplayOptions($displayOptions)
            ->getPaymentPart();
        return $tcPdf->Output('', 'S');
    }
}