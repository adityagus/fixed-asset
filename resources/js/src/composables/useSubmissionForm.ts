import { watchEffect } from 'vue'

export function useSubmissionForm({
    submissionRef,
    formType,
    formData,
    defaultFormData,
    isDraft,
    approvalLayers,
    selectedPR,
    selectedPO,
    currentUser,
    userDataRef,
    notedDataRef,
    notesHistory,
}) {
    console.log('useSubmissionForm initialized for formType:', formType.value)
    watchEffect(() => {
        if (!submissionRef.value || !submissionRef.value.data) return

        const data = submissionRef.value.data
        currentUser.value = userDataRef.value
        console.log(
            'Current User in useSubmissionForm:',
            currentUser.value.username,
            data.created_by
        )

        // Kondisi per tipe form
        if (formType.value === 'purchase-request') {
            isDraft.value =
                (data.status === 'Draft' || data.status === 'Revised') &&
                data.created_by == currentUser.value.username
            // --- usePurchaseRequest ---
            console.log('purchase request loaded : ', data)

            formData.value.formNumber = data.pr_number || ''
            formData.value.cabang = data.cabang || ''
            formData.value.requestedBy = data.created_by || ''
            formData.value.department = data.department || ''
            formData.value.justification = data.justification || ''
            formData.value.status = data.status || 'Draft'
            formData.value.notes = data.notes || []
            approvalLayers.value = data.approvals
            if (
                Array.isArray(data.purchase_request_items) &&
                data.purchase_request_items.length > 0
            ) {
                formData.value.items = data.purchase_request_items.map(
                    (item: any) => ({
                        item_id: item.item_master.id || '',
                        quantity: item.quantity || 1,
                        category: item.item_master.category.nama_katbrg || '',
                        additional_information: item.item_master.ket_brg || '',
                        unit_price: item.unit_price || 0,
                        total_price: item.total_price || 0,
                        purchase_request_number:
                            item.purchase_request_number || '',
                    })
                )
            } else {
                formData.value.items = defaultFormData().items
            }
            console.log('Notes loaded for PR:', notedDataRef)
            if (notedDataRef.value) {
                notesHistory.value = notedDataRef.value.map((note) => ({
                    text: note.text,
                    time: new Date(note.time).toLocaleString(),
                    sender: note.sender,
                }))
            }
        } else if (formType.value === 'purchase-order') {
            // --- usePurchaseOrder ---
            isDraft.value =
                (data.status === 'Draft' || data.status === 'Revised') &&
                data.created_by == 'ronaldo'

            if (data.status.toLowerCase() !== 'draft') {
                console.log('purchase order loaded : ', data)
                selectedPR.value = data.purchase_request_number
                // reset items dulu
                formData.value.items = []
                formData.value.formNumber = data.po_number
                formData.value.cabang = data.purchase_request.cabang
                formData.value.requestedBy = data.purchase_request.created_by
                formData.value.justification =
                    data.purchase_request.justification
                formData.value.department = data.purchase_request.department
                formData.value.status = data.status
                formData.value.requestDate = data.po_date
                formData.value.vendor_id = data.vendor_id
                formData.value.prReference = selectedPR.value
                approvalLayers.value = data.approvals
                formData.value.items = data.purchase_order_items.map(
                    (item: any) => ({
                        item_id: item.item_master.id || '',
                        quantity: item.quantity || 1,
                        category: item.item_master.category.nama_katbrg || '',
                        additional_information: item.item_master.ket_brg || '',
                        unit_price: item.unit_price,
                        total_price: item.total_price,
                        purchase_request_number: item.purchase_request_number,
                    })
                )
            } else {
                formData.value.formNumber = data.po_number || ''
                formData.value.cabang = data.cabang || ''
                formData.value.requestedBy = data.created_by || ''
                formData.value.department = data.department || ''
                formData.value.justification = data.justification || ''
                formData.value.status = data.status || 'Draft'
                formData.value.notes = data.notes || []
                isDraft.value = formData.value.status === 'Draft'
                approvalLayers.value = data.approvals
                if (
                    Array.isArray(data.purchase_request_items) &&
                    data.purchase_request_items.length > 0
                ) {
                    formData.value.items = data.purchase_request_items.map(
                        (item: any) => ({
                            item_id: item.item_master.id || '',
                            quantity: item.quantity || 1,
                            category:
                                item.item_master.category.nama_katbrg || '',
                            additional_information:
                                item.item_master.ket_brg || '',
                            unit_price: item.unit_price || 0,
                            total_price: item.total_price || 0,
                            purchase_request_number:
                                item.purchase_request_number || '',
                        })
                    )

                    console.log('PO Items loaded:', formData.value.items)
                } else {
                    formData.value.items = defaultFormData().items
                }
            }
            return null
        } else if (formType.value === 'registration-asset') {
            // --- useRegistrationAsset ---
            if (data.purchase_order_number !== null) {
                console.log('registration asset loaded : ', data)
                isDraft.value =
                    (data.status === 'Draft' || data.status === 'Revised') &&
                    data.created_by == currentUser.value.username

                selectedPO.value = data.purchase_order_number
                console.log('Selected PO from RA:', selectedPO.value)
                formData.value.formNumber = data.ra_number || ''
                formData.value.cabang =
                    data.purchase_order.purchase_request.cabang || ''
                formData.value.requestedBy =
                    data.purchase_order.purchase_request.created_by || ''
                formData.value.department =
                    data.purchase_order.purchase_request.department || ''
                formData.value.justification =
                    data.purchase_order.purchase_request.justification || ''
                formData.value.invoiceDate = ''
                formData.value.requestDate = ''
                formData.value.poReference = selectedPO.value
                formData.value.prReference =
                    data.purchase_order.purchase_request.pr_number
                formData.value.vendor_id = data.purchase_order.vendor_id || ''
                // formData.value.formDate = data.ra_date || '';
                // formData.value.invoice_date = data.invoice_date || '';
                formData.value.invoiceDate = data.invoice_date || ''
                formData.value.requestDate = data.ra_date || ''
                formData.value.status = data.status || 'Draft'
                formData.value.notes = data.notes || []
                approvalLayers.value = data.approvals
                if (
                    Array.isArray(data.registration_asset_items) &&
                    data.registration_asset_items.length > 0
                ) {
                    formData.value.items = data.registration_asset_items.map(
                        (item: any) => ({
                            item_id: item.item_master.id || '',
                            quantity: item.quantity || 1,
                            category:
                                item.item_master.category.nama_katbrg || '',
                            additional_information:
                                item.item_master.ket_brg || '',
                            unit_price: item.unit_price || 0,
                            total_price: item.total_price || 0,
                            purchase_request_number:
                                item.purchase_request_number || '',
                            location: item.location || '',
                        })
                    )
                } else {
                    formData.value.items = defaultFormData().items
                }
            } else {
                formData.value.formNumber = data.ra_number || ''
                formData.value.cabang = data.cabang || ''
                formData.value.requestedBy = data.created_by || ''
                formData.value.department = data.department || ''
                formData.value.justification = data.justification || ''
                formData.value.invoice_date = data.invoice_date || ''
                formData.value.requestDate = data.ra_date || ''
                formData.value.vendor_id = data.vendor_id || ''
                formData.value.status = data.status || 'Draft'
                formData.value.notes = data.notes || []
                isDraft.value = formData.value.status === 'Draft'
                approvalLayers.value = data.approvals
            }
        }
    })
}
