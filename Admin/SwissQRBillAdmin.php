<?php

namespace Linderp\SuluSwissQRBillBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;

class SwissQRBillAdmin extends Admin
{

    public function __construct() {
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        if ($viewCollection->has('sulu_contact.contacts_list')) {
            $pageEditFormViewBuilder = $viewCollection->get('sulu_contact.contacts_list');
            $pageEditFormViewBuilder->addToolbarActions([
                new ToolbarAction('swiss_qr_bill.generate-pdfs', ['allow_overwrite' => true]),
            ]);
        }
    }
}
