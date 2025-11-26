import { useMutation, useQueryClient } from '@tanstack/vue-query'
// import { createSubmission } from './api';
import {
    deleteSubmission,
    getSubmission,
    postSendNote,
    postSubmission,
} from './api/submissionService'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import { postUploadFile } from './api/masterService'
import {
    saveDraftPurchaseRequest,
    submitPurchaseRequest,
} from './api/purchaseRequestService'
import {
    PurchaseRequest,
    SubmitPurchaseRequestPayload,
} from '@/types/purchaseRequest'
import { axiosInstance } from '@/utils/axios'
import { SubmitPurchaseOrderPayload } from '@/types/purchaseOrders'
import { submitPurchaseOrder } from './api/purchaseOrderService'
import { SubmitRegisterAssetPayload } from '@/types/registerAsset'
import { submitRegistrationAsset } from './api/registerAssetService'

// const queryClient = useQueryClient();
// export const useCreateSubmission = () => {

//   return useMutation({
//     mutationFn: getSubmission,
//     onSuccess: () => {
//       // Invalidate and refetch
//       queryClient.invalidateQueries(['submissions']);
//     },
//     onError: (error) => {
//       console.error('Error creating submission:', error);
//     },
//     onMutate(variables, context) {
//       console.log('Creating submission with variables:', variables);
//       return context;
//     },
//     onSettled() {
//       console.log('Create submission mutation settled');
//     }
//   });
// }
const router = useRouter()

export const useCreateFormSubmission = () => {
    const queryClient = useQueryClient()

    return useMutation({
        mutationFn: (typeFormat) => postSubmission(typeFormat),
        onSuccess: (res) => {
            console.log('Form submission created successfully', res)
            window.location.href = `/apps/form/${res.type}/${
                res.data.pr_number || res.data.po_number || res.data.ra_number
            }`

            // router.push('/apps/form-builder/' + res.data.pr_number);
            // Invalidate and refetch
            // queryClient.invalidateQueries(['submissions']);
        },
        onError: (error) => {
            console.error('Error creating form submission:', error)
        },
        onSettled: async (_, error) => {
            if (error) {
                console.error('Error creating form submission:', error)
            } else {
                // await queryClient.invalidateQueries(['submissions']);
            }
            console.log('Create form submission mutation settled')
        },
    })
}

export const useDeleteSubmission = () => {
    const queryClient = useQueryClient()
    return useMutation({
        mutationFn: ({ type, number }) => {
            console.log('useDeleteSubmission called with:', type, number)
            return deleteSubmission(type, number)
        },
        onSuccess: async () => {
            await queryClient.invalidateQueries(['submissions'])
            Swal.fire('Deleted!', `Submission has been deleted.`, 'success')
        },
        onError: (error) => {
            console.error('Error deleting submission:', error)
        },
        onSettled: () => {
            console.log('Delete submission mutation settled')
        },
    })
}

export const useUploadFileSubmission = (type: string) => {
    return useMutation({
        mutationFn: (formData: FormData) => postUploadFile(type, formData),
    })
}

export const useSubmitPurchaseRequest = () => {
    const qc = useQueryClient()
    // console.log('useSubmitPurchaseRequest called', formData);
    return useMutation({
        mutationFn: async (formData: SubmitPurchaseRequestPayload) =>
            await submitPurchaseRequest(formData),
        onSuccess: (data, variable) => {
            console.log('Purchase Request submitted successfully:', data)
            qc.invalidateQueries(['PurchaseRequestIds'])

            Swal.fire({
                title: 'Sukses!',
                text: `Permintaan Pembelian (${variable.formNumber}) telah berhasil dikirim!`,
                icon: 'success',
                confirmButtonText: 'OK',
            })
        },
        onError: (error) => {
            console.error('Error submitting Purchase Request:', error)
        },
        onSettled: () => {
            console.log('Submit Purchase Request mutation settled')
        },
    })
}

export const useSubmitPurchaseOrder = () => {
    const qc = useQueryClient()
    return useMutation({
        mutationFn: async (formData: SubmitPurchaseOrderPayload) =>
            await submitPurchaseOrder(formData),
        onSuccess: (data, variable) => {
            console.log('Purchase Order submitted successfully:', data)
            qc.invalidateQueries(['PurchaseOrderIds'])

            Swal.fire({
                title: 'Sukses!',
                text: `Pesanan Pembelian (${variable.po_number}) telah berhasil dikirim!`,
                icon: 'success',
                confirmButtonText: 'OK',
            })
        },
        onError: (error) => {
            console.error('Error submitting Purchase Order:', error)
        },
        onSettled: () => {
            console.log('Submit Purchase Order mutation settled')
        },
    })
}

export const useSubmitRegistrationAsset = () => {
    const qc = useQueryClient()
    return useMutation({
        mutationFn: async (formData: SubmitRegisterAssetPayload) =>
            await submitRegistrationAsset(formData),
        onSuccess: (data, variable) => {
            console.log('Registration Asset submitted successfully:', data)
            qc.invalidateQueries(['RegistrationAssetIds'])

            Swal.fire({
                title: 'Sukses!',
                text: `Pendaftaran Aset (${variable.ra_number}) telah berhasil dikirim!`,
                icon: 'success',
                confirmButtonText: 'OK',
            })
        },
        onError: (error) => {
            console.error('Error submitting Registration Asset:', error)
        },
        onSettled: () => {
            console.log('Submit Registration Asset mutation settled')
        },
    })
}

export const useSaveDraftPurchaseRequest = () => {
    const qc = useQueryClient()
    return useMutation({
        mutationFn: async (formData: SubmitPurchaseRequestPayload) =>
            await saveDraftPurchaseRequest(formData),
        onSuccess: (data) => {
            console.log('Purchase Request draft saved successfully:', data)
            qc.invalidateQueries(['PurchaseRequestIds'])
        },
        onError: (error) => {
            console.error('Error saving Purchase Request draft:', error)
        },
        onSettled: () => {
            console.log('Save Purchase Request draft mutation settled')
        },
    })
}

export const useSetApprovalStatus = () => {
    const qc = useQueryClient()
    return useMutation({
        mutationFn: async (formData: {
            formNumber: number
            layer: number
            status: 'approved' | 'rejected'
            type: string
        }) => {
            const response = await axiosInstance.post<any>(
                `/set-approval`,
                formData
            )
            return response.data
        },
        onSuccess: (data) => {
            console.log('Approval status set successfully:', data)
            qc.invalidateQueries(['submissions'])
        },
        onError: (error) => {
            console.error('Error setting approval status:', error)
        },
        onSettled: () => {
            console.log('Set approval status mutation settled')
        },
    })
}

export const useSendNote = () => {
    const qc = useQueryClient()
    return useMutation({
        mutationFn: async (formData: {
            formNumber: string,
            formType: string,
            text: string,
            sender: string,
            time: string,
        }) => {
            await postSendNote(formData);
        },
        onSuccess: (data) => {
            console.log('Note sent successfully:', data)
            qc.invalidateQueries(['notes'])
        },
        onError: (error) => {
            console.error('Error sending note:', error)
        },
        onSettled: () => {
            console.log('Send note mutation settled')
        },
    })
}