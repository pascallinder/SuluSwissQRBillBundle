import {action} from 'mobx';
import {translate} from 'sulu-admin-bundle/utils';
import {AbstractListToolbarAction} from 'sulu-admin-bundle/views';
import {Requester} from "sulu-admin-bundle/services";
export default class GeneratePDFToolbarAction extends AbstractListToolbarAction {
    getToolbarItemConfig() {
        return {
            icon: 'fa-file-invoice',
            label: translate('swiss-qr-bill.generate-pdf'),
            onClick: this.handleClick,
            type: 'button',
        };
    }

    @action handleClick = async () => {
        const store = this.listStore;
            const response = await fetch('/admin/api/qr-bill/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/zip',
                },
                body: JSON.stringify({
                    ids: store.selectionIds,
                }),
            })

            if (!response.ok) {
                throw new Error('Failed to generate ZIP');
            }

            const blob = await response.blob();

            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');

            a.href = url;
            a.download = 'qr-bills.zip';
            document.body.appendChild(a);
            a.click();

            a.remove();
            URL.revokeObjectURL(url);
    };
}
