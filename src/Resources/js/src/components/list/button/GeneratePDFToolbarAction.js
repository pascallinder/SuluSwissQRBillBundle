import React from 'react';
import {action, observable} from 'mobx';
import {translate} from 'sulu-admin-bundle/utils';
import {Dialog, Input} from "sulu-admin-bundle/components";
import {AbstractListToolbarAction} from 'sulu-admin-bundle/views';
export default class GeneratePDFToolbarAction extends AbstractListToolbarAction {
    @observable showDialog = false;
    @observable amount = undefined;
    getToolbarItemConfig() {
        return {
            icon: 'fa-file-invoice',
            label: translate('swiss-qr-bill.list.generate-pdf'),
            onClick: this.handleToolbarButtonClick.bind(this),
            disabled: this.listStore.selectionIds.length === 0,
            type: 'button',
        };
    }
    getNode() {
        return (
            <Dialog
                cancelText={translate("sulu_admin.cancel")}
                confirmText={translate("sulu_admin.ok")}
                key="swiss-qr-bill.amount_dialog"
                onCancel={this.handleDialogCancel.bind(this)}
                onConfirm={this.handleDialogConfirm.bind(this)}
                open={this.showDialog}
                title={translate("swiss-qr-bill.dialog.title")}
            >
                {translate('swiss-qr-bill.dialog.description')}
                <Input
                    icon="su-dollar"
                    onChange={action((value)=> this.amount = value)}
                    type="text"
                    value={this.amount}
                />
            </Dialog>
        );
    }
    @action handleDialogCancel() {
        this.showDialog = false;
    }

    @action handleToolbarButtonClick() {
        this.showDialog = true;
    }

    @action async handleDialogConfirm() {
        this.showDialog = false;
        const store = this.listStore;
        const response = await fetch('/admin/api/qr-bill/generate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/zip',
            },
            body: JSON.stringify({
                ids: store.selectionIds,
                amount: isNaN(this.amount) ? parseFloat(this.amount):undefined
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
