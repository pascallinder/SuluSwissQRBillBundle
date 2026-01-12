import GeneratePDFToolbarAction from "./components/list/button/GeneratePDFToolbarAction";
import {listToolbarActionRegistry} from 'sulu-admin-bundle/views';
listToolbarActionRegistry.add('swiss_qr_bill.generate-pdfs',GeneratePDFToolbarAction)