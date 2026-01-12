<?php

namespace Linderp\SuluSwissQRBillBundle\Controller\Admin;
use Linderp\SuluSwissQRBillBundle\Service\PdfZipper;
use Linderp\SuluSwissQRBillBundle\Service\QRBillPDFGenerator;
use Linderp\SuluSwissQRBillBundle\Service\QRCodeGenerator;
use Sulu\Bundle\ContactBundle\Entity\Contact;
use Sulu\Bundle\ContactBundle\Entity\ContactRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class ContactQRBillGenerator extends AbstractController{
    public function __construct(private readonly QRBillPDFGenerator $qrBillPDFGenerator,
                                private readonly ContactRepositoryInterface $contactRepository,
                                private readonly QRCodeGenerator $qrCodeGenerator,
                                private readonly PdfZipper $pdfZipper){}
    #[Route(path: '/admin/api/qr-bill/generate', name: 'app.qr-bill.generate', methods: ['POST'])]
    public function generate(Request $request): BinaryFileResponse
    {
        /** @var Contact[] $contacts */
        $contacts = $this->contactRepository->findByIds($request->getPayload()->all('ids'));
        $files = [];
        foreach ($contacts as $contact){
            $qrBill = $this->qrCodeGenerator->generate($contact);
            $files[strtolower($contact->getFirstName().'_'.$contact->getLastName().'.pdf')] =
                $this->qrBillPDFGenerator->generate($qrBill);
        }
        $response = new BinaryFileResponse($this->pdfZipper->zip($files));
        $response->headers->set('Content-Type', 'application/zip');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'qr-bills.zip'
        );
        $response->deleteFileAfterSend();
        return $response;
    }
}
